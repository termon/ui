import assert from 'node:assert/strict';
import { readFileSync } from 'node:fs';
import test from 'node:test';
const source = readFileSync(new URL('../../src/resources/js/date-picker.js', import.meta.url), 'utf8');
function picker(options = {}) {
    const events = [], ticks = [], watches = {};
    const window = { requestAnimationFrame: callback => ticks.push(callback), innerWidth: 1024, innerHeight: 768, addEventListener: (...args) => events.push(args), removeEventListener: (...args) => events.push(args) };
    const document = { addEventListener: (...args) => events.push(args), removeEventListener: (...args) => events.push(args) };
    const state = Function('window', 'document', `return (${source})`)(window, document)({ locale: 'en-GB', ...options });
    const form = { addEventListener: (...args) => events.push(args), removeEventListener: (...args) => events.push(args) };
    state.$refs = { input: { disabled: false, readOnly: false, form, setCustomValidity(message) { this.validityMessage = message; }, focus() {}, getBoundingClientRect: () => ({ left: 1000, top: 700, bottom: 740 }) }, panel: { offsetWidth: 320, offsetHeight: 300, querySelector() { return { focus() {} }; } } };
    state.$watch = (key, callback) => watches[key] = callback;
    state.$nextTick = callback => ticks.push(callback);
    state.init();
    return { state, events, watches, flush() { while (ticks.length) ticks.shift()(); } };
}
for (const datetime of [false, true]) {
    test(`${datetime ? 'datetime' : 'date'}: strict parsing, bounds, external synchronization and cleanup`, () => {
        const value = datetime ? '2024-02-29 09:02:17' : '2024-02-29';
        const { state, watches, events } = picker({ datetime, value, min: '2024-02-01', max: '2024-02-29' });
        assert.equal(state.value, value);
        assert.equal(state.text, value);
        for (const date of ['2023-02-29', '2024-02-30', '2024-13-01']) assert.equal(state.parse(date + (datetime ? ' 09:00:00' : '')), null);
        assert.equal(state.allowed(datetime ? '2024-03-01 00:00:00' : '2024-03-01'), false);
        assert.equal(state.allowed(value), true);
        state.value = null; watches.value(); assert.equal(state.text, '');
        state.destroy();
        assert.equal(events.length, 6);
        assert.equal(events[0][1], events[3][1]);
        assert.equal(events[1][1], events[4][1]);
        assert.equal(events[2][1], events[5][1]);
    });
}
for (const datetime of [false, true]) {
    test(`${datetime ? 'datetime' : 'date'}: manual values reach Laravel without browser validation`, () => {
        const { state, watches, flush } = picker({ datetime, min: '2026-01-01', max: '2026-12-31' });
        for (const raw of ['', 'not a date', datetime ? '2027-03-99 09:00:00' : '2027-03-99', datetime ? '2027-01-01 09:00:00' : '2027-01-01']) {
            state.text = raw; state.edit(); watches.value(); flush();
            assert.equal(state.value, raw);
            assert.equal(state.text, raw);
            assert.equal(state.$refs.input.validityMessage, undefined);
        }
        state.text = datetime ? '2026-10-15T09:02' : '2026-10-15';
        state.edit(); watches.value(); flush();
        assert.equal(state.value, datetime ? '2026-10-15 09:02:00' : '2026-10-15');
        assert.equal(state.cursor, '2026-10-15');
    });
}
test('readonly and disabled block opening, selection, clearing and typing', () => {
    for (const flag of ['readOnly', 'disabled']) {
        const { state } = picker({ value: '2026-10-12' }); state.$refs.input[flag] = true;
        state.show(); state.choose('2026-10-15'); state.clear(); state.today(); state.edit();
        assert.equal(state.open, false); assert.equal(state.value, '2026-10-12');
    }
});
test('keyboard navigation crosses month boundaries and clamps month changes', () => {
    const { state, flush } = picker({ value: '2024-01-31' });
    state.shiftMonth(1); flush(); assert.equal(state.cursor, '2024-02-29');
    state.navigate({ key: 'ArrowRight', preventDefault() {} }); flush(); assert.equal(state.cursor, '2024-03-01');
    state.navigate({ key: 'Home', preventDefault() {} }); flush(); assert.equal(state.cursor, '2024-02-25');
});
test('popup positions before visibility, reset restores initial value, instances are independent', () => {
    const { state, events, flush } = picker({ value: '2026-10-12' });
    const other = picker({ value: '2026-01-01' }).state;
    state.show(); assert.equal(state.panelStyle.visibility, 'hidden'); flush();
    assert.deepEqual(state.panelStyle, { visibility: 'visible', top: '392px', left: '696px' });
    state.choose('2026-10-15'); assert.equal(state.value, '2026-10-15'); assert.equal(other.value, '2026-01-01');
    events[2][1](); flush(); assert.equal(state.value, '2026-10-12'); assert.equal(state.open, false);
});
test('datetime selection preserves seconds and clamps to a boundary time', () => {
    const { state } = picker({ datetime: true, value: '2026-10-12 09:02:17', min: '2026-10-13 10:00:00' });
    state.choose('2026-10-13'); assert.equal(state.value, '2026-10-13 10:00:00');
    state.changeTime('11:03:19'); assert.equal(state.value, '2026-10-13 11:03:19');
    assert.equal(state.parse('2026-10-13 25:00:00'), null);
    assert.ok(state.parse('2026-03-29 01:30:00'), 'calendar values do not reject daylight-saving gaps');
});

test('month and year navigation clamp leap days without changing the selected value', () => {
    const { state, flush } = picker({ value: '2024-02-29' });
    state.shiftMonth(12); flush();
    assert.equal(state.cursor, '2025-02-28');
    assert.equal(state.value, '2024-02-29');
    state.navigate({ key: 'PageUp', shiftKey: true, preventDefault() {} }); flush();
    assert.equal(state.cursor, '2024-02-28');
    state.navigate({ key: 'End', preventDefault() {} }); flush();
    assert.equal(state.cursor, '2024-03-02');
});

for (const datetime of [false, true]) {
    test(`${datetime ? 'datetime' : 'date'}: calendar choices outside bounds do not change the value`, () => {
        const value = datetime ? '2026-10-12 09:02:17' : '2026-10-12';
        const { state, flush } = picker({ datetime, value, min: '2026-10-01', max: '2026-10-31' });
        state.choose('2026-11-01');
        assert.equal(state.value, value);
        state.clear(); flush();
        assert.equal(state.value, '');
        assert.equal(state.text, '');
        assert.equal(state.open, false);
    });
}
