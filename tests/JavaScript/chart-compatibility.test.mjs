import assert from 'node:assert/strict';
import { readFileSync } from 'node:fs';
import test from 'node:test';

function chart(config) {
    const source = readFileSync(new URL('../../src/resources/views/components/chart.blade.php', import.meta.url), 'utf8');
    const expression = source.match(/x-data="([\s\S]*?)"/)[1].replace('{{ $chartConfig }}', JSON.stringify(config));
    const document = { documentElement: { classList: { contains: () => false } } };
    const window = { matchMedia: () => ({ matches: false }) };
    const component = Function('document', 'window', 'CustomEvent', `return (${expression})`)(
        document, window, class { constructor(type, options) { this.type = type; Object.assign(this, options); } },
    );
    return component;
}

const base = () => ({ type: 'bar', data: { labels: ['One'], datasets: [{ label: 'Existing series', data: [3] }] } });

test('existing chart data and explicit theme options survive theming without mutating caller config', () => {
    const config = base();
    config.options = {
        responsive: false,
        maintainAspectRatio: true,
        animation: { duration: 150 },
        plugins: { legend: { labels: { color: '#123456' } }, tooltip: { backgroundColor: '#abcdef' } },
        scales: { y: { ticks: { color: '#654321' } } },
    };
    const original = structuredClone(config);
    const result = chart(config).themedConfig();
    assert.deepEqual(result.data, original.data);
    assert.equal(result.options.responsive, false);
    assert.equal(result.options.maintainAspectRatio, true);
    assert.equal(result.options.animation.duration, 150);
    assert.equal(result.options.plugins.legend.labels.color, '#123456');
    assert.equal(result.options.plugins.tooltip.backgroundColor, '#abcdef');
    assert.equal(result.options.scales.y.ticks.color, '#654321');
    assert.deepEqual(config, original);
});

test('existing custom plugins and callbacks survive chart configuration cloning', () => {
    const component = chart(base());
    const callback = () => 'Host label';
    const hostPlugin = { id: 'host-plugin', beforeDraw() {} };
    component.config.plugins = [hostPlugin];
    component.config.options = { plugins: { tooltip: { callbacks: { label: callback }, labelMap: { One: 'Mapped' } } } };
    const result = component.themedConfig();
    assert.equal(result.options.plugins.tooltip.callbacks.label, callback);
    assert.equal(result.plugins[0].id, 'host-plugin');
    assert.equal(result.plugins[0].beforeDraw, hostPlugin.beforeDraw);
    assert.deepEqual(component.config.plugins, [hostPlugin]);
});

test('existing chart click event retains its name, bubbling, and detail keys', () => {
    const config = base();
    config.options = { emitOnClick: 'host-chart-click' };
    const component = chart(config);
    const events = [];
    component.$el = { dispatchEvent: event => events.push(event) };
    const result = component.themedConfig();
    result.options.onClick({}, [], { data: config.data });
    assert.equal(events.length, 0);
    result.options.onClick({}, [{ index: 0, datasetIndex: 0 }], { data: config.data });
    assert.equal(events[0].type, 'host-chart-click');
    assert.equal(events[0].bubbles, true);
    assert.deepEqual(events[0].detail, { dataIndex: 0, datasetIndex: 0, label: 'One', value: 3 });
});

for (const type of ['bar', 'pie', 'doughnut']) {
    test(`${type}: disabled plugins remain disabled when applying the theme`, () => {
        for (const plugins of [false, { legend: false }, { legend: false, title: false, tooltip: false }]) {
            const config = base();
            config.type = type;
            config.options = { plugins };
            const original = structuredClone(config);
            const result = chart(config).themedConfig();
            if (plugins === false) {
                assert.equal(result.options.plugins, false);
            } else {
                assert.equal(result.options.plugins.legend, false);
                if (plugins.title === false) assert.equal(result.options.plugins.title, false);
                if (plugins.tooltip === false) assert.equal(result.options.plugins.tooltip, false);
            }
            assert.deepEqual(config, original);
        }
    });
}
