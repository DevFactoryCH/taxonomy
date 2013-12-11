<?php namespace Devfactory\Taxonomy;

use Config;
use Input;
use Lang;
use Redirect;
use Sentry;
use Validator;
use View;
use Helpers;


class VocabulariesController extends \Illuminate\Routing\Controllers\Controller {

	protected $vocabulary;
	protected $route_prefix;

	public function __construct(Vocabulary $voc) {

		$this->vocabulary = $voc;
		$this->route_prefix = Config::get('taxonomy::route_prefix');
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()	{

		$vocabularies = $this->vocabulary->paginate(10);
        return View::make('taxonomy::index', compact('vocabularies'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create() {
        return View::make('taxonomy::create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store() {
		
		$validation = Validator::make(Input::All(), Vocabulary::$rules);

		if($validation->passes()) {
			$voc = new Vocabulary();
			$voc->value = Input::get('value');
			$voc->save();

			$terms = preg_split('/[;]/', trim(Input::get('terms')));
			foreach ($terms as $t) {
				if($t != "") {
					$term = new Term();
					$term->value = $t;
					$term->vocabulary_id = $voc->id;
					$term->save();
				}
					
			}


			return Redirect::to($this->route_prefix.'/vocabularies');
		}

		return Redirect::to($this->route_prefix.'/vocabularies/create')
			->withInput()
			->withErrors($validation)
			->with('message', 'There were validation errors.');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id) {
		$id = 2;
		$vocabulary = $this->vocabulary->find($id);
        return View::make('taxonomy::show', compact('vocabulary'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id) {
	    $voc = $this->vocabulary->find($id);

	    if($voc != null) {
	    	$terms = '';
	    	foreach($voc->terms as $term) {
	    		$terms.=$term->value.';';
	    	}

        	return View::make('taxonomy::edit', compact('voc', 'terms'));
	    }
	    return Redirect::to($this->route_prefix.'/vocabularies');
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id) {

		$validation = Validator::make(Input::All(), Vocabulary::$rules);

		if($validation->passes()) {
			$voc = $this->vocabulary->find($id);
			$voc->value = Input::get('value');
			$voc->save();

			$terms = preg_split('/[;]/', trim(Input::get('terms')));
			Term::where('vocabulary_id', $id)->delete();

			foreach ($terms as $t) {
				if($t != "") {
					$term = new Term();
					$term->value = $t;
					$term->vocabulary_id = $voc->id;
					$term->save();
				}
			}


			return Redirect::to($this->route_prefix.'/vocabularies');
		}

		return Redirect::to($this->route_prefix.'/vocabularies/edit', $id)
			->withInput()
			->withErrors($validation)
			->with('message', 'There were validation errors.');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id) {	

		$voc = $this->vocabulary->find($id);
		if($voc != null) {
			$voc->delete();
		}

		return Redirect::to($this->route_prefix.'/vocabularies');
	}

}
