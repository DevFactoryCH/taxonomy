Taxonomy
=======================

This package allows you to create multiple vocabularies with a heirarchy of terms inside, that can then be used to attack to various Models.

Installation
=========

Using Composer, edit your `composer.json` file to require `devfactory/taxonomy`.

	"require-dev": {
		"devfactory/taxonomy": "dev-master"
	}

Then from the terminal run

    composer update

Then register the  service provider by opening `app/config/app.php`

    'DevFactory\Taxonomy\TaxonomyServiceProvider'

If you want you can publish the config files if you want to change them

    php artisan config:publish devfactory/taxonomy

Perform the DB migrations to install the required tables

    php artisan migrate --package=devfactory/taxonomy

And finally in any of the Models where you want to use the Taxonomy functionality, add the following trait:

    use \DevFactory\Taxonomy\TaxonomyTrait;
