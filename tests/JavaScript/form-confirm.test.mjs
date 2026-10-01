import assert from 'node:assert/strict';
import { readFileSync } from 'node:fs';
import test from 'node:test';

const source = readFileSync(new URL('../../src/resources/views/components/form/confirm.blade.php', import.meta.url), 'utf8');
function fixture({ livewire = false, form = null, call } = {}) {
    const props = { confirmingProperty: 'confirming', prepareAction: 'prepare', confirmAction: 'confirm', cancelAction: 'cancel' };
    const expression = source.match(/x-data="([\s\S]*?)"/)[1].replace(/@js\((.*?)\)/g, (_, value) => JSON.stringify(value === "$mode === 'livewire'" ? livewire : props[value.slice(1)]));
    const component = Function(`return (${expression})`)();
    const ticks = [];
    const calls = [];
    let confirming = false;
    const focus = { trigger: 0, cancel: 0 };
    const dialog = { open: false, showModal() { this.open = true; }, close() { this.open = false; }, getBoundingClientRect: () => ({ left: 10, right: 100, top: 10, bottom: 100 }) };
    component.$el = { closest: () => form };
    component.$refs = { dialog, trigger: { focus() { focus.trigger++; } }, cancel: { focus() { focus.cancel++; } } };
    component.$nextTick = fn => ticks.push(fn);
    component.$wire = { $get(property) { assert.equal(property, 'confirming'); return confirming; }, async $call(action) { calls.push(action); if (call) return call(action); confirming = action === 'prepare'; } };
    return { component, dialog, calls, focus, setConfirming(value) { confirming = value; }, flush() { ticks.splice(0).forEach(fn => fn()); } };
}
function formFixture({ valid = true, prevented = false, emit = true, failure = false } = {}) {
    let observe;
    let submissions = 0;
    let reenter = () => {};
    const form = { reportValidity: () => valid, addEventListener(type, fn) { assert.equal(type, 'submit'); observe = fn; }, removeEventListener(type, fn) { assert.equal(observe, fn); observe = null; }, requestSubmit() { submissions++; reenter(); if (failure) throw Error('submission failed'); if (emit) observe({ defaultPrevented: prevented }); } };
    const result = fixture({ form });
    reenter = () => result.component.submit();
    return { ...result, submissions: () => submissions, listener: () => observe };
}

test('normal form opens, validates, submits once and stays locked for navigation', () => {
    const f = formFixture();
    f.component.open();
    assert.equal(f.dialog.open, true);
    assert.equal(f.focus.cancel, 1);
    f.component.submit();
    f.component.submit();
    f.flush();
    assert.equal(f.submissions(), 1);
    assert.equal(f.dialog.open, false);
    assert.equal(f.component.busy, true);
    assert.equal(f.focus.trigger, 0);
    assert.equal(f.listener(), null);
    assert.deepEqual(f.calls, []);
});
for (const options of [{ prevented: true }, { emit: false }]) {
    test(`intercepted or absent submit event releases lock: ${JSON.stringify(options)}`, () => {
        const f = formFixture(options);
        f.component.open(); f.component.submit(); f.flush();
        assert.equal(f.component.busy, false);
        assert.equal(f.focus.trigger, 1);
        f.component.open(); f.component.submit();
        assert.equal(f.submissions(), 2);
        assert.equal(f.listener(), null);
    });
}
test('invalid form, missing form, and submission exception remain retryable', () => {
    const invalid = formFixture({ valid: false });
    invalid.component.open(); invalid.component.submit(); invalid.flush();
    assert.equal(invalid.submissions(), 0);
    assert.equal(invalid.component.busy, false);
    assert.equal(invalid.focus.trigger, 1);
    assert.doesNotThrow(() => fixture().component.submit());
    const failed = formFixture({ failure: true });
    assert.throws(() => failed.component.submit(), /submission failed/);
    assert.equal(failed.component.busy, false);
    assert.equal(failed.listener(), null);
});
test('form dismissal closes without submitting', () => {
    const f = formFixture(); f.component.open(); f.component.dismiss(); f.flush();
    assert.equal(f.dialog.open, false);
    assert.equal(f.submissions(), 0);
    assert.equal(f.focus.trigger, 1);
});
test('Livewire prepare and confirm use server state without submitting surrounding form', async () => {
    const f = fixture({ livewire: true, form: { requestSubmit() { assert.fail('must not submit'); } } });
    await f.component.open(); f.flush();
    assert.equal(f.dialog.open, true);
    assert.ok(f.focus.cancel > 0);
    await f.component.submit(); f.flush();
    assert.equal(f.dialog.open, false);
    assert.equal(f.component.busy, false);
    assert.ok(f.focus.trigger > 0);
    assert.deepEqual(f.calls, ['prepare', 'confirm']);
});
test('Livewire cancellation calls cancel only while server state is confirming', async () => {
    const f = fixture({ livewire: true });
    f.component.dismiss(); assert.deepEqual(f.calls, []);
    await f.component.open();
    f.component.dismiss(); await new Promise(resolve => setImmediate(resolve)); f.flush();
    assert.deepEqual(f.calls, ['prepare', 'cancel']);
    assert.equal(f.dialog.open, false);
    f.component.dismiss(); assert.equal(f.calls.length, 2);
});
for (const action of ['prepare', 'confirm', 'cancel']) {
    test(`Livewire ${action} blocks duplicate actions and dismissal until settled`, async () => {
        let resolve;
        const f = fixture({ livewire: true, call: () => new Promise(done => { resolve = done; }) });
        f.setConfirming(true); f.component.sync(true);
        const pending = f.component.run(action);
        assert.equal(f.component.busy, true);
        await f.component.open(); await f.component.submit(); f.component.dismiss();
        assert.deepEqual(f.calls, [action]);
        assert.equal(f.dialog.open, true);
        f.setConfirming(false); resolve(); await pending; f.flush();
        assert.equal(f.component.busy, false);
        assert.equal(f.dialog.open, false);
    });
    test(`Livewire ${action} failure releases lock, preserves server state, and allows retry`, async () => {
        const f = fixture({ livewire: true, call: async () => { throw Error('server failed'); } });
        f.setConfirming(true);
        await assert.rejects(f.component.run(action), /server failed/); f.flush();
        assert.equal(f.component.busy, false);
        assert.equal(f.dialog.open, true);
        await assert.rejects(f.component.run(action), /server failed/);
        assert.deepEqual(f.calls, [action, action]);
    });
}
test('Livewire confirmation remains open when server validation retains confirming state', async () => {
    const f = fixture({ livewire: true, call: async () => {} });
    f.setConfirming(true); await f.component.submit();
    assert.equal(f.dialog.open, true);
    assert.equal(f.component.busy, false);
    f.setConfirming(false); f.component.sync(false); f.flush();
    assert.equal(f.dialog.open, false);
    assert.ok(f.focus.trigger > 0);
});
test('backdrop requires both pointerdown and click outside; busy dismissals are ignored', () => {
    const f = fixture(); f.component.open();
    const dispatch = (name, event) => Function('$event', `with(this) { ${source.slice(source.indexOf("    <dialog")).match(new RegExp(`${name}="([^"]*)"`))[1]} }`).call(f.component, event);
    const outside = { target: f.dialog, clientX: 0, clientY: 0 };
    const inside = { target: f.dialog, clientX: 50, clientY: 50 };
    dispatch('x-on:pointerdown', inside); dispatch('x-on:click', outside); assert.equal(f.dialog.open, true);
    f.component.busy = true;
    dispatch('x-on:cancel.prevent', {}); assert.equal(f.dialog.open, true);
    f.component.busy = false;
    dispatch('x-on:pointerdown', outside); dispatch('x-on:click', outside); assert.equal(f.dialog.open, false);
    assert.equal(f.component.backdropPressed, false);
});

test('multiple confirmation instances keep their state and actions independent', async () => {
    const first = fixture({ livewire: true });
    const second = fixture({ livewire: true });
    await first.component.open();
    assert.equal(first.dialog.open, true);
    assert.equal(second.dialog.open, false);
    assert.deepEqual(second.calls, []);
    await second.component.open();
    await first.component.submit();
    assert.equal(first.dialog.open, false);
    assert.equal(second.dialog.open, true);
});

test('Escape and native close delegate to Livewire cancellation', async () => {
    for (const event of ['x-on:cancel.prevent', 'x-on:close.self']) {
        const f = fixture({ livewire: true });
        await f.component.open();
        if (event === 'x-on:close.self') f.dialog.open = false;
        const expression = source.match(new RegExp(`${event}="([^"]*)"`))[1];
        Function(`with(this) { ${expression} }`).call(f.component);
        await new Promise(resolve => setImmediate(resolve));
        assert.deepEqual(f.calls, ['prepare', 'cancel']);
        assert.equal(f.dialog.open, false);
    }
});
