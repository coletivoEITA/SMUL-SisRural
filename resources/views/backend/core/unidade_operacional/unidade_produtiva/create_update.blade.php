@extends('backend.layouts.app')

@section('content')
    <div class="card-ater">
        <div class="card-body-ater">
            {{-- @include('backend.components.title-form.index', ['title' => $title]) --}}

            {!! form($form) !!}
        </div>

        <div class="card-footer-ater">
            <div class="row">
                <div class="col">
                    {{ form_cancel(route('admin.core.unidade_operacional.unidade_produtiva.index',[$unidadeOperacional->id]), __('buttons.general.cancel'), 'btn btn-danger px-4') }}
                </div>

                <div class="col text-right">
                    <button type="submit" class="btn btn-primary px-5" form="form-builder">Salvar</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('after-scripts')
    <script>
        $(document).ready(function () {
            $('#unidade_produtiva_id').select2();
        });
    </script>
@endpush
