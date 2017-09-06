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
		<!-- left column -->
		<div class="col-md-8">
			<!-- general form elements FORNECEDORES-->
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title">Cadastro de Fornecedores</h3>
				</div>
				<!-- /.box-header -->
				<!-- form start -->
				<form role="form" method="POST" action="/data/general/supplier">
					{{ csrf_field() }}
					<div class="box-body">
						<div class="form-group">
							<label for="name">Razão Social</label>
							<input class="form-control" name="name" 
									placeholder="Bee2b Inovações Digitais." 
									type="text"  
									value="{{old('name')}}">
						</div>
						<span>
							<div class="row">
								<div class="form-group col-xs-8">
									<label for="cnpj">C.N.P.J.</label>
									<input class="form-control" name="cnpj" 
									placeholder="27.570.139/0001-27" 
									type="text" 
									value="{{old('cnpj')}}">
								</div>
								<div class="form-group col-xs-4">
									<label for="alias">Sigla</label>
									<input class="form-control" name="alias"
									placeholder="bee2b" 
									type="text" 
									value="{{old('alias')}}" >
								</div>
							</div>
						</span>
					</div>
					<!-- /.box-body -->
					@include('layouts.errors')
					<div class="box-footer">
						<button type="submit" class="btn btn-primary">Submit</button>
					</div>
				</form>
			</div>
			<!-- /.box -->

			<!-- general form elements -->
			<div class="box box-success">
				<div class="box-header with-border">
					<h3 class="box-title">Cadastro de Valores</h3>
				</div>
				<!-- /.box-header -->
				<!-- form start -->
				<form role="form" method="POST" action="/data/general/lookup">
					{{ csrf_field() }}
					<div class="box-body">
						<span>
							<div class="row">
								<div class="form-group col-xs-8">
									<label for="type">Tipo</label>
									<input class="form-control" name="type" 
									placeholder="prod_type_code" 
									type="text" 
									value="{{old('type')}}">
								</div>
								<div class="form-group col-xs-4">
									<label for="code">Código</label>
									<input class="form-control" name="code"
									placeholder="Kit" 
									type="text" 
									value="{{old('code')}}" >
								</div>
							</div>
						</span>
						<div class="form-group">
							<label for="meaning">Significado</label>
							<input class="form-control" name="meaning" 
									placeholder="Produto pertence a um kit" 
									type="text"  
									value="{{old('meaning')}}">
						</div>
					</div>
					<!-- /.box-body -->
					@include('layouts.errors')
					<div class="box-footer">
						<button type="submit" class="btn btn-primary">Submit</button>
					</div>
				</form>
			</div>
			<!-- /.box -->

			

		</div>
		<!--/.col (left) -->
		<!-- right column -->
		<div class="col-md-4">
			<!-- Horizontal Form -->
			<div class="box box-info">

				<div class="box-header with-border">
					<h3 class="box-title">Unidade de Medidas</h3>
				</div>
				<!-- /.box-header -->
				<!-- form start -->
				<form role="form" method="POST" action="/data/general/uom">
					{{ csrf_field() }}
					<div class="box-body">
						<span>
							<div class="row">
								<div class="form-group col-xs-8">
									<label for="type">Classe</label>
									<input class="form-control" name="class" 
									placeholder="comprimento" 
									type="text" 
									value="{{old('class')}}">
								</div>
								<div class="form-group col-xs-4">
									<label for="code">Código</label>
									<input class="form-control" name="code"
									placeholder="cm" 
									type="text" 
									value="{{old('code')}}" >
								</div>
							</div>
						</span>
						<div class="form-group">
							<label for="meaning">Nome</label>
							<input class="form-control" name="name" 
									placeholder="centimetro" 
									type="text"  
									value="{{old('name')}}">
						</div>
					</div>
					<!-- /.box-body -->
					@include('layouts.errors')
					<div class="box-footer">
						<button type="submit" class="btn btn-primary">Submit</button>
					</div>
				</form>

			</div>
			<!-- /.box -->
			<!-- general form elements disabled -->
			<div class="box box-warning">
				<div class="box-header with-border">
					<h3 class="box-title">Marcas</h3>
				</div>
				<!-- /.box-header -->
				<!-- form start -->
				<form role="form" method="POST" action="/data/general/brand">
					{{ csrf_field() }}
					<div class="box-body">
						<div class="form-group">
							<label for="meaning">Nome</label>
							<input class="form-control" name="name" 
									placeholder="Villa Coisa" 
									type="text"  
									value="{{old('name')}}">
						</div>
						<div class="form-group">
							<label for="meaning">Referência</label>
							<input class="form-control" name="reference" 
									placeholder="fabricação própria" 
									type="text"  
									value="{{old('reference')}}">
						</div>
					</div>
					<!-- /.box-body -->
					@include('layouts.errors')
					<div class="box-footer">
						<button type="submit" class="btn btn-primary">Submit</button>
					</div>
				</form>
			</div>
			<!-- /.box -->
		</div>
		<!--/.col (right) -->
	</div>
	<!-- /.row -->
</section>
@endsection
