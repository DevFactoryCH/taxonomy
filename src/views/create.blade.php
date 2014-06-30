@extends(Config::get('taxonomy::layout_extend'))

{{-- Page content --}}
@section(Config::get('taxonomy::section'))
<div class="page-header">
	<h3>
		Créeer un nouveau vocabulaire adawd

		<div class="pull-right">
			<a href="{{ URL::to('vocabularies') }}" class="btn btn-small btn-inverse"><i class="icon-circle-arrow-left icon-white"></i> Back</a>
		</div>
	</h3>
</div>



<form class="form-vertical" method="post" action="" autocomplete="off">
	<!-- CSRF Token -->
	<input type="hidden" name="_token" value="{{ csrf_token() }}" />

		<!-- Name -->
		<div class="control-group {{ $errors->has('value') ? 'error' : '' }}">
			{{ Form::label('value','Nom') }}
			<div class="controls">
				{{ Form::text('value') }}
				{{ $errors->first('value', '<span class="help-inline">:message</span>') }}
			</div>
		</div>

			<!-- terms -->
		<div class="control-group{{ $errors->first('terms', ' error') }}">
			{{ Form::label('terms', 'termes (separate with ; )') }}
			<div class="controls">
				{{ Form::textarea('terms',null,array('style' => 'width:300px')) }}
			{{ $errors->first('terms', '<span class="help-block">:message</span>') }}
			</div>
		</div>


	<!-- Form actions -->
	<div class="control-group">
		<div class="controls">
			<a class="btn btn-link" href="{{ URL::to(Config::get('taxonomy::section').'/vocabularies') }}">Cancel</a>
			<button type="reset" class="btn">Reset</button>
			<button type="submit" class="btn btn-success">Publish</button>
		</div>
	</div>
</form>

@stop
