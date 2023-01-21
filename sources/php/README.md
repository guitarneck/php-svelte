# PHP

## Goal

The goal is to use [Svelte][svelte-site] components inside a [PHP][php-site] site.

## Architecture

The routing is covered by [phroute][phroute-packagist], the controllers are in the `bundles/` directory. \
The routes are setted in the `routes/routes.php` script, which generate a cache file `routes.bin`.

| Directory | Description                           |
|-----------|---------------------------------------|
| bundles/  | The controllers of the application    |
| cli/      | The cli scripts                       |
| includes/ | Who knows ?                           |
| routes/   | The controller routes caching process |
| tests/    | Unit and e2e tests                    |
| vendor/   | The composer directory                |

### Not psr-4

The php script files use double extension, except for callable and requestable scripts. For this reason, it is not psr-4.

   - .php : Callable or requestable (cli or web)
   - .controller.php : A controller
   - .class.php : A class
   - .inc.php : An includable file
   - .lib.php : A library file includable (bag of classes, functions, etc.)
   - .view.php : A PHP view

## www, the root site

As usual, `index.php` is the entry point of the site, connecting the routes of the controllers. \
All the subdirectories for the site life remains there _(js/, css/, img/, etc.)_

[svelte-site]: https://svelte.dev/
[php-site]: https://www.php.net/
[phroute-packagist]: https://packagist.org/packages/phroute/phroute