@extends('adminlte::layouts.app')

@section('htmlheader_title')
	{{ trans('Produtos') }}
@endsection

@section('contentheader_title')
	{{ trans('Produtos') }}
@endsection

@section('contentheader_description')
	{{ trans('(Cadastro de Produtos)') }}
@endsection
<!-- to be changed in contentheader.balade.php-->
@section('contentheader_path')
        	{{ "Cadastros"." > "."Produtos" }}
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
									placeholder="nome da empresa" 
									type="text"  
									value="{{old('name')}}">
						</div>
						<span>
							<div class="row">
								<div class="form-group col-xs-8">
									<label for="cnpj">C.N.P.J.</label>
									<input class="form-control" name="cnpj" 
									placeholder="cnpj" 
									type="text" 
									value="{{old('cnpj')}}">
								</div>
								<div class="form-group col-xs-4">
									<label for="alias">Sigla</label>
									<input class="form-control" name="alias"
									placeholder="alias" 
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
									placeholder="type" 
									type="text" 
									value="{{old('type')}}">
								</div>
								<div class="form-group col-xs-4">
									<label for="code">Código</label>
									<input class="form-control" name="code"
									placeholder="code" 
									type="text" 
									value="{{old('code')}}" >
								</div>
							</div>
						</span>
						<div class="form-group">
							<label for="meaning">Significado</label>
							<input class="form-control" name="meaning" 
									placeholder="nome da empresa" 
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

			<!-- Form Element sizes -->
			<div class="box box-success">
				<div class="box-header with-border">
					<h3 class="box-title">Different Height</h3>
				</div>
				<div class="box-body">
					<input class="form-control input-lg" placeholder=".input-lg" type="text">
					<br>
					<input class="form-control" placeholder="Default input" type="text">
					<br>
					<input class="form-control input-sm" placeholder=".input-sm" type="text">
				</div>
				<!-- /.box-body -->
			</div>
			<!-- /.box -->

			<div class="box box-danger">
				<div class="box-header with-border">
					<h3 class="box-title">Different Width</h3>
				</div>
				<div class="box-body">
					<div class="row">
						<div class="col-xs-3">
							<input class="form-control" placeholder=".col-xs-3" type="text">
						</div>
						<div class="col-xs-4">
							<input class="form-control" placeholder=".col-xs-4" type="text">
						</div>
						<div class="col-xs-5">
							<input class="form-control" placeholder=".col-xs-5" type="text">
						</div>
					</div>
				</div>
				<!-- /.box-body -->
			</div>
			<!-- /.box -->

			<!-- Input addon -->
			<div class="box box-info">
				<div class="box-header with-border">
					<h3 class="box-title">Input Addon</h3>
				</div>
				<div class="box-body">
					<div class="input-group">
						<span class="input-group-addon">@</span>
						<input class="form-control" placeholder="Username" type="text">
					</div>
					<br>

					<div class="input-group">
						<input class="form-control" type="text">
						<span class="input-group-addon">.00</span>
					</div>
					<br>

					<div class="input-group">
						<span class="input-group-addon">$</span>
						<input class="form-control" type="text">
						<span class="input-group-addon">.00</span>
					</div>

					<h4>With icons</h4>

					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-envelope"></i></span>
						<input class="form-control" placeholder="Email" type="email">
					</div>
					<br>

					<div class="input-group">
						<input class="form-control" type="text">
						<span class="input-group-addon"><i class="fa fa-check"></i></span>
					</div>
					<br>

					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-dollar"></i></span>
						<input class="form-control" type="text">
						<span class="input-group-addon"><i class="fa fa-ambulance"></i></span>
					</div>

					<h4>With checkbox and radio inputs</h4>

					<div class="row">
						<div class="col-lg-6">
							<div class="input-group">
								<span class="input-group-addon">
									<input type="checkbox">
								</span>
								<input class="form-control" type="text">
							</div>
							<!-- /input-group -->
						</div>
						<!-- /.col-lg-6 -->
						<div class="col-lg-6">
							<div class="input-group">
								<span class="input-group-addon">
									<input type="radio">
								</span>
								<input class="form-control" type="text">
							</div>
							<!-- /input-group -->
						</div>
						<!-- /.col-lg-6 -->
					</div>
					<!-- /.row -->

					<h4>With buttons</h4>

					<p class="margin">Large: <code>.input-group.input-group-lg</code></p>

					<div class="input-group input-group-lg">
						<div class="input-group-btn">
							<button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown">Action
								<span class="fa fa-caret-down"></span></button>
								<ul class="dropdown-menu">
									<li><a href="#">Action</a></li>
									<li><a href="#">Another action</a></li>
									<li><a href="#">Something else here</a></li>
									<li class="divider"></li>
									<li><a href="#">Separated link</a></li>
								</ul>
							</div>
							<!-- /btn-group -->
							<input class="form-control" type="text">
						</div>
						<!-- /input-group -->
						<p class="margin">Normal</p>

						<div class="input-group">
							<div class="input-group-btn">
								<button type="button" class="btn btn-danger">Action</button>
							</div>
							<!-- /btn-group -->
							<input class="form-control" type="text">
						</div>
						<!-- /input-group -->
						<p class="margin">Small <code>.input-group.input-group-sm</code></p>

						<div class="input-group input-group-sm">
							<input class="form-control" type="text">
							<span class="input-group-btn">
								<button type="button" class="btn btn-info btn-flat">Go!</button>
							</span>
						</div>
						<!-- /input-group -->
					</div>
					<!-- /.box-body -->
				</div>
				<!-- /.box -->

			</div>
			<!--/.col (left) -->
			<!-- right column -->
			<div class="col-md-4">
				<!-- Horizontal Form -->
				<div class="box box-info">
					<div class="box-header with-border">
						<h3 class="box-title">Horizontal Form</h3>
					</div>
					<!-- /.box-header -->
					<!-- form start -->
					<form class="form-horizontal">
						<div class="box-body">
							<div class="form-group">
								<label for="inputEmail3" class="col-sm-2 control-label">Email</label>

								<div class="col-sm-10">
									<input class="form-control" id="inputEmail3" placeholder="Email" type="email">
								</div>
							</div>
							<div class="form-group">
								<label for="inputPassword3" class="col-sm-2 control-label">Password</label>

								<div class="col-sm-10">
									<input class="form-control" id="inputPassword3" placeholder="Password" type="password">
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-offset-2 col-sm-10">
									<div class="checkbox">
										<label>
											<input type="checkbox"> Remember me
										</label>
									</div>
								</div>
							</div>
						</div>
						<!-- /.box-body -->
						<div class="box-footer">
							<button type="submit" class="btn btn-default">Cancel</button>
							<button type="submit" class="btn btn-info pull-right">Sign in</button>
						</div>
						<!-- /.box-footer -->
					</form>
				</div>
				<!-- /.box -->
				<!-- general form elements disabled -->
				<div class="box box-warning">
					<div class="box-header with-border">
						<h3 class="box-title">General Elements</h3>
					</div>
					<!-- /.box-header -->
					<div class="box-body">
						<form role="form">
							<!-- text input -->
							<div class="form-group">
								<label>Text</label>
								<input class="form-control" placeholder="Enter ..." type="text">
							</div>
							<div class="form-group">
								<label>Text Disabled</label>
								<input class="form-control" placeholder="Enter ..." disabled="" type="text">
							</div>

							<!-- textarea -->
							<div class="form-group">
								<label>Textarea</label>
								<textarea class="form-control" rows="3" placeholder="Enter ..."></textarea>
							</div>
							<div class="form-group">
								<label>Textarea Disabled</label>
								<textarea class="form-control" rows="3" placeholder="Enter ..." disabled=""></textarea>
							</div>

							<!-- input states -->
							<div class="form-group has-success">
								<label class="control-label" for="inputSuccess"><i class="fa fa-check"></i> Input with success</label>
								<input class="form-control" id="inputSuccess" placeholder="Enter ..." type="text">
								<span class="help-block">Help block with success</span>
							</div>
							<div class="form-group has-warning">
								<label class="control-label" for="inputWarning"><i class="fa fa-bell-o"></i> Input with
									warning</label>
									<input class="form-control" id="inputWarning" placeholder="Enter ..." type="text">
									<span class="help-block">Help block with warning</span>
								</div>
								<div class="form-group has-error">
									<label class="control-label" for="inputError"><i class="fa fa-times-circle-o"></i> Input with
										error</label>
										<input class="form-control" id="inputError" placeholder="Enter ..." type="text">
										<span class="help-block">Help block with error</span>
									</div>

									<!-- checkbox -->
									<div class="form-group">
										<div class="checkbox">
											<label>
												<input type="checkbox">
												Checkbox 1
											</label>
										</div>

										<div class="checkbox">
											<label>
												<input type="checkbox">
												Checkbox 2
											</label>
										</div>

										<div class="checkbox">
											<label>
												<input disabled="" type="checkbox">
												Checkbox disabled
											</label>
										</div>
									</div>

									<!-- radio -->
									<div class="form-group">
										<div class="radio">
											<label>
												<input name="optionsRadios" id="optionsRadios1" value="option1" checked="" type="radio">
												Option one is this and that—be sure to include why it's great
											</label>
										</div>
										<div class="radio">
											<label>
												<input name="optionsRadios" id="optionsRadios2" value="option2" type="radio">
												Option two can be something else and selecting it will deselect option one
											</label>
										</div>
										<div class="radio">
											<label>
												<input name="optionsRadios" id="optionsRadios3" value="option3" disabled="" type="radio">
												Option three is disabled
											</label>
										</div>
									</div>

									<!-- select -->
									<div class="form-group">
										<label>Select</label>
										<select class="form-control">
											<option>option 1</option>
											<option>option 2</option>
											<option>option 3</option>
											<option>option 4</option>
											<option>option 5</option>
										</select>
									</div>
									<div class="form-group">
										<label>Select Disabled</label>
										<select class="form-control" disabled="">
											<option>option 1</option>
											<option>option 2</option>
											<option>option 3</option>
											<option>option 4</option>
											<option>option 5</option>
										</select>
									</div>

									<!-- Select multiple-->
									<div class="form-group">
										<label>Select Multiple</label>
										<select multiple="" class="form-control">
											<option>option 1</option>
											<option>option 2</option>
											<option>option 3</option>
											<option>option 4</option>
											<option>option 5</option>
										</select>
									</div>
									<div class="form-group">
										<label>Select Multiple Disabled</label>
										<select multiple="" class="form-control" disabled="">
											<option>option 1</option>
											<option>option 2</option>
											<option>option 3</option>
											<option>option 4</option>
											<option>option 5</option>
										</select>
									</div>

								</form>
							</div>
							<!-- /.box-body -->
						</div>
						<!-- /.box -->
					</div>
					<!--/.col (right) -->
				</div>
				<!-- /.row -->
			</section>
			@endsection
