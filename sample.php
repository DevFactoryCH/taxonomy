<?php 

use Devfactory\Taxonomy\Models\Vocabulary;
use App\Models\Product;
use Devfactory\Taxonomy\Models\Term;

Route::get('taxonomy',function(){
	die();
	DB::statement("SET foreign_key_checks=0");
	DB::table('products')->truncate();
	DB::table('terms')->truncate();
	DB::table('vocabularies')->truncate();
	DB::table('term_relations')->truncate();
	DB::statement("SET foreign_key_checks=1");

	Taxonomy::createVocabulary('Region');

	$vocabulary = Taxonomy::getVocabulary(1);             

	if( $vocabulary->name === "Region")
		echo "true 1<br>";

	// Using ID
	$vocabulary2 = Taxonomy::getVocabulary('Region');  // Using Name
	
	if( $vocabulary2 == $vocabulary )
		echo "true 2<br>";

	$termAsia = Taxonomy::createTerm('Region', [ 
	        'name' => 'Asia',
	        'description'=>'description',
	    ]);

	$termEurope = Taxonomy::createTerm('Region', [ 
	        'name' => 'Europe',
	        'description'=>'description',
	    ]);

	$termIndonesia = Taxonomy::createTerm('Region', [ 
	        'name' => 'Indonesia',
	        'description'=>'description',
	        'parent_id'=>$termAsia->id
	    ]);

	$termSingapore = Taxonomy::createTerm('Region', [ 
	        'name' => 'Singapore',
	        'description'=>'description',
	        'parent_id'=>$termAsia->id
	    ]);


	$termFrance = Taxonomy::createTerm('Region', [ 
	        'name' => 'France',
	        'description'=>'description',
	        'parent_id'=>$termEurope->id
	    ]);

	$termGermany = Taxonomy::createTerm('Region', [ 
	        'name' => 'Germany',
	        'description'=>'description',
	        'parent_id'=>$termEurope->id
	    ]);

	if( $termAsia->name === 'Asia' && $termAsia->vocabulary_id === $vocabulary->id )
		echo "true 3<br>";

	if( $termEurope->name === 'Europe' && $termEurope->vocabulary_id === $vocabulary->id )
		echo "true 4<br>";

	if( $termIndonesia->name === 'Indonesia' && $termIndonesia->vocabulary_id === $vocabulary->id )
		echo "true 5<br>";

	if( $termSingapore->name === 'Singapore' && $termSingapore->vocabulary_id === $vocabulary->id )
		echo "true 6<br>";

	if( $termFrance->name === 'France' && $termFrance->vocabulary_id === $vocabulary->id )
		echo "true 7<br>";
	
	if( $termGermany->name === 'Germany' && $termGermany->vocabulary_id === $vocabulary->id )
		echo "true 8<br>";

	$terms =  Vocabulary::where('name','Region')->first()->terms;  // Using Name

	if( count($terms) === 6 )
		echo "true 9<br>";

	$termGetAsia = Taxonomy::getTerm('Region', 'Asia');

	if( $termGetAsia->name == $termAsia->name )
		echo "true 10<br>";

	$termsGet = Taxonomy::getTerms('Region');

	if( count($termsGet) == count($terms) )
		echo "true 11<br>";

	$listTermId =  Taxonomy::getTerms('Region')->pluck('id')->toArray();

	if( in_array($termAsia->id, $listTermId) && in_array($termEurope->id, $listTermId) && in_array($termIndonesia->id, $listTermId)  )
		echo "true 12<br>";

	$listTermIdAsia =  Taxonomy::getTerms('Region','Asia')->pluck('id')->toArray();

	if(  in_array($termIndonesia->id, $listTermIdAsia) &&  in_array($termSingapore->id, $listTermIdAsia)  )
		echo "true 13<br>";


	$productOne = Product::create([
					'name'=>'Lalala',
					'price'=>1
					]);

	$productTwo = Product::create([
					'name'=>'Tototo',
					'price'=>2
					]);

	
	$productThree = Product::create([
					'name'=>'Rururu',
					'price'=>2
					]);


	$productOne->setTerm($termIndonesia);

	// reset term
	$productOne->setTerm($termAsia);

	if( @$productOne->hasTerm($termAsia) == true )
		echo "true 14<br>";

	if( @$productOne->hasTerm($termIndonesia) == false )
		echo "true 15<br>";

	if( @$productOne->getTerm('Region')->term->id == $termAsia->id )
		echo "true 16<br>";

	// reset term
	$productOne->removeTerm($termAsia);

	if( @$productOne->hasTerm($termAsia) == false )
		echo "true 17<br>";

	$productOne->addTerm($termAsia);
	$productOne->addTerm($termIndonesia);
	$productOne->addTerm($termSingapore);

	$productTwo->addTerm($termAsia);
	$productTwo->addTerm($termIndonesia);
	$productTwo->addTerm($termSingapore);

	$productThree->addTerm($termAsia);
	$productThree->addTerm($termIndonesia);
	$productThree->addTerm($termSingapore);

	if( count($productOne->getTerms('Region')) == 3 )
		echo "true 18<br>";


	$childOfAsia = Taxonomy::getTerms('Region','Asia')->pluck('id');

	if( count($childOfAsia) == 2 )
		echo "true 19<br>";

	$productsFromTerm = Product::whereHasTerm($childOfAsia)->get();

	if( count($productsFromTerm) == 3 )
		echo "true 20<br>";

	$vocabularyRegion = Taxonomy::getVocabulary('Region');

	$productsFromVocabulary = Product::whereHasVocabulary($vocabularyRegion->id)->get();

	if( count($productsFromVocabulary) == 3 )
		echo "true 21<br>";

});