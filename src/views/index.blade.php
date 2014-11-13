@extends(Config::get('taxonomy::config.layout_extend'))

@section(Config::get('taxonomy::section'))

  <p>
    {{ Form::open(array('method'=>'GET','route' => array('admin.taxonomy.create'))) }}
    {{ Form::submit('Add a vocabulary', array('class'=>'btn btn-primary app-add')) }}
    {{ Form::close() }}
  </p>

  <div class="row">

    <div class="col-sm-12">

      <div class="box">

        <div class="box-header">

          <h3 class="box-title">Taxonomy</h3>

        </div>

        <div class="box-body table-responsive no-padding">

          <table class="table table-hover">

            <thead>
              <tr>
                <th class="span2">Name</th>
                <th class="span2"></th>
              </tr>
            </thead>

            <tbody>
              @foreach ($vocabularies as $vocabulary)
                <tr>
                  <td>{{ $vocabulary->name }}</td>
                  <td>
                    <a href="{{ URL::to(Config::get('taxonomy::route_prefix').'/vocabularies/edit', $v->id) }}" class="btn btn-mini">@lang('button.edit')</a>
                    <a href="{{ URL::to(Config::get('taxonomy::route_prefix').'/vocabularies/delete', $v->id) }}" class="btn btn-mini btn-danger del">@lang('button.delete')</a>
                  </td>
                </tr>
              @endforeach
            </tbody>

          </table>

        </div>

        <div class="box-footer clearfix">
          {{ $vocabularies->links() }}
        </div>

      </div>

    </div>
  </div>

@stop
