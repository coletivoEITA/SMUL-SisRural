@extends('backend.layouts.app')

@section('title', app_name() . ' | Produtor/a')

@section('content')
    <div class="card card-ater">
        <div class="card-header">
            <div class="row">
                <div class="col-6">
                    <h1 class="card-title mb-0 mt-1 h4">
                        {{ __('concepts.contato.list_of') }}
                    </h1>
                </div>

                @can('create same operational units farmers')
                    <div class="col-6 pull-right">
                        <div class="float-right">
                            <a aria-label="Adicionar novo/a produtor/a"
                                href="{{ route('admin.core.produtor.create') }}"
                                class="btn btn-primary px-5">
                                Adicionar
                            </a>
                        </div>
                    </div>
                @endcan
            </div>
        </div>

        <div class="card-body">
            <table id="table" class="table table-ater">
                <thead>
                    <tr>
                        <th width="60">#</th>
                        <th>Nome</th>
                        <th>CPF</th>
                        <th>Distrito</th>
                        <th>Bairro</th>
                        <th>Telefone</th>
                        <th>Status</th>
                        <!-- <th>Situação Atual</th> -->
                        <!-- th>Próximo Passo</th -->
                        <th width="60">Ações</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

@push('after-scripts')
    @include('backend.core.produtor.datatable', ['datatable_route' => 'admin.core.produtor.datatable_contato'])
@endpush
