[![Build Status](https://travis-ci.org/DevFactoryCH/taxonomy.svg?branch=master)](https://travis-ci.org/DevFactoryCH/taxonomy)
[![Latest Stable Version](https://poser.pugx.org/devfactory/taxonomy/v/stable.svg)](https://packagist.org/packages/devfactory/taxonomy)
[![Total Downloads](https://poser.pugx.org/devfactory/taxonomy/downloads.svg)](https://packagist.org/packages/devfactory/taxonomy)
[![License](https://poser.pugx.org/devfactory/taxonomy/license.svg)](https://packagist.org/packages/devfactory/taxonomy)

#Taxonomy

This package allows you to create vocabularies with terms in Laravel 4 and 5

## Installation

### Laravel 5

In your `composer.json` add:
m
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

Use Taxonomy base class  
```
use Devfactory\Taxonomy\Models\Term;
use Devfactory\Taxonomy\Models\TermRelation;
use Devfactory\Taxonomy\Models\Vocabulary;
``` 

### Taxonomy
`Taxonomy` is the grouping mechanism between model/object. Each group is of `taxonomy` is called `vocabulary` .The name of different group between one `vocabulary` is called `term`. The `term` can have also have parrent-child relationship. 

This goal of this package is to make create and organizing multiple taxonomy as easy as possible

Sample :

**Region Vocabulary**

List of term : 

- Asia
    - Indonesia
    - Singapore
- Europe
    - France
    - Germany
- North America 
    - Canada
    - United States of America
- Australia
    - Australia
    - New Zealand
- Africa
    - Egypt
    - Marocco


#### Vocabulary 

```php
Taxonomy::createVocabulary('Region');
```

**Retrieving a vocabulary**

```php
$vocabulary = Taxonomy::getVocabulary(1);             // Using ID
$vocabulary = Taxonomy::getVocabularyByName('Region');  // Using Name
```

**Deleting a vocabulary**

```php

$vocabulary->delete(); // Using Eloquent delete
Taxonomy::deleteVocabulary('Region');  // Using Name
```

#### Term

**Adding a term to a vocabulary**
```php
$vocabulary = Taxonomy::getVocabularyByName('Region'); 

$termAsia = Taxonomy::createTerm($vocabulary->id, [ 
        'name' => 'Asia',
        'description'=>'Some description ',
        'parent_id'=>0 , // optional param, set 0 if it has not parrent 
        'weight'=>0, // optional param, the term can be retrieved later and sort by its weight

    ]);

$termIndonesia = Taxonomy::createTerm($vocabulary->id, [ 
        'name' => 'Indonesia',
        'description'=>'Some description',
        'parent_id'=>$termAsia->id, 

    ]);
```

**Retrive single term**
```

// Retrive term `Asia` from Vocabulary `Region`
$term = Taxonomy::getTerm('Region', 'Asia')

// Other method 
$vocabularyRegion = Taxonomy::getVocabularyByName('Region'); 
$term = Taxonomy::getTerm($vocabularyRegion , 'Asia')

```

**Retrive all terms from vocabulary**
```
/* Using Taxonomy Helper*/

// Get term with child
$terms = Taxonomy::getTerms('Region');

// Get all first level terms ( parent_id = 0 )
$terms = Taxonomy::getTerms('Region', false);

// Get terms from Region Vocabulary , child of Asia term
$terms = Taxonomy::getTerms('Region', 'Asia');

/*From vocabulary model itself*/
$vocabularyRegion = Taxonomy::getVocabularyByName('Region'); 

// Get term with child
$terms = $vocabularyRegion->terms();

// Get term without child
$terms = $vocabularyRegion->terms()->where('parent_id',0)->get()

```

#### Working with Model

Make sure your model is already using `\Devfactory\Taxonomy\TaxonomyTrait`

**Assign one to many relationship**
```
$car = \Car::findOrFail(1);

$term = Taxonomy::getTerm('Region', 'Asia');

// term object
$car->setTerm($term)

// term id
$car->setTerm(1)

```

**Assign many to many relationship**
```
$car = \Car::findOrFail(1);

$car->addTerm(Taxonomy::getTerm('Region', 'Asia'));
$car->addTerm(Taxonomy::getTerm('Region', 'Europe'));

// by term id 
$car->addTerm(1);
```

**Check if a model has a term**
```
$car = \Car::findOrFail(1);

$term = Taxonomy::getTerm('Region', 'Asia');

// by term object
$car->hasTerm($term);

// by term id
$car->hasTerm(1);
```

**Get term(s) from model**
```
$car = \Car::findOrFail(1);

// using Vocabulary id
$term = $car->getTerm(1);
$terms = $car->getTerms(1);

// using Vocabulary Name
$term = $car->getTerm('Asia');
$terms = $car->getTerms('Asia');
```

**Remove term from model**
```
$car = \Car::findOrFail(1);

$term = Taxonomy::getTerm('Region', 'Asia');

// Remove using term object
$car->removeTerm($term);

// Remove using term id
$car->removeTerm(1);

$car->removeTerms();
```

**Remove all terms from model**
```
$car = \Car::findOrFail(1);

// REMOVE ALL TERMS FROM ALL VOCABULARY
$car->removeTerms();
```

**Remove all terms from specific vocabulary from model**
```
$car = \Car::findOrFail(1);

// Remove all term with vocabulary id = 1
$car->removeTerms(1);

$vocabularyRegion = Taxonomy::getVocabularyByName('Region'); 

$car->removeTerms($vocabularyRegion);
```

#### Running Query Againts Model

**Get all model which belong to certain vocabulary**
```
$vocabularyRegion = Taxonomy::getVocabularyByName('Region')->list('id');

$cars = Car::whereHasVocabulary($vocabularyRegion)->get();
```

**Get all model which belong to certain term(s)**
```
$terms = Taxonomy::getTerms('Region')->list('id');

$cars = Car::whereHasTerm($terms)->get();

// Only Child of Asia
$terms = Taxonomy::getTerms('Region','Asia')->list('id');
$cars = Car::whereHasTerm($terms)->get();
```