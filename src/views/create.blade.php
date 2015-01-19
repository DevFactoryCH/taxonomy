@extends($layout->extends)

@section($layout->header)
  <h1>@lang('taxonomy::taxonomy.create.header')</h1>
@stop

@section($layout->content)

  <div class="row">

    {{ Form::open(array('method' => 'POST', 'route' => array($prefix .'taxonomy.store'), 'id' => 'app-create', 'class' => 'form')) }}

    <div class="col-md-8">

      <div class="box box-primary">

        <div class="box-body">

          <div class="form-group{{ $errors->has('name') ? ' has-error has-feedback' : '' }}">
            {{ Form::label('name', Lang::get('taxonomy::taxonomy.create.label.name'), ['class' => 'control-label']) }}
            {{ Form::text('name', NULL, ['class' => 'form-control']) }}
            {{ $errors->has('name') ? Form::label('error', $errors->first('name'), array('class' => 'control-label')) : '' }}
            {{ $errors->has('name') ? '<span class="glyphicon glyphicon-remove form-control-feedback"></span>' : '' }}
          </div>

          <!-- terms -->
          <div class="form-group{{ $errors->first('terms', ' error') }}">
            {{ Form::label('terms', Lang::get('taxonomy::taxonomy.create.label.terms'), ['class' => 'control-label']) }}
            {{ Form::textarea('terms', NULL, ['class' => 'form-control']) }}
            {{ $errors->has('terms') ? Form::label('error', $errors->first('terms'), array('class' => 'control-label')) : '' }}
            {{ $errors->has('terms') ? '<span class="glyphicon glyphicon-remove form-control-feedback"></span>' : '' }}
          </div>

        </div>

        <div class="box-footer">
          <button type="submit" class="btn btn-flat btn-primary">
            @lang('taxonomy::taxonomy.button.create')
          </button>
        </div>

      </div>
    </div>

    {{ Form::close() }}

  </div>

@stop
