# PHP + Svelte + Phroute

<img src="www/img/logo-php.svg" width="140px" /> <img src="www/img/logo-svelte.svg" width="300px" />

---

## What's in ?

A simple, basic and quick project to test and embed [svelte][svelte-site] components inside a [php][php-site] site. \
Also, using [phroute][phroute-packagist] and caching for the controllers.

Main versions used so far :

   - PHP 7.4.32 <img src="www/img/logo-php-light.svg" height=12/>
      - Phroute 2.2
   - NPM 8.8.0 <img src="www/img/logo-npm.svg" height=12/>
   - Node v16.15.0 <img src="www/img/icon-nodejs.svg" height=12/>
      - Svelte 3.55 <img src="www/img/icon-svelte.svg" height=12/>
      - ESLint 8.31 <img src="www/img/icon-eslint.svg" height=12/>
      - Font Awesome 6 <img src="www/img/icon-fontawesome.svg" height=12/>
      - Tailwindcss 3.2.4 <img src="www/img/icon-tailwindcss.svg" height=12/>
      - SASS 1.57 <img src="www/img/logo-sass.svg" height=12/>
      - Rollup 3.7.5 <img src="www/img/icon-rollup.svg" height=12/>
      - Vite 4.0 <img src="www/img/icon-vitejs.svg" height=12 />

**No SvelteKit !**

## The goal

The goal is to use Svelte for the front, and keep PHP for the back. Svelte has to be multi-components, that could be use in PHP views.

## Building stages

There is two main building stages for this :

   1. Building the site routes ([phroute][phroute-packagist] caching)
   2. Building the Svelte components (with [rollup][rollup-site] or [vite][vite-site])

### Benchmark rollup & vite

```shell
$ /usr/bin/time --verbose --output=./benchmark-vite.time.txt make build-vite
$ /usr/bin/time --verbose --output=./benchmark-rollup.time.txt make build-rollup
```

## Makefile

Some headache with make, as the project was done under wsl. That's why winshell is hard coded. Not a big deal to change for other os. \
Got some troubles with `/bin/sh` and aliases to windows system, while everything works fine in command line...

### server

The server runs the PHP internal server. **Only for testing usage !!!** \
It means also there is not security at all on the server architecture. `www/` directory should be the only document root of the site.

## TODO

   - Implementing tailwind
   - Implementing svelte/store
   - Common js and css extracting

[svelte-site]: https://svelte.dev/
[php-site]: https://www.php.net/
[phroute-packagist]: https://packagist.org/packages/phroute/phroute

[rollup-site]: https://rollupjs.org/
[vite-site]: https://vitejs.dev/

[php-icon]: www/img/php.png
[svelte-icon]: www/img/svelte.png