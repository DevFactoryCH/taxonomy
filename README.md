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
Taxonomy::deleteVocabulary(1);             // Using ID
Taxonomy::deleteVocabularyByName('Region');  // Using Name
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
$terms = Taxonomy::getTerms('Asia');

// Get term without child
$terms = Taxonomy::getTerms('Asia',true);

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

$car->setTerm($term)

```

**Assign many to many relationship**
```

$car = \Car::findOrFail(1);

$car->addTerm(Taxonomy::getTerm('Region', 'Asia'));
$car->addTerm(Taxonomy::getTerm('Region', 'Europe'));

```