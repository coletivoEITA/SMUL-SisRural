@extends('backend.layouts.app-template', ['iframe'=>true])

@section('body')
    <div class="card card-ater">
        <div class="card-header">
            <div class="row">
                <div class="col-10">
                    <h1 class="card-title mb-0 h4 mt-1">
                        {{$title}}
                    </h1>
                </div>

                <div class="col-2 pull-right">
                    <div class="float-right">
                        <a href="{{$addUrl}}" class="btn btn-primary px-5">
                            Adicionar
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body">
            <table id="table" class="table table-ater" style="width:100%">
                <thead>
                <tr>                    
                    <th>Cultura</th>                    
                    <th>Quantidade</th>
                    <th>Situação produtiva e observações</th>
                    <th width="60">Ações</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

@push('after-scripts')
    <script>
        $(document).ready(function () {
            $('#table').DataTable({
                "dom": '<"top table-top"f>rt<"row table-bottom"<"col-sm-12 col-md-5"il><"col-sm-12 col-md-7"p>><"clear">',
                "processing": true,
                "serverSide": true,
                "lengthChange": false,
                "ajax": '{{ $urlDatatable }}',
                "language": {
                    "url": '{{ asset('js/datatables-pt-br.json')}}'
                },
                "columns": [                    
                    {"data": "produto"},                    
                    {"data": "quantidade"},
                    {"data": "observacao"},                    
                    {
                        "data": "actions",
                        "searchable": false,
                        "orderable": false,
                        render: function (data) {
                            return htmlDecode(data);
                        }
                    }
                ]
            }).on('draw', function () {
                initAutoLink($("#table"));
            });

            addAutoLink(function () {
                debounceSearch('#table');
            });
        });
    </script>
@endpush
