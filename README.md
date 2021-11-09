Laravel Modules
===================

Modules is a simple package to allow the means to separate your Laravel 5.3 application out into modules. Each module is completely self-contained allowing the ability to simply drop a module in for use.

Quick Installation
------------------
Begin by installing the package through Composer.

```
composer require pierresilva/laravel-modules
```

Once this operation is complete, simply add both the service provider and facade classes to your project's `config/app.php` file:

#### Service Provider

```php
pierresilva\Modules\ModulesServiceProvider::class,
```

#### Facade

```php
'Module' => pierresilva\Modules\Facades\Module::class,
```

#### Module Structure

The package is built with Laravel 5 in mind. Modules follow the same app structure adopted with the latest version of Laravel, ensuring that modules feel like a natural part of your application.

```
laravel-project/
    app/
    |-- Modules/
        |-- Blog/
            |-- Config/
            |-- Database/
                |-- Factories/
                |-- Migrations/
                |-- Seeds/
            |-- Http/
                |-- Controllers/
                |-- Middleware/
                |-- Requests/
            |-- Models/
            |-- Providers/
                |-- ModuleServiceProvider.php
                |-- RouteServiceProvider.php
            |-- Resources/
                |-- Lang/
                |-- Views/
            |-- Routes/
                |-- api.php/
                |-- web.php/
            |-- Tests/
            |-- module.json
```

#### Manifest File

Along with the structure, every module has a module.json manifest file. This manifest file is used to outline information such as the description, version, author(s), and anything else you'd like to store pertaining to the module at hand.

```json
{
    "name": "Blog",
    "slug": "blog",
    "version": "1.0",
    "author": "Author Name",
    "license": "MIT",
    "description": "Only the best blog module in the world!",
    "order": 100
}
```

* **name** - A human-friendly name of the module. Not required.
* **slug** - The slug of the module. This is used for identification purposes.
* **version** - The module's version. Not required.
* **description** - A description of the module. Not required.
* **author** - The module's author name. Not required
* **license** - The module's license. Not required
* **order** - The order of which modules are loaded. This is optional, but if you have a requirement to load a module later this is the option you are looking for. Not required

Configuration
-------------

#### Publishing The Config File

To publish the bundled config file, simply run Laravel's vendor:publish Artisan command:

```
php artisan vendor:publish
```

#### Path to Modules

You may define the path where you'd like to store your modules.
```php
'path' => app_path('Modules'),
```

#### Module's Base Namespace
Define the base namespace for your modules.
```php
'namespace' => 'App\Modules\\'
```
> **Note:** Be sure to update this path if you move your modules to another directory.

#### To move modules folder

* Change configuration above.
* Add auto load namespace match folder in composer.json file. For example: "Modules\\": "modules/". This will be move modules folder to Laravel base path.
* Run command composer dump-autoload in where composer.json installed.

#### Restructure your modules
You can alter the internal structure of your modules without affecting the scaffolding and migration commands. For example, if you want to put your php code in a ``src`` directory and leave out the resources files you could do something like this:
```php
'pathMap' => [
    'Database'          => 'src/Database',
    'Http'              => 'src/Http',
    'Providers'         => 'src/Providers',
    'Models'            => 'src/Models',
    'Policies'          => 'src/Policies',
    'Resources/Views'   => 'resources/views',
    'Resources/Lang'    => 'resources/lang',
    'Routes'            => 'routes',
],
```
> **Notice** that the keys here are just based on the default module structure

Resources
---------
#### Views
Module views are referenced using a double-colon ``module::view`` syntax. So you may load the ``admin`` view from the ``blog`` module like so:

```php
Route::get('admin', function() {
    return view('blog::admin');
});
```
#### Overriding Module Views
Modules registers two locations for your views for each module: one in the application's ``resources/views/vendor`` directory and one in your module's resources directory. So, using our blog example: when requesting a module view, Laravel will first check if a custom version of the view has been provided in ``resources/views/vendor/blog``. Then, if the view has not been customized, Laravel will search the module's view directory. This makes it easy for end-users to customize/override your module's views.
#### Translations
Modules registers the ``Resources/Lang`` location for your translation files within each of your modules. Module translations are referenced using a double-colon ``module::file.line`` syntax. So, you may load the ``blog`` module's ``welcome`` line from the messages file like so:
```php
echo trans('blog::messages.welcome');
```
Public Assets
-------------
Just like packages for Laravel, your modules may have assets such as JavaScript, CSS, and images. To publish these assets to the application's ``public`` directory, use the service provider's ``publishes`` method. You may do this within your module's primary service provider, or create a service provider specifically for assets.

In this example, we'll be storing our assets in an ``Assets`` directory at the root of our module. We will also add a ``modules`` asset group tag, which may be used to publish groups of related assets:

```php
/**
 * Preforms post-registration booting of services.
 *
 * @return void
 */
public function boot()
{
    $this->publishes([
        __DIR__.'/../Assets' => public_path('assets/modules/example'),
    ], 'modules');
}
```

Now, when you execute the ``vendor:publish`` command, your module's assets will be copied to the specified location. Since you typically will need to overwrite the assets every time the module is updated, you may use the ``--force`` flag:

```
php artisan vendor:publish --tag=modules --force
```
If you would like to make sure your public assets are always up-to-date, you can add this command to the ``post-update-cmd`` list in your ``composer.json`` file.

Middleware
----------
Modules comes bundled with middleware that you may use within your application. Below you will find a description of each one with examples of their uses.

The **Identify Module** middleware provides the means to pull and store module manifest information within the session on each page load. This provides the means to identify routes from specific modules.

#### Register
Simply register as a route middleware with a short-hand key in your ``app/Http/Kernel.php`` file.
```php
protected $routeMiddleware = [
    ...
    'module' => \pierresilva\Modules\Middleware\IdentifyModule::class,
];
```
#### Usage
Now, you may simply use the ``middleware`` key in the route options array. The **IdentifyModule** middleware expects the slug of the module to be passed along in order to locate and load the relevant manifest information.
```php
Route::group(['prefix' => 'blog', 'middleware' => ['module:blog']], function() {
    Route::get('/', function() {
        dd(
            'This is the Blog module index page.',
            session()->all()
        );
    });
});
```
#### Result
If you ``dd()`` your session, you'll see that you have a new ``module`` array key with your module's manifest information available.
```html
"This is the Blog module index page."
array:2 [▼
  "_token" => "..."
  "module" => array:6 [▼
    "name" => "Blog"
    "slug" => "blog"
    "version" => "1.0"
    "description" => "This is the description for the Blog module."
    "enabled" => true
    "order" => 9001
  ]
]
```

Composer Support
----------------
#### Installation
To get started, simply require the plugin through Composer for your application:
```
composer require wikimedia/composer-merge-plugin
```
#### Usage
```json
"extra": {
    "merge-plugin": {
        "include": [
            "app/Modules/*/composer.json"
        ]
    }
}
```
Now, for every module that requires their own composer dependencies to be installed with your application, simply create a ``composer.json`` file at the root of your module:
```json
{
    "name": "yourapplication/users",
    "description": "Yourapplication Users module.",
    "keywords": ["yourapplication", "module", "users"],
    "require": {
        "pierresilva/laravel-acl": "~5.3"
    },
    "config": {
        "preferred-install": "dist"
    }
}
```

Then simply run ``composer update`` per normal! Wikimedia's composer merge plugin will automatically parse all of your modules ``composer.json`` files and merge them with your main ``composer.json`` file dynamically.

Facade Reference
----------------
#### Module::all()
Get all modules.
```php
$modules = Module::all();
```
#### Module::all()
Get all module slugs.
```php
$modules = Module::slugs();
```
#### Module::where($key, $value)
Get modules based on where clause.
* $key (string) Module property key. Required.
* $value (mixed) Value to match. Required.
```php
$blogModule = Module::where('slug', 'blog');
```
#### Module::sortBy($key)
Get modules based on where clause.
* $key (string) Module property key. Required.
```php
$orderedModules = Module::sortBy('order');
```
#### Module::sortByDesc($key)
Sort modules by the given key in descending order.
* $key (string) Module property key. Required.
```php
$orderedModules = Module::sortByDesc('order');
```
#### Module::exists($slug)
Check if given module exists.
* $slug (string) Module slug. Required.
```php
if (Module::exists('blog')) {
    return 'Module "blog" exists!';
}
```
#### Module::count()
Returns a count of all modules.
```php
$moduleCount = Module::count();
```
#### Module::getProperties($slug)
Returns the modules defined properties.
* $slug (string) Module slug. Required.
```php
$moduleProperties = Module::getProperties('blog');
```
#### Module::get($property, $default)
   Returns the given module manifest property.
   * $property (string) Module property slug in the following format: moduleSlug::propertyKey. Required.
   * $default (mixed) The default value if the defined property does not exist.
   ```php
   $moduleName = Module::get('blog::name', 'Blog');
   ```
#### Module::set($property, $value)
Set the given module manifest property value.
* $propertySlug (string) Module property slug in the following format: moduleSlug::propertyKey. Required.
* $value (mixed) The new property value to be saved. Required
```php
Module::set('blog::description', 'This is a new description for the blog module.');
```
#### Module::enable($slug)
Enable the specified module.
* $slug (string) Module slug. Required.
```php
Module::enable('blog');
```
#### Module::disable($slug)
Disable the specified module.
* $slug (string) Module slug. Required.
```php
Module::disable('blog');
```
#### Module::enabled()
Gets all enabled modules.
```php
$enabledModules = Module::enabled();
```
#### Module::disabled()
Gets all disabled modules.
```php
$disabledModules = Module::disabled();
```
#### Module::isEnabled($slug)
Checks if specified module is enabled.
* $slug (string) Module slug. Required.
```php
if (Module::isEnabled('blog')) {
    return 'Blog module is enabled!';
}
```
#### Module::isDisabled($slug)
Checks if specified module is disabled.
* $slug (string) Module slug. Required.
```php
if (Module::isDisabled('blog')) {
    return 'Blog module is disabled.';
}
```

Artisan Commands
----------------
Modules package comes with a handful of Artisan commands to make generating and managing modules easy.

#### module:make [slug]
Generate a new module. This will generate all the necessary folders and files needed to bootstrap your new module. The new module will be automatically enabled and work out of the box.
```
php artisan module:make blog
```

#### module:make:controller [slug] [ControllerName]
Generate a new module controller class.
```
php artisan module:make:controller blog PostsController
```

#### module:make:migration [slug] [migration_name]
Generate a new module migration file.
```
php artisan module:make:migration blog create_posts_table
```

#### module:make:seeder [slug] [SeederName]
Generate a new module seeder file.
```
php artisan module:make:seeder blog PostsTableSeeder
```

For migrate entry blog seders create a `[ModuleName]DatabaseSeeder` class into `Database\Seeders` folder module, and include your generate Sedder classes in `run()` method. 

#### module:make:request [slug] [RequestName]
Create a new module form request class.
```
php artisan make:module:request blog CreatePostRequest
```
#### module:make:test [slug] [TestName]
Create a new module test class.
```
php artisan make:module:test blog PostsTest
```
and add in `<testsuites></testsuites>` tag in `phpunit.xml` file the following snippet:
```
<testsuite name="BlogModule">
    <directory suffix="Test.php">./app/Modules/Blog/Tests</directory>
</testsuite>
```

After that the `php artisan test` command can find the module tests classes.

#### module:enable [slug]
Enable a module.
```
php artisan module:enable blog
```
#### module:list
List all application modules.
```
php artisan module:list
```
#### module:migrate [slug]
Migrate the migrations from the specified module or from all modules.

Migrate all modules.
```
php artisan module:migrate
```

Migrate specific module.
```
php artisan module:migrate blog --pretend
```
#### module:migrate:refresh [slug]
Reset and re-run all migrations for a specific or all modules.

**Parameters**
* --database - The database connection to use.
* --seed - Indicates if the seed task should be re-run.
```
php artisan module:migrate:refresh
```
```
php artisan module:migrate:refresh blog --seed
```
#### module:migrate:reset [slug]
Rollback all database migrations for a specific or all modules.

**Parameters**
* --database - The database connection to use.
* --force - Force the operation to run while in production.
* --pretend - Dump the SQL queries that would be run.

Reset all modules migrations.
```
php artisan module:migrate:reset
```
Reset specific module migrations.
```
php artisan module:migrate:reset blog
```
#### module:migrate:rollback [slug]
Rollback the last database migrations for a specific or all modules.

**Parameters**
* --database - The database connection to use.
* --force - Force the operation to run while in production.
* --pretend - Dump the SQL queries that would be run.

Rollback all modules migrations.
```
php artisan module:migrate:rollback
```
Rollback specific module migrations.
```
php artisan module:migrate:rollback blog
```
#### module:seed [slug]
Seed the database with records for a specific or all modules.

**Parameters**
* --class - The class name of the module's root seeder.
* --database - The database connection to seed.

Seed all modules.
```
php artisan module:seed
```
Seed specific module.
```
php artisan module:seed blog
```
## Start building out some awesome modules!


#### Author

[Pierre Silva](https://appscenter.dev)
