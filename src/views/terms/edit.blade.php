@extends($layout->extends)

@section($layout->header)
  <h1>@lang('taxonomy::terms.edit.header')</h1>
@stop

@section($layout->content)

  <div class="row">

    {{ Form::open(array('method' => 'PUT', 'route' => array($prefix .'terms.update', $term->id), 'id' => 'app-update', 'class' => 'form')) }}

    <div class="col-md-6">

      <div class="box box-primary">

        <div class="box-body">

          <div class="form-group{{ $errors->has('name') ? ' has-error has-feedback' : '' }}">
            {{ Form::label('name', Lang::get('taxonomy::terms.edit.label.name'), ['class' => 'control-label']) }}
            {{ Form::text('name', $term->name, ['class' => 'form-control']) }}
            {{ $errors->has('name') ? Form::label('error', $errors->first('name'), array('class' => 'control-label')) : '' }}
            {{ $errors->has('name') ? '<span class="glyphicon glyphicon-remove form-control-feedback"></span>' : '' }}
          </div>

        </div>

        <div class="box-footer">
          <button type="submit" class="btn btn-flat btn-primary">
            @lang('taxonomy::taxonomy.button.save')
          </button>
        </div>

      </div>
    </div>

    {{ Form::close() }}

  </div>

@stop
