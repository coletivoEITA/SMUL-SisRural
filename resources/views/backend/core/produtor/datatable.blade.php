<script>
$(document).ready(function() {
    $('#table').DataTable({
        "dom": '<"top table-top"f>rt<"row table-bottom"<"col-sm-12 col-md-5"il><"col-sm-12 col-md-7"p>><"clear">',
        "processing": true,
        "serverSide": true,
        "lengthChange": true,                
        "ajax": '{{ route($datatable_route) }}',
        "language": {
            "url": '{{ asset('js/datatables-pt-br.json') }}'
        },
        "columns": [{
                "data": "uid"
            },
            {
                "data": "nome"
            },
            {
                "data": "cpf"
            },
            {
                "data": "subprefeitura"
            },
            {
                "data": "bairro"
            },
            {
                "data": "telefone_1"
            },
            {
                "data": "status"
            },
            // {
            //     "data": "status_observacao"
            // },                    
            {
                "data": "actions",
                "searchable": false,
                "orderable": false,
                render: function(data) {
                    return htmlDecode(data);
                }
            }
        ]
    }).on('draw', function() {
        initAutoLink($("#table"));
    });

    addAutoLink(function() {
        debounceSearch('#table');
    });
});

</script>
