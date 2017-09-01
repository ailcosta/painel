@extends('adminlte::page')

@section('title', 'Lista de Produtos')

@section('content')
    <div class="col-md-12">
    @if (Session::has('message'))
       <div class="callout callout-success">
          <h4>Aviso</h4>

          <p>{{ Session::get('message') }}</p>
        </div>
    @endif
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">OpÃ§Ãµes de busca</h3>
                 <div class="box-tools"></div>
            </div>
            <div class="box-body table-responsive no-padding">
            <a type="button" class="btn btn-block btn-warning" href="http://auth.mercadolivre.com.br/authorization?client_id=6233605376304887&response_type=code&redirect_uri=https://fornecedores.fullhub.com.br/ml/redirect">Entrar</a>

            </div>
        </div>
    </div>
@endsection
