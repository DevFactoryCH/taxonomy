@extends($layout->extends)

@section($layout->header)
  <h1>{{ trans('taxonomy.vocabulary.index.header') }}</h1>
@stop

@section($layout->content)

  <div class="row">

    <div class="col-sm-12 col-md-6">

      <div class="box">

        <div class="box-header">

          <h3 class="box-title">
            @lang('taxonomy::vocabulary.vocabularies')
          </h3>

        </div>

        <div class="box-body table-responsive no-padding">

          <table class="table table-hover">

            <tbody>
              <tr>
                <th class="span2">
                  @lang('taxonomy::vocabulary.table.name')
                </th>
                <th class="span2">
                  @lang('taxonomy::vocabulary.table.actions')
                </th>
              </tr>
            </tbody>

            <tbody>
              @foreach ($vocabularies as $vocabulary)
                <tr>
                  <td>{{ $vocabulary->name }}</td>
                  <td class="text-right">

                    <div class="btn-group">
                      {{ Form::open(array('method' => 'GET', 'url' => action('\Devfactory\Taxonomy\Controllers\TaxonomyController@getEdit'), $vocabulary->id)) }}
		      {{ Form::button(Lang::get('taxonomy::vocabulary.index.button.view_terms'), array('class'=>'btn btn-xs btn-primary btn-flat', 'type' => 'submit')) }}
		      {{ Form::close() }}
                    </div>

                  </td>
                </tr>
              @endforeach
            </tbody>

          </table>

        </div>

        <div class="box-footer clearfix">
          {!! $vocabularies->render() !!}
        </div>

      </div>

    </div>
  </div>

@stop
