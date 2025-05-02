@props([ 
    'id',
    'config' 
])

<!-- This component is used to render a Highcharts chart. It uses Alpine.js for reactivity and localStorage to persist the dark mode preference. -->
@once
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>
@endonce

<div
    x-data="{
        chart: null,
        id:'{{ $id }}',
        darker: localStorage.getItem('dark') === 'true' || (localStorage.getItem('dark') === null && window.matchMedia('(prefers-color-scheme: dark)').matches ),
        init() {
            //console.log('Highcharts component initialized');           
            Highcharts.setOptions(this.$data.darker ? this.darkTheme : this.lightTheme);
            this.render();

            this.$watch('dark', val => {
                //console.log('Dark mode changed to: ' + val);
                Highcharts.setOptions(val ? this.darkTheme : this.lightTheme);
                this.render();
            });          
        },

        get darkTheme() {            
            return  {
                chart: { backgroundColor: '#1F2937', style: { color: '#e5e5e5' } },
                title: { style: { color: '#e5e5e5' } },
                xAxis: { labels: { style: { color: '#d4d4d4' } },  lineColor: '#404040', gridLineColor: '#262626' },
                yAxis: { labels: { style: { color: '#d4d4d4' } },   gridLineColor: '#262626'},
                legend: { itemStyle: { color: '#d4d4d4' }, itemHoverStyle: { color: '#f5f5f5' } },
                tooltip: { backgroundColor: '#262626', style: { color: '#f5f5f5' } }
            } 
        },

        get lightTheme() {
            return {
                chart: { backgroundColor: '#ffffff', style: { color: '#1F2937' } },
                title: { style: { color: '#0f172a' } },
                xAxis: { labels: { style: { color: '#334155' } }, lineColor: '#e5e7eb' },
                yAxis: { labels: { style: { color: '#334155' } }, gridLineColor: '#e5e7eb' },
                legend: { itemStyle: { color: '#0f172a' } },
                tooltip: { backgroundColor: '#f9fafb', style: { color: '#0f172a' } }
            }             
        },
        
        render(config) {
            //console.log('Rendering chart');
            if (this.chart) this.chart.destroy();           
            this.chart = Highcharts.chart('{{ $id }}', {{ $config ?? $slot }});            
        }, 
    }"  
>
    <div id="{{ $id }}" {{ $attributes->merge(['class' => "w-full h-96"])}}></div>
</div>