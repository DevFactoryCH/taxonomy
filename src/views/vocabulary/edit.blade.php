@extends($layout->extends)

@section($layout->header)
  <h1>@lang('taxonomy::vocabulary.edit.header'){{ $vocabulary->name }}</h1>
@stop

@section($layout->content)

  <p>
    {{ Form::open(array('method' => 'GET', 'route' => array($prefix . 'terms.create'))) }}
    {{ Form::button(Lang::get('taxonomy::vocabulary.edit.button_add_term'), array('class'=>'btn btn-primary btn-flat', 'type' => 'submit')) }}
    {{ Form::close() }}
  </p>

  <div class="row">

    <div class="col-md-6">

      <div class="box box-primary">

        <div class="box-body table-responsive no-padding">

          <table class="table table-hover">

            <tbody>
              <tr>
                <th class="span2">
                  @lang('taxonomy::vocabulary.table.terms')
                </th>
                <th class="span2">
                  @lang('taxonomy::vocabulary.table.actions')
                </th>
              </tr>
            </tbody>

            <tbody>
              @foreach ($terms as $term)
                <tr>
                  <td>{{ $term->name }}</td>
                  <td class="text-right">

                    <div class="btn-group">
                      {{ Form::open(array('method' => 'GET', 'route' => array($prefix . 'terms.edit', $term->id))) }}
                      {{ Form::button(Lang::get('taxonomy::vocabulary.button.edit'), array('class'=>'btn btn-xs btn-primary btn-flat', 'type' => 'submit')) }}
                      {{ Form::close() }}
                    </div>

                    <div class="btn-group">
                      {{ Form::open(array('method' => 'DELETE', 'route' => array($prefix . 'terms.destroy', $term->id))) }}
                      {{ Form::button(Lang::get('taxonomy::vocabulary.button.delete'), array('class'=>'delete-confirm-dialog btn btn-xs btn-danger btn-flat', 'type' => 'submit')) }}
                      {{ Form::close() }}
                    </div>

                  </td>
                </tr>
              @endforeach
            </tbody>

          </table>

        </div>

      </div>
    </div>

  </div>

@stop
