@extends('backend.layouts.app')

@section('title', app_name() . ' | Produtor')

@section('content')
    <h1 class="mb-4">{{ !@$produtor?"Criar Novo Produtor":"Editar Produtor/a: ".$produtor->nome}}</h1>

    @if(@$unidadeProdutiva->id)
        @cardater(['class'=>''])
            @slot('body')
                <div class="row align-items-center">
                    <div class="col col-lg-10">
                        <h4 class="mb-0 text-white2">Complete agora os dados do/a Produtor/a ou pule para próximo passo.</h4>
                    </div>

                    <div class="col col-lg-2 text-right">
                        <a href="{{route('admin.core.novo_produtor_unidade_produtiva.unidade_produtiva_edit', ['produtor'=>$produtor, 'unidadeProdutiva'=>$unidadeProdutiva])}}" class="btn btn-primary px-5" form="form-builder">PULAR</a>
                    </div>
                </div>
            @endslot
        @endcardater
    @endif

    <div class="card-ater">
        <div class="card-body-ater">
            {{-- @include('backend.components.title-form.index', ['title' => $title]) --}}

            <div class="form-produtor">
                {!! form($form) !!}
            </div>

            @if ($produtor)
                <div id="a-unidade-produtiva">
                    @include('backend.components.iframe.html', ["id"=>$containerId, "label"=>"Lista de Unidades Produtivas", "src"=>$containerSrc])
                </div>
            @endif

            @if (@!$produtor)
                @include('backend.components.card-iframe-add.html', ["title"=>"Unidades Produtivas", "data"=>"a-unidade-produtiva", "label"=>"Vincular Unidade Produtiva"])
            @endif
        </div>

        <div class="card-footer-ater">
            <div class="row">
                <div class="col">
                    {{ form_cancel(App\Helpers\General\AppHelper::prevUrl(route('admin.core.produtor.index')), __('buttons.general.cancel'), 'btn btn-danger px-4') }}
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
    @if ($produtor)
        @include('backend.components.iframe.scripts', ["id"=>$containerId, "src"=>$containerSrc])
    @endif

    @include('backend.scripts.estado-cidade-select2');

    <script>
        $(function () {
            selectAutoYesNo("#fl_agricultor_familiar", '#card-agricultor-familiar');
            selectAutoYesNo("#fl_assistencia_tecnica", '#card-assistencia-tecnica');
            selectAutoYesNo("#fl_contrata_mao_de_obra_externa", '#card-mao-de-obra-externa');
            selectAutoYesNo("#fl_portador_deficiencia", '#card-portador-deficiencia');

            selectAutoYesNo("#fl_agricultor_familiar_dap", '.card-agricultor-familiar-dap');
            selectAutoYesNo("#fl_comunidade_tradicional", '#card-comunidade-tradicional');

            selectAutoYesNo("#fl_tipo_parceria", '#card-tipo-parceria');
            selectAutoYesNo("#fl_cnpj", '#card-cnpj');
            selectAutoYesNo("#fl_nota_fiscal_produtor", '#card-nota-fiscal-produtor');

            selectAutoYesNo("#fl_possui_ocupacao_principal", '#card-ocupacao-principal');
               
            if($("input[name='quant_unidade_produtiva']").val() > 0) {
                selectAutoNoYes("#fl_reside_unidade_produtiva", '#card-endereco');
            }

            function submitProdutorForm(action) {
                $('<input>').attr({
                        type: 'hidden',
                        id: 'submit_action',
                        name: 'submit_action',
                        value: action,
                    }).appendTo('#form-builder');
                $("#form-builder").submit();
            }

            function processForm(e) {
                if (e.preventDefault) e.preventDefault();
                submitProdutorForm('edit_after');
            }

            $("#close_after_btn").click(() => submitProdutorForm('close_after'));
            $("#edit_after_btn").click(() => submitProdutorForm('edit_after'));
            $("#form-builder").one("submit", processForm);


            $("#nome").change(function() {
                $.ajax({
                    url:base_url+'api/produtor/verificaNome',
                    method:"GET",
                    data:{
                        nome: $(this).val(),
                    }
                }).done((response)=>{
                    $("#nome ~ .invalid-feedback").remove();
                    $("#nome").css("border-color", "");
                    $("#nome").css("box-shadow", "");

                    if (response === 'true') {
                        $("#nome")[0].insertAdjacentHTML("afterend", '<div class="invalid-feedback" style="display:block"> {{ __('concepts.produtora.name_exists') }} </div>');
                        $("#nome").css("border-color", "#e55353");
                        //era pra ser só no focus
                        $("#nome").css("box-shadow", "0 0 0 0.2rem rgb(229 83 83 / 25%)");
                    }
                });
            });
            
        });
    </script>
@endpush
