@extends($layout->extends)

@section($layout->header)
  <h1>Taxonomy</h1>
@stop

@section($layout->content)

  <p>
    {{ Form::open(array('method'=>'GET','route' => array('admin.taxonomy.create'))) }}
    {{ Form::submit('Add a vocabulary', array('class'=>'btn btn-primary app-add')) }}
    {{ Form::close() }}
  </p>

  <div class="row">

    <div class="col-sm-12 col-md-6">

      <div class="box">

        <div class="box-header">

          <h3 class="box-title">Vocabularies</h3>

        </div>

        <div class="box-body table-responsive no-padding">

          <table class="table table-hover">

            <tbody>
              <tr>
                <th class="span2">Name</th>
                <th class="span2"></th>
              </tr>
            </tbody>

            <tbody>
              @foreach ($vocabularies as $vocabulary)
                <tr>
                  <td>{{ $vocabulary->name }}</td>
                  <td>
		    {{ Form::open(array('class' => 'pull-right', 'method' => 'DELETE', 'route' => array($prefix . 'taxonomy.destroy', $vocabulary->id))) }}
		    {{ Form::button(Lang::get('button.delete'), array('class'=>'delete-confirm-dialog btn btn-xs btn-danger btn-flat', 'type' => 'submit')) }}
		    {{ Form::close() }}

                    {{ Form::open(array('style' => 'margin-right: 5px', 'class' => 'pull-right', 'method' => 'GET', 'route' => array($prefix . 'taxonomy.edit', $vocabulary->id))) }}
		    {{ Form::button(Lang::get('button.edit'), array('class'=>'btn btn-xs btn-info btn-flat', 'type' => 'submit')) }}
		    {{ Form::close() }}
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
