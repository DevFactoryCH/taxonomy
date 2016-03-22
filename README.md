[![Build Status](https://travis-ci.org/DevFactoryCH/taxonomy.svg?branch=master)](https://travis-ci.org/DevFactoryCH/taxonomy)
[![Latest Stable Version](https://poser.pugx.org/devfactory/taxonomy/v/stable.svg)](https://packagist.org/packages/devfactory/taxonomy)
[![Total Downloads](https://poser.pugx.org/devfactory/taxonomy/downloads.svg)](https://packagist.org/packages/devfactory/taxonomy)
[![License](https://poser.pugx.org/devfactory/taxonomy/license.svg)](https://packagist.org/packages/devfactory/taxonomy)

#Taxonomy

This package allows you to create vocabularies with terms in Laravel 4 and 5

## Installation

### Laravel 5

In your `composer.json` add:

	"require": {
		"devfactory/taxonomy": "3.0.*"
	}

From the terminal run

    composer update

Then register the service provider and Facade by opening `app/config/app.php`

```php
'Devfactory\Taxonomy\TaxonomyServiceProvider',

'Taxonomy'        => 'Devfactory\Taxonomy\Facades\TaxonomyFacade',
```

Then run the following artisant command to publish the config and migrations:

	php artisan vendor:publish

Then run the migrations:

	php artisan migrate

And finally in any of the Models where you want to use the Taxonomy functionality, add the following trait:

```php
<?php

class Car extends \Eloquent {
  use \Devfactory\Taxonomy\TaxonomyTrait;
}
```

### Laravel 4

In your `composer.json` add:

	"require": {
		"devfactory/taxonomy": "2.0.*"
	}

From the terminal run

    composer update

Then register the service provider and Facade by opening `app/config/app.php`

```php
'Devfactory\Taxonomy\TaxonomyServiceProvider',

'Taxonomy'        => 'Devfactory\Taxonomy\Facades\TaxonomyFacade',
```

If you want you can publish the config files if you want to change them

    php artisan config:publish devfactory/taxonomy

Perform the DB migrations to install the required tables

    php artisan migrate --package=devfactory/taxonomy

And finally in any of the Models where you want to use the Taxonomy functionality, add the following trait:

```php
<?php

class Car extends \Eloquent {
  use \Devfactory\Taxonomy\TaxonomyTrait;
}
```

## Usage

Taxonomy base class  
```
use Devfactory\Taxonomy\Models\Term;
use Devfactory\Taxonomy\Models\TermRelation;
use Devfactory\Taxonomy\Models\Vocabulary;
``` 

Creating a vocabulary:

```php
Taxonomy::createVocabulary('Cars');
```

Retrieving a Vocabulary:

```php
$vocabulary = Taxonomy::getVocabulary(1);             // Using ID
$vocabulary = Taxonomy::getVocabularyByName('Cars');  // Using Name
```

Deleting a Vocabulary:

```php
Taxonomy::deleteVocabulary(1);             // Using ID
Taxonomy::deleteVocabularyByName('Cars');  // Using Name
```

Adding a Term to a vocabulary:

```php
Taxonomy::createTerm($vocabulary->id, 'Audi');
```

You can also optionally specify a parent term and a weight for each, so you can group them together and keep them sorted:

```php
$german_cars = Taxonomy::createTerm($vocabulary->id, 'German Cars');
$italian_cars = Taxonomy::createTerm($vocabulary->id, 'Italian Cars');

// Using parent
$term_audi = Taxonomy::CreateTerm($vocabulary->id, 'Audi', $german_cars->id, 0);
$term_bmw  = Taxonomy::CreateTerm($vocabulary->id, 'BMW', $german_cars->id, 1);
$term_benz = Taxonomy::CreateTerm($vocabulary->id, 'Mercedes-Benz', $german_cars->id, 2);
$term_ferrari = Taxonomy::CreateTerm($vocabulary->id, 'Ferrari', $italian_cars->id, 0);

// Set Description
Taxonomy::CreateTerm($vocabulary->id, 
	[ 
		'name' => 'Toyota',
		'description'=>'Some description',
		'parent'=>$parent_id,
		'weight'=>$weight,

	]);

```

Retrieve all term from vocabulary
```
$terms = Taxonomy::getVocabularyByNameAsArray('Cars');

// Get a Vocabulary by name as an options array for dropdowns
$terms = Taxonomy::getVocabularyByNameOptionsArray('Cars');
```

Retrive term from vocabulary
```
// $term = Taxonomy::getTermByName($vocabulary,$term_name);

$vocabulary = Taxonomy::getVocabularyByName('Cars');
$term = Taxonomy::getTermByName($vocabulary,'German Cars');

// $term = Taxonomy::getTermByName($vocabulary_id,$term_name); 

$term = Taxonomy::getTermByName($vocabulary->id,'German Cars');
```

With the Car Model, I can create a new instance and assign it a term for the make it belongs to:

```php
$car = Car::create([
  'model' => 'A3',
]);

$car->addTerm($term_bmw->id);
$car->addTerm($term_benz->id);
$car->removeAllTerms();              // Remove all terms linked to this car

$car->addTerm($term_ferrari->id);
$car->removeTerm($term_ferrari-id);  // Remove a specific term

$car->addTerm($term_audi->id);

// Get all the terms from the vocabulary 'Cars' That
// are attached to this Car.
$terms = $car->getTermsByVocabularyName('Cars');
```

To retrieve all the cars that match a given term:

```php
$audis = Car::getAllByTermId($term_audi->id)->get();
```
