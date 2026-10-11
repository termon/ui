import assert from 'node:assert/strict';
import { readFileSync } from 'node:fs';
import test from 'node:test';

function picker(type) {
    const source = readFileSync(new URL(`../../src/resources/views/components/form/${type}.blade.php`, import.meta.url), 'utf8');
    const script = source.match(/<script>([\s\S]*?)<\/script>/)[1];
    const factory = Function(`${script}; return ${type === 'date' ? 'datePicker' : 'dateTimePicker'};`)();
    const state = factory('');
    state.$refs = { input: { disabled: false } };
    state.$nextTick = () => {};
    state.syncFromValue(type === 'date' ? '2026-10-11' : '2026-10-11 09:30:00');
    return state;
}

for (const type of ['date', 'datetime']) {
    test(`${type}: disabled picker cannot open or change its value`, () => {
        const state = picker(type);
        const original = { value: state.value, displayValue: state.displayValue, day: state.day };
        state.$refs.input.disabled = true;
        state.toggle();
        assert.equal(state.open, false);
        state.selectDate(15);
        state[type === 'date' ? 'setToday' : 'setNow']();
        state.clear();
        if (type === 'datetime') state.updateSelectedTime();
        assert.deepEqual({ value: state.value, displayValue: state.displayValue, day: state.day }, original);
    });

    test(`${type}: enabling picker restores selection and clearing`, () => {
        const state = picker(type);
        state.$refs.input.disabled = true;
        state.toggle();
        state.$refs.input.disabled = false;
        state.toggle();
        assert.equal(state.open, true);
        state.selectDate(15);
        assert.match(state.value, /^2026-10-15/);
        state.clear();
        assert.equal(state.value, '');
        assert.equal(state.displayValue, '');
        assert.equal(state.open, false);
    });
}

for (const type of ['date', 'datetime']) {
    test(`${type}: established date formats round-trip without changing submitted values`, () => {
        const source = readFileSync(new URL(`../../src/resources/views/components/form/${type}.blade.php`, import.meta.url), 'utf8');
        const script = source.match(/<script>([\s\S]*?)<\/script>/)[1];
        const factory = Function('window', 'document', `${script}; return ${type === 'date' ? 'datePicker' : 'dateTimePicker'};`)(
            { addEventListener() {}, removeEventListener() {} },
            { addEventListener() {}, removeEventListener() {} },
        );
        const values = type === 'date'
            ? ['', '2024-02-29', '2026-12-31']
            : ['', '2024-02-29 09:30:00', '2026-12-31T23:59:00', '31-12-2026 23:59:00'];
        for (const value of values) {
            const state = factory(value);
            state.$refs = { input: { disabled: false } };
            const ticks = [];
            state.$nextTick = callback => ticks.push(callback);
            state.$watch = () => {};
            state.init();
            ticks.splice(0).forEach(callback => callback());
            assert.equal(state.value, value, `initial value ${value}`);
            if (value) {
                assert.ok(state.displayValue.length > 0);
                state.selectDate(state.day);
                const expected = type === 'date' ? value : value === '2024-02-29 09:30:00' ? value : '2026-12-31 23:59:00';
                assert.equal(state.value, expected, 'picker selection keeps the existing wire/form format');
            } else {
                assert.equal(state.displayValue, '');
                assert.equal(state.selectedDate, null);
            }
            state.destroy();
        }
    });
}

test('datetime rejects invalid calendar dates and times without crashing or retaining an invalid value', () => {
    const state = picker('datetime');
    for (const value of ['2026-99-11 09:30:00', '2026-02-30 09:30:00', '2025-02-29 09:30:00', '31-04-2026 09:30:00', '2026-10-11 25:00:00', '2026-10-11 09:99:00', '2026-10-11 09:30:99']) {
        assert.equal(state.parseDate(value), null, value);
        state.value = value;
        assert.doesNotThrow(() => state.syncFromValue(value));
        assert.equal(state.value, '');
        assert.equal(state.displayValue, '');
        assert.equal(state.selectedDate, null);
    }
});

for (const type of ['date', 'datetime']) {
    test(`${type}: keyboard opening focuses a day and applying returns focus to the input`, () => {
        const state = picker(type);
        let focus = '';
        const selected = { focus() { focus = 'selected day'; } };
        const first = { focus() { focus = 'first day'; } };
        let hasSelection = true;
        state.$refs.panel = { querySelector(selector) { return selector.includes('aria-pressed') ? hasSelection ? selected : null : first; } };
        state.$refs.input.focus = () => { focus = 'input'; };
        state.$nextTick = callback => callback();
        state.openCalendar();
        assert.equal(state.open, true);
        assert.equal(focus, 'selected day');
        state.selectDate(15);
        assert.match(state.value, /^2026-10-15/);
        state.apply();
        assert.equal(state.open, false);
        assert.equal(focus, 'input');
        hasSelection = false;
        state.openCalendar();
        assert.equal(focus, 'first day');
        state.apply();
        state.$refs.input.disabled = true;
        state.openCalendar();
        assert.equal(state.open, false);
    });
}
