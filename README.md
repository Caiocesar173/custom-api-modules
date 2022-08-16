# Laravel API Modules

This is a Laravel package which created to manage your large Laravel app using modules. The modules in this package are made for API services. 
This package is based uppon, [laravel-modules](https://github.com/nWidart/laravel-modules).


## Install

To install through Composer, by run the following command:

``` bash
composer require caiocesar173/custom-api-modules-laravel
```

The package will automatically register a service provider and alias.

### Autoloading

By default, the module classes are not loaded automatically. You can autoload your modules using `psr-4`. For example:

``` json
{
  "autoload": {
    "psr-4": {
      "App\\": "app/",
      "Modules\\": "Modules/",
      "Database\\Factories\\": "database/factories/",
      "Database\\Seeders\\": "database/seeders/"
  }
}
```

**Tip: don't forget to run `composer dump-autoload` afterwards.**

## Documentation

You'll find installation instructions and full documentation on [https://docs.laravelmodules.com/](https://docs.laravelmodules.com/).
