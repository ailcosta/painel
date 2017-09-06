@extends('adminlte::layouts.app')

@section('htmlheader_title')
  {{ trans('message.generalData') }}
@endsection

@section('contentheader_title')
  {{ trans('message.generalData') }}
@endsection

@section('contentheader_description')
  {{ trans('(Cadastro de Fornecedores, Unidade de Medidas, Marcas e Códigos)') }}
@endsection
<!-- to be changed in contentheader.balade.php-->
@section('contentheader_path')
          {{ "Cadastros"."|"."Cadastros Gerais" }}
@endsection

@section('main-content')

<section class="content">
  <div class="row">

    <div class="col-md-12">
    @if (Session::has('message'))
       <div class="callout callout-success">
          <h4>Aviso</h4>

          <p>{{ Session::get('message') }}</p>
        </div>
    @endif
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">Opções de busca</h3>
                 <div class="box-tools"></div>
            </div>
            <div class="box-body table-responsive no-padding">
            <a type="button" 
              class="btn btn-block btn-warning" 
              href="http://auth.mercadolivre.com.br/authorization?client_id=6233605376304887&response_type=code&redirect_uri=https://painel.villacoisa.com.br/ml/redirect">Entrar</a>

            </div>
        </div>
    </div>
    </div>
</section>
@endsection
