<div class="card-chart" id="chart_2_9_rendimento_comercializacao">
    <div class="txt-title">Rendimento da Comercialização</div>

    <div class="chart"></div>

    <div class="txt-legend">
        Não respondeu: <span>-</span>
        &nbsp;&nbsp;Total: <span>-</span>
    </div>
</div>

@push('after-scripts')
    <script>
        function chart_2_9_rendimento_comercializacao(ret) {
            var values = [
                ['Participação da renda agrícola na renda familiar', 'Total']
            ];

            if (!ret) {
                ret = [];
            }

            var total = 0;
            for(var i =0; i < ret.itens.length; i++) {
                var item = ret.itens[i];
                values.push([item.nome+" ("+item.total+")", item.total]);
                total += item.total;
            }
            total += ret.nao_respondeu;

            var options = {
                backgroundColor: {
                    fill:'transparent'
                },
                chartArea: { width:"94%",height:"92%" },
                legend: {'position': 'right'},
                pieSliceText: 'percentage',
                theme: 'material',
                // sliceVisibilityThreshold:0
            };

            var arrayToDataTable = google.visualization.arrayToDataTable(values);

            var chart = new google.visualization.PieChart($('#chart_2_9_rendimento_comercializacao .chart')[0]);
            chart.draw(arrayToDataTable, options);

            $("#chart_2_9_rendimento_comercializacao .txt-legend span:first").html(ret.nao_respondeu);
            $("#chart_2_9_rendimento_comercializacao .txt-legend span:last").html(total);
        }
    </script>
@endpush
