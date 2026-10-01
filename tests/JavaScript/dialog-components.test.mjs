import assert from 'node:assert/strict';
import { readFileSync } from 'node:fs';
import test from 'node:test';

const view = name => readFileSync(new URL(`../../src/resources/views/components/${name}.blade.php`, import.meta.url), 'utf8');
const modalSource = view('modal/index');
const state = (source, props = {}) => Function(`return (${source.match(/x-data="([\s\S]*?)"/)[1].replace(/@js\(\$(\w+)\)/g, (_, key) => JSON.stringify(props[key]))})`)();
const handler = (source, name, component, event) => Function('$event', `with (this) { ${source.match(new RegExp(`${name}="([^"]*)"`))[1]} }`).call(component, event);

for (const dismissable of [false, true]) {
    test(`dismissable=${dismissable} controls native cancellation and backdrop dismissal`, () => {
        const component = state(modalSource, { name: 'edit', show: true, dismissable });
        const dialog = { getBoundingClientRect: () => ({ left: 10, right: 100, top: 10, bottom: 100 }) };
        component.$el = dialog;
        let prevented = false;
        handler(modalSource, 'x-on:cancel.self', component, { preventDefault() { prevented = true; } });
        assert.equal(prevented, !dismissable);
        const outside = { target: dialog, clientX: 0, clientY: 0 };
        handler(modalSource, 'x-on:pointerdown', component, outside);
        handler(modalSource, 'x-on:click', component, outside);
        assert.equal(component.show, !dismissable);
        assert.equal(component.backdropPressed, false);
    });
}

test('inside clicks and drags from content to backdrop do not dismiss', () => {
    const component = state(modalSource, { name: 'edit', show: true, dismissable: true });
    const dialog = { getBoundingClientRect: () => ({ left: 10, right: 100, top: 10, bottom: 100 }) };
    component.$el = dialog;
    const inside = { target: dialog, clientX: 50, clientY: 50 };
    const outside = { target: dialog, clientX: 0, clientY: 0 };
    for (const click of [inside, outside]) {
        handler(modalSource, 'x-on:pointerdown', component, inside);
        handler(modalSource, 'x-on:click', component, click);
        assert.equal(component.show, true);
    }
    handler(modalSource.slice(modalSource.indexOf('<button')), 'x-on:click', component, {});
    assert.equal(component.show, false); // Explicit close button remains available.
});
