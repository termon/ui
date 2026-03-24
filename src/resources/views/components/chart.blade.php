@props([
    'id',
    'config',
])

@php
    $chartConfig = isset($config) ? \Illuminate\Support\Js::from($config) : trim((string) $slot);
@endphp

@once
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endonce

<div
    x-data="{
        chart: null,
        observer: null,
        config: {{ $chartConfig }},
        isDark() {
            return document.documentElement.classList.contains('dark');
        },
        init() {
            this.render();

            this.observer = new MutationObserver(() => this.render());
            this.observer.observe(document.documentElement, {
                attributes: true,
                attributeFilter: ['class'],
            });
        },
        destroy() {
            if (this.observer) this.observer.disconnect();
            if (this.chart) this.chart.destroy();
        },
        cloneValue(value) {
            if (Array.isArray(value)) {
                return value.map((item) => this.cloneValue(item));
            }

            if (value && typeof value === 'object') {
                return Object.fromEntries(
                    Object.entries(value).map(([key, item]) => [key, this.cloneValue(item)])
                );
            }

            return value;
        },
        themedConfig() {
            const dark = this.isDark();
            const textColor = dark ? '#e5e7eb' : '#0f172a';
            const mutedTextColor = dark ? '#d1d5db' : '#334155';
            const gridColor = dark ? '#374151' : '#e5e7eb';
            const surfaceColor = dark ? '#111827' : '#ffffff';
            const tooltipBg = dark ? '#1f2937' : '#f8fafc';

            const config = this.cloneValue(this.config);
            config.options ??= {};
            config.options.responsive ??= true;
            config.options.maintainAspectRatio ??= false;
            config.options.plugins ??= {};
            config.options.plugins.legend ??= {};
            config.options.plugins.legend.labels ??= {};
            config.options.plugins.legend.labels.color ??= mutedTextColor;
            config.options.plugins.title ??= {};
            config.options.plugins.title.color ??= textColor;
            config.options.plugins.tooltip ??= {};
            config.options.plugins.tooltip.backgroundColor ??= tooltipBg;
            config.options.plugins.tooltip.titleColor ??= textColor;
            config.options.plugins.tooltip.bodyColor ??= textColor;
            config.options.scales ??= {};

            Object.values(config.options.scales).forEach((scale) => {
                scale.grid ??= {};
                scale.grid.color ??= gridColor;
                scale.ticks ??= {};
                scale.ticks.color ??= mutedTextColor;
                scale.border ??= {};
                scale.border.color ??= gridColor;
                scale.title ??= {};
                scale.title.color ??= textColor;
            });

            config.plugins ??= [];
            config.plugins.push({
                id: 'ouiChartBackground',
                beforeDraw: (chart) => {
                    const { ctx, chartArea } = chart;
                    if (!chartArea) return;
                    ctx.save();
                    ctx.fillStyle = surfaceColor;
                    ctx.fillRect(chartArea.left, chartArea.top, chartArea.right - chartArea.left, chartArea.bottom - chartArea.top);
                    ctx.restore();
                },
            });

            return config;
        },
        render() {
            if (!window.Chart) return;
            if (this.chart) this.chart.destroy();
            this.chart = new Chart(this.$refs.canvas, this.themedConfig());
        },
    }"
>
    <div {{ $attributes->merge(['class' => 'w-full h-96']) }}>
        <canvas id="{{ $id }}" x-ref="canvas"></canvas>
    </div>
</div>
