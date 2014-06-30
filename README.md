taxonomy-laravel-bundle
=======================

"devfactory/taxonomy": "dev-master"
composer update

-----------------------

Register :
in config/app.php providers

'Devfactory\Taxonomy\TaxonomyServiceProvider'

publish config
--------------
php artisan config:publish devfactory/taxonomy


Migrate tables :
----------------

php artisan migrate --package=devfactory/taxonomy


Extends the models (optionnal)
------------------------------

use Devfactory\Taxonomy\Term as taxoTermModel;
class Term extends taxoTermModel { }

use Devfactory\Taxonomy\TermRelation as taxoTermRelationModel;
class TermRelation extends taxoTermRelationModel { }

use Devfactory\Taxonomy\TermRelation as taxoVocabularyModel;
class Vocabulary extends V { }