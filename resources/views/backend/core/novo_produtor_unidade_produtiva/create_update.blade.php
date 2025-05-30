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
                <button type="submit" class="btn btn-primary px-5" form="form-builder">Salvar</button>
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

            let edited_nome_unidade_produtiva = false;
            $("#nome_unidade_produtiva").keyup(function() {edited_nome_unidade_produtiva = true});
            $("#nome_produtor").keyup(function() {
                if(!$("#fl_unidade_produtiva").prop("checked") && !edited_nome_unidade_produtiva) {
                    $("#nome_unidade_produtiva").val($("#nome_produtor").val());
                }
            })

            $("#nome_produtor").change(function() {
                $.ajax({
                    url:base_url+'api/produtor/verificaNome',
                    method:"GET",
                    data:{
                        nome: $(this).val(),
                    }
                }).done((response)=>{
                    $("#nome_produtor ~ .invalid-feedback").remove();
                    $("#nome_produtor").css("border-color", "");
                    $("#nome_produtor").css("box-shadow", "");

                    if (response === 'true') {
                        $("#nome_produtor")[0].insertAdjacentHTML("afterend", '<div class="invalid-feedback" style="display:block"> {{ __('concepts.produtora.name_exists') }} </div>');
                        $("#nome_produtor").css("border-color", "#e55353");
                        //era pra ser só no focus
                        $("#nome_produtor").css("box-shadow", "0 0 0 0.2rem rgb(229 83 83 / 25%)");
                    }
                });
            });

            $("#nome_unidade_produtiva").change(function() {
                $.ajax({
                    url:base_url+'api/unidades_produtivas/verificaNome',
                    method:"GET",
                    data:{
                        nome: $(this).val(),
                    }
                }).done((response)=>{
                    $("#nome_unidade_produtiva ~ .invalid-feedback").remove();
                    $("#nome_unidade_produtiva").css("border-color", "");
                    $("#nome_unidade_produtiva").css("box-shadow", "");

                    if (response === 'true') {
                        $("#nome_unidade_produtiva")[0].insertAdjacentHTML("afterend", '<div class="invalid-feedback" style="display:block"> {{ __('concepts.unidade_produtiva.name_exists') }} </div>');
                        $("#nome_unidade_produtiva").css("border-color", "#e55353");
                        //era pra ser só no focus
                        $("#nome_unidade_produtiva").css("box-shadow", "0 0 0 0.2rem rgb(229 83 83 / 25%)");
                    }
                });
            });

        });
    </script>
@endpush
