@extends($layout->extends)

@section($layout->header)
  <h1>@lang('taxonomy::vocabulary.edit.header'){!! $vocabulary->name !!}</h1>
@stop

@section($layout->content)

  <style type="text/css">

   /**
    * Nestable
    */

   .dd { position: relative; display: block; margin: 0; padding: 0; max-width: 600px; list-style: none; font-size: 13px; line-height: 20px; }

   .dd-list { display: block; position: relative; margin: 0; padding: 0; list-style: none; }
   .dd-list .dd-list { padding-left: 30px; }
   .dd-collapsed .dd-list { display: none; }

   .dd-item,
   .dd-empty,
   .dd-placeholder { display: block; position: relative; margin: 0; padding: 0; min-height: 20px; font-size: 13px; line-height: 20px; }

   .dd-handle { display: block; height: 35px; margin: 5px 0; padding: 5px 10px; color: #333; text-decoration: none; font-weight: bold; border: 1px solid #ccc;
                background: #f3f3f3;
                box-sizing: border-box; -moz-box-sizing: border-box;
                }
   .dd-handle:hover { color: #2ea8e5; background: #fff; }

   .dd-item > button { display: block; position: relative; cursor: pointer; float: left; width: 25px; height: 20px; margin: 5px 0; padding: 0; text-indent: 100%; white-space: nowrap; overflow: hidden; border: 0; background: transparent; font-size: 12px; line-height: 1; text-align: center; font-weight: bold; }
   .dd-item > button:before { content: '+'; display: block; position: absolute; width: 100%; text-align: center; text-indent: 0; }
   .dd-item > button[data-action="collapse"]:before { content: '-'; }

   .dd-placeholder,
   .dd-empty { margin: 5px 0; padding: 0; min-height: 30px; background: #f2fbff; border: 1px dashed #b6bcbf; box-sizing: border-box; -moz-box-sizing: border-box; }
   .dd-empty { border: 1px dashed #bbb; min-height: 100px; background-color: #e5e5e5;
               background-image: -webkit-linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff),
               -webkit-linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff);
               background-image:    -moz-linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff),
               -moz-linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff);
               background-image:         linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff),
               linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff);
               background-size: 60px 60px;
               background-position: 0 0, 30px 30px;
               }

   .dd-dragel { position: absolute; pointer-events: none; z-index: 9999; }
   .dd-dragel > .dd-item .dd-handle { margin-top: 0; }
   .dd-dragel .dd-handle {
     -webkit-box-shadow: 2px 4px 6px 0 rgba(0,0,0,.1);
     box-shadow: 2px 4px 6px 0 rgba(0,0,0,.1);
   }

  </style>

  <div class="row">

    <div class="col-md-6">

      <div class="box box-primary">

          <div class="box-header">
              <div class="btn-group">
                  {!! Form::open(array('method' => 'GET', 'url' => action('\Devfactory\Taxonomy\Controllers\TaxonomyController@getIndex'))) !!}
                  {!! Form::button(Lang::get('taxonomy::vocabulary.edit.button.back'), array('class'=>'btn btn-primary btn-flat', 'type' => 'submit')) !!}
                  {!! Form::close() !!}
              </div>
              <div class="btn-group">
                  {!! Form::open(array('method' => 'GET', 'url' => action('\Devfactory\Taxonomy\Controllers\TermsController@getCreate', $vocabulary->id))) !!}
                  {!! Form::button(Lang::get('taxonomy::vocabulary.edit.button.add_term'), array('class'=>'btn btn-success btn-flat', 'type' => 'submit')) !!}
                  {!! Form::close() !!}
              </div>
          </div>

        <div class="box-body">

          <p>
            @lang('taxonomy::vocabulary.table.terms')

            @lang('taxonomy::vocabulary.table.actions')
          </p>

          <div class="dd">
            <ul class="dd-list">
              @foreach ($terms as $term_id => $parent)
                <li class="dd-item" data-id="{!! $parent['term']->id !!}">
                  <!-- drag handle -->
                  <div class="handle dd-handle">
                    <i class="fa fa-ellipsis-v"></i>
                    <i class="fa fa-ellipsis-v"></i>
                    &nbsp;
                    <!-- checkbox -->
                    <!-- todo text -->
                    <span class="text">{!! $parent['term']->name !!}</span>
                    <!-- Emphasis label -->
                    <!-- General tools such as edit or delete-->
                    <div class="pull-right">
                      <div class="btn-group">
                        {!! Form::open(array('method' => 'GET', 'url' => action('\Devfactory\Taxonomy\Controllers\TermsController@getEdit', $parent['term']->id))) !!}
                        {!! Form::button(Lang::get('taxonomy::vocabulary.button.edit'), array('class'=>'btn btn-xs btn-primary btn-flat', 'type' => 'submit')) !!}
                        {!! Form::close() !!}
                      </div>

                      <div class="btn-group">
                        {!! Form::open(array('method' => 'DELETE', 'url' => action('\Devfactory\Taxonomy\Controllers\TermsController@deleteDestroy', $parent['term']->id))) !!}
                        {!! Form::button(Lang::get('taxonomy::vocabulary.button.delete'), array('class'=>'delete-confirm-dialog btn btn-xs btn-danger btn-flat', 'type' => 'submit')) !!}
                        {!! Form::close() !!}
                      </div>
                    </div>
                  </div>

                  <ul class="dd-list">
                    @foreach ($parent['children'] as $term_weight => $child)
                      <li class="dd-item" data-id="{!! $child->id !!}">
                        <!-- drag handle -->
                        <div class="handle dd-handle">
                          <i class="fa fa-ellipsis-v"></i>
                          <i class="fa fa-ellipsis-v"></i>
                          &nbsp;
                          <!-- checkbox -->
                          <!-- todo text -->
                          <span class="text">{!! $child->name !!}</span>
                          <!-- Emphasis label -->
                          <!-- General tools such as edit or delete-->
                          <div class="pull-right">
                            <div class="btn-group">
                              {!! Form::open(array('method' => 'GET', 'url' => action('\Devfactory\Taxonomy\Controllers\TermsController@getEdit', $child->id))) !!}
                              {!! Form::button(Lang::get('taxonomy::vocabulary.button.edit'), array('class'=>'btn btn-xs btn-primary btn-flat', 'type' => 'submit')) !!}
                              {!! Form::close() !!}
                            </div>

                            <div class="btn-group">
                              {!! Form::open(array('method' => 'DELETE', 'url' => action('\Devfactory\Taxonomy\Controllers\TermsController@deleteDestroy', $child->id))) !!}
                              {!! Form::button(Lang::get('taxonomy::vocabulary.button.delete'), array('class'=>'delete-confirm-dialog btn btn-xs btn-danger btn-flat', 'type' => 'submit')) !!}
                              {!! Form::close() !!}
                            </div>
                          </div>
                        </div>
                      </li>
                    @endforeach
                  </ul>
                </li>
              @endforeach
            </ul>
          </div>

        </div>

      </div>
    </div>

  </div>

@stop

@section('js')

  <script>
   $(function() {
     $('.dd').nestable({
       listNodeName: 'ul',
       expandBtnHTML: '',
       collapseBtnHTML: '',
       maxDepth:2,
     });

     $('.dd').on('change', function() {
       var json = JSON.stringify($(this).nestable('serialize'));
       $.ajax({
         url: '{!! action('\Devfactory\Taxonomy\Controllers\TaxonomyController@postOrderTerms', $vocabulary->id) !!}',
         type: 'post',
         data: {
           json
         },
         headers: {
           'X-CSRF-Token': '{!! csrf_token() !!}',
         },
         dataType: 'json'
       });
     });

     $('.dd-handle button').on('mousedown', function(e){
       e.stopPropagation();
     });
   });
  </script>

  {!! HTML::script('vendor/taxonomy/js/jquery.nestable.js') !!}

@stop
