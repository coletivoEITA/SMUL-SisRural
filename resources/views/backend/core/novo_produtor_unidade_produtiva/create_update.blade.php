@extends('backend.layouts.app')

@section('title', app_name() . ' | Novo Produtor / Unidade Produtiva')

@section('content')
<div class="card-ater">
    <h1 class="mb-4">Novo Produtor / Nova Unidade Produtiva</h1>
    <div class="card-body-ater">
        {!!form_start($form)!!}

        {!!form_until($form, 'lng')!!}

        @include('backend.core.unidade_produtiva.lat_lng.index', ['lat' => @$form->lat->getValue(), 'lng'=> @$form->lng->getValue()])

        {!!form_rest($form)!!}
    </div>

    <div class="card-footer-ater">
        <div class="row">
            <div class="col">
                {{ form_cancel(route('admin.dashboard'), __('buttons.general.cancel'), 'btn btn-danger px-4') }}
            </div>

            <div class="col text-right">
                <button id="close_after_btn" class="btn btn-primary px-5" form="form-builder">Salvar e Fechar</button>
                <button id="edit_after_btn" class="btn btn-primary px-5" form="form-builder">Salvar</button>
            </div>

        </div>
    </div>
</div>
@endsection

@push('after-scripts')
    @include('backend.core.unidade_produtiva.lat_lng.scripts', ['lat' => @$form->lat->getValue(), 'lng'=> @$form->lng->getValue()])

    @include('backend.scripts.estado-cidade-select2')

    <script>
        $(function() {
            $("select[name='unidade_produtiva_id']").select2();

            $("#fl_unidade_produtiva").change(function() {
                $("#card-coordenadas").removeClass('d-none');
                $("#sem-unidade-produtiva").removeClass('d-none');
                $("#com-unidade-produtiva").addClass('d-none');

                $("#estado_id").attr("required", "required");
                $("#cidade_id").attr("required", "required");
                $("#endereco").attr("required", "required");
                $("#unidade_produtiva_id").removeAttr("required");

                if ($(this).prop("checked")) {
                    $("#card-coordenadas").addClass('d-none');
                    $("#sem-unidade-produtiva").addClass('d-none');
                    $("#com-unidade-produtiva").removeClass('d-none');

                    $("#estado_id").removeAttr("required");
                    $("#cidade_id").removeAttr("required");
                    $("#endereco").removeAttr("required");
                    $("#unidade_produtiva_id").attr("required", "required");
                }
            }).change();
            // selectAutoYesNo("#fl_exist_unidade_produtiva", '.card-exist-unidade-produtiva');
            // selectAutoYesNoNone("#fl_exist_unidade_produtiva", '.card-unidade-produtiva');

            function submitProdUPForm(action) {
              if(!$("#fl_unidade_produtiva").prop("checked") && !$("#nome_unidade_produtiva").val()) {
                $("#nome_unidade_produtiva").val($("#nome_produtor").val());
              }
              $('<input>').attr({
                    type: 'hidden',
                    id: 'submit_action',
                    name: 'submit_action',
                    value: action,
                }).appendTo('#form-builder');
              $("#form-builder").submit();
            }

            $("#close_after_btn").click(() => submitProdUPForm('close_after'));
            $("#edit_after_btn").click(() => submitProdUPForm('edit_after'));

        });
    </script>
@endpush
