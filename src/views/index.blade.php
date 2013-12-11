@extends(Config::get('taxonomy::layout_extend'))


{{-- Page content --}}
@section(Config::get('taxonomy::section'))

<div class="page-header">
	<h3>
		Taxonomies management 2

		<div class="pull-right">
			<a href="{{ URL::to(Config::get('taxonomy::route_prefix').'/vocabularies/create') }}" class="btn btn-small btn-info"><i class="icon-plus-sign icon-white"></i>@lang('button.create')</a>
		</div>
	</h3>
</div>

{{ $vocabularies->links() }}

<table class="table table-bordered table-striped table-hover">
	<thead>
		<tr>
			<th class="span2">Date de création</th>
			<th class="span2" >Titre</th>
			<th class="span2" >Items</th>
			<th class="span2" ></th>
		</tr>
	</thead>
	<tbody>
		@foreach ($vocabularies as $v)
		<tr>
			<td>{{ $v->created_at->format('M jS, Y') }}</td>
			<td>{{ $v->value}}</td>
			<td>@foreach($v->terms as $t)
					[ {{ $t->value}} ]
				@endforeach 
			<td>
				<a href="{{ URL::to(Config::get('taxonomy::route_prefix').'/vocabularies/edit', $v->id) }}" class="btn btn-mini">@lang('button.edit')</a>		
				<a href="{{ URL::to(Config::get('taxonomy::route_prefix').'/vocabularies/delete', $v->id) }}" class="btn btn-mini btn-danger del">@lang('button.delete')</a>
			</td>
		</tr>
		@endforeach
	</tbody>
</table>

{{ $vocabularies->links() }}


		

@stop
