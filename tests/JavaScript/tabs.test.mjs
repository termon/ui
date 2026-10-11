import assert from 'node:assert/strict';
import { readFileSync } from 'node:fs';
import test from 'node:test';

const source = readFileSync(new URL('../../src/resources/views/components/tabs/index.blade.php', import.meta.url), 'utf8');
function fixture({ prefix = 'first-tabs', active = 'One', saved = null, query = '', customPanelId = '' } = {}) {
    const expression = source.match(/x-data="([\s\S]*?)"/)[1].replace(/@js\(\$(\w+)\)/g, (_, prop) => JSON.stringify({ active, tabsId: prefix }[prop]));
    const storage = new Map(saved ? [['activeTab', saved]] : []);
    const history = [];
    const window = { location: { search: query, href: `https://example.test/items${query}` }, history: { replaceState: (...args) => history.push(args[2]) } };
    const state = Function('localStorage', 'window', `return (${expression})`)(
        { getItem: key => storage.get(key) ?? null, setItem: (key, value) => storage.set(key, value) }, window,
    );
    const panels = ['One', "Owner's", 'Three'].map((name, index) => ({
        id: index === 1 ? customPanelId : '', dataset: { name }, attributes: { role: 'tabpanel' },
        getAttribute(key) { return this.attributes[key] ?? null; },
        setAttribute(key, value) { this.attributes[key] = value; },
    }));
    const focused = [];
    const buttons = panels.map((panel, index) => ({ focus() { focused.push(index); state.switchTab(panel.dataset.name); } }));
    state.$refs = { tabs: { children: [...panels, { getAttribute: () => null }] }, tabList: { querySelectorAll: () => buttons } };
    state.$nextTick = callback => callback();
    state.init();
    return { state, panels, focused, storage, history };
}

test('tab keyboard controls wrap and activate the newly focused tab', () => {
    const f = fixture();
    f.state.focusNextTab(0);
    assert.deepEqual(f.focused, [1]);
    assert.equal(f.state.activeTab, "Owner's");
    f.state.focusNextTab(2);
    assert.equal(f.state.activeTab, 'One');
    f.state.focusPreviousTab(0);
    assert.equal(f.state.activeTab, 'Three');
    f.state.focusTab(0);
    f.state.focusTab(f.state.tabHeadings.length - 1);
    assert.deepEqual(f.focused, [1, 0, 2, 0, 2]);
    assert.equal(f.storage.get('activeTab'), 'Three');
    assert.equal(new URL(f.history.at(-1)).searchParams.get('tab'), 'Three');
});

test('tabs connect actual panel IDs to unique button IDs and preserve host panel IDs', () => {
    const first = fixture({ customPanelId: 'host-panel' });
    const second = fixture({ prefix: 'second-tabs' });
    assert.deepEqual(first.state.panelIds, ['first-tabs-panel-1', 'host-panel', 'first-tabs-panel-3']);
    const buttonIds = [];
    for (const f of [first, second]) {
        f.panels.forEach((panel, index) => {
            const buttonId = f.state.buttonId(index);
            assert.equal(panel.attributes['aria-labelledby'], buttonId);
            assert.equal(f.state.panelIds[index], panel.id);
            buttonIds.push(buttonId);
        });
    }
    assert.equal(new Set(buttonIds).size, 6);
    assert.equal(first.state.tabHeadings.length, 3);
});

test('saved and query-string tab selection retain established precedence', () => {
    assert.equal(fixture({ saved: 'Three', query: '?tab=One' }).state.activeTab, 'Three');
    assert.equal(fixture({ saved: 'unknown', query: '?tab=Three' }).state.activeTab, 'Three');
    assert.equal(fixture({ active: 'unknown' }).state.activeTab, 'One');
});

test('tab panels use their parent active value without evaluating their names', () => {
    const tabSource = readFileSync(new URL('../../src/resources/views/components/tabs/tab.blade.php', import.meta.url), 'utf8');
    const name = "Owner's";
    const expression = tabSource.match(/x-data="([\s\S]*?)"/)[1].replace('@js($name)', JSON.stringify(name));
    const panel = Function(`return (${expression})`)();
    panel.showIfActive(name);
    assert.equal(panel.show, true);
    panel.showIfActive('One');
    assert.equal(panel.show, false);
});
