@props([
    'id',
    'config',
    'ariaLabel' => null,
    'fallbackText' => null,
])

@php
    $chartConfig = isset($config) ? \Illuminate\Support\Js::from($config) : trim((string) $slot);
    $accessibleLabel = $ariaLabel ?: \Illuminate\Support\Str::headline($id);
@endphp

@once
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.5.1"></script>
@endonce

<div
    x-data="{
        chart: null,
        observer: null,
        config: {{ $chartConfig }},
        isDark() {
            return document.documentElement.classList.contains('dark');
        },
        prefersReducedMotion() {
            return window.matchMedia('(prefers-reduced-motion: reduce)').matches;
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
        applyTooltipFormatting(config) {
            const labelMap = config.options?.plugins?.tooltip?.labelMap;
            const datasetLabelMap = config.options?.plugins?.tooltip?.datasetLabelMap;
            const valueMap = config.options?.plugins?.tooltip?.valueMap;
            const secondaryValueMap = config.options?.plugins?.tooltip?.secondaryValueMap;
            const displayValue = config.options?.plugins?.tooltip?.displayValue ?? true;

            if (!labelMap && !datasetLabelMap && !valueMap && !secondaryValueMap && displayValue) return;

            config.options.plugins.tooltip.callbacks ??= {};
            config.options.plugins.tooltip.callbacks.label ??= (context) => {
                const label = context.label ?? '';
                const datasetLabel = context.dataset?.label ?? '';
                const value = context.formattedValue ?? context.raw ?? '';
                const tooltipLabel = datasetLabelMap?.[datasetLabel] ?? labelMap?.[label] ?? (datasetLabel || label);
                const tooltipValue = valueMap?.[datasetLabel]?.[label] ?? value;
                const secondaryValue = secondaryValueMap?.[datasetLabel]?.[label] ?? secondaryValueMap?.[label];
                const lines = [displayValue ? `${tooltipLabel}: ${tooltipValue}` : tooltipLabel];

                if (secondaryValue) lines.push(secondaryValue);

                return lines;
            };

            delete config.options.plugins.tooltip.labelMap;
            delete config.options.plugins.tooltip.datasetLabelMap;
            delete config.options.plugins.tooltip.valueMap;
            delete config.options.plugins.tooltip.secondaryValueMap;
            delete config.options.plugins.tooltip.displayValue;
        },
        themedConfig() {
            const dark = this.isDark();
            const textColor = dark ? '#e5e7eb' : '#0f172a';
            const mutedTextColor = dark ? '#d1d5db' : '#334155';
            const gridColor = dark ? '#374151' : '#e5e7eb';
            const surfaceColor = dark ? '#111827' : '#ffffff';
            const tooltipBg = dark ? '#1f2937' : '#f8fafc';

            const config = this.cloneValue(this.config);
            const centreText = config.options?.plugins?.centreText;
            const clickEventName = config.options?.emitOnClick;
            config.options ??= {};
            config.options.responsive ??= true;
            config.options.maintainAspectRatio ??= false;
            if (this.prefersReducedMotion()) config.options.animation = false;
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

            if (config.type === 'pie' || config.type === 'doughnut') {
                config.options.plugins.legend.position ??= 'bottom';
                config.options.plugins.legend.align ??= 'start';
                config.options.plugins.legend.labels.usePointStyle ??= true;
                config.options.plugins.legend.labels.pointStyle ??= 'circle';
                config.options.plugins.legend.labels.padding ??= 16;

                config.data?.datasets?.forEach((dataset) => {
                    dataset.hoverOffset ??= 8;
                    dataset.spacing ??= 2;
                    dataset.borderRadius ??= 3;
                });
            }

            if (clickEventName) {
                config.options.onClick ??= (event, elements, chart) => {
                    const element = elements[0];
                    if (!element) return;

                    this.$el.dispatchEvent(new CustomEvent(clickEventName, {
                        bubbles: true,
                        detail: {
                            dataIndex: element.index,
                            datasetIndex: element.datasetIndex,
                            label: chart.data.labels?.[element.index] ?? null,
                            value: chart.data.datasets?.[element.datasetIndex]?.data?.[element.index] ?? null,
                        },
                    }));
                };
            }

            delete config.options.emitOnClick;
            delete config.options.plugins.centreText;

            this.applyTooltipFormatting(config);

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

            if (centreText?.text) {
                config.plugins.push({
                    id: 'ouiChartCentreText',
                    afterDatasetsDraw: (chart) => {
                        const { ctx, chartArea } = chart;
                        if (!chartArea) return;

                        const x = (chartArea.left + chartArea.right) / 2;
                        const y = (chartArea.top + chartArea.bottom) / 2;
                        ctx.save();
                        ctx.fillStyle = centreText.color ?? textColor;
                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'middle';
                        ctx.font = `600 ${centreText.fontSize ?? 24}px sans-serif`;
                        ctx.fillText(centreText.text, x, y - (centreText.subtext ? 8 : 0));

                        if (centreText.subtext) {
                            ctx.fillStyle = centreText.subtextColor ?? mutedTextColor;
                            ctx.font = `400 ${centreText.subtextFontSize ?? 12}px sans-serif`;
                            ctx.fillText(centreText.subtext, x, y + 16);
                        }

                        ctx.restore();
                    },
                });
            }

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
        <canvas id="{{ $id }}" x-ref="canvas" role="img" aria-label="{{ $accessibleLabel }}">
            {{ $fallbackText ?: $accessibleLabel }}
        </canvas>
    </div>
</div>
