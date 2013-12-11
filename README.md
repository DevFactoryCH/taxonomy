taxonomy-laravel-bundle
=======================

"devfactory/taxonomy": "dev-master"
composer update

-----------------------

Register :
in config/app.php providers

'Devfactory\Taxonomy\TaxonomyServiceProvider'


Migrate tables :
----------------

php artisan migrate --package=devfactory/taxonomy


Extends the models (optionnal)
------------------------------

use Devfactory\Taxonomy\Term as taxoTermModel;

class User extends taxoTermModel { }