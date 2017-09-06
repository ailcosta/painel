<!-- Content Header (Page header) -->
<section class="content-header">

    <h1>
        @yield('contentheader_title', 'Não foi definido um contentheader_title')
        <small>@yield('contentheader_description')</small>
    </h1>
    <ol class="breadcrumb">
        <li>
        	<a href="#">
        		<i class="fa fa-dashboard"></i> 
        		{{ trans('message.path') }}
    		</a>
		</li>
		

        <li class="active">
			@yield('contentheader_path')
    	</li>
        <li class="active">
        	{{ trans('message.here') }}
    	</li>
    </ol>

</section>