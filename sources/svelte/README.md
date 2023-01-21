# Svelte - Multi input/output Project

## Goal

The goal is to build independent [Svelte][svelte-site] components, to be used as custom HTML tags, with [PHP][php-site].

## Description

For the **input**, each component reside in a directory, where `main.js` is the entry point, loading the svelte component itself.
> The name of the directory must be the name of the component.

The **output** is then generated, according to the component name, in either `css/` or `js/` directory of the site.

The custom HTML tag name starts with the string `svelte-`. It is not a requirement, but it helps to identify them in a PHP views. \
The component is loaded by the PHP view, from the `js/` site directory. The component load its own named css [see svelte:head][svelte-head], if needed.

***Exemple :***
```html
...
<svelte-counter name="yocounter" min="2" value="<?= $params['yocounter'] ?? '' ?>"></svelte-counter>
...
<script src="js/counter.js" defer></script>
...
```
## Architecture

_An example architecture of the project would be as follow:_

***Input:***
```text
      components/
         admin/
            main.js
            Admin.svelte
         client/
            main.js
            Client.svelte
         pos/
            main.js
            Pos.svelte

      svelte.config.js

      rollup.config.js
      vite.build.js

      package.json
```

***Output:***
```text
      www/
         css/
            admin.css
            client.css
            pos.css
         js/
            admin.js
            client.js
            pos.js
```

## Building

Two way for building the project, using [roolup][rollup-site] or [vite][vite-site] bundlers.
> Both are doing the same and are there only for demonstration.
> _Why ? I first used rollup, then decide to try vite_

_Extract of `package.json`, for building script:_
```json
   ...
    "build:rollup": "rollup -c",
    "build:vite": "node vite.build.js"
    ...
```

Building with `npm`, like so, with [roolup][rollup-site] or [vite][vite-site] :

```shell
$ npm run build:rollup
```
```shell
$ npm run build:vite
```

[svelte-head]: https://svelte.dev/tutorial/svelte-head
[rollup-site]: https://rollupjs.org/
[vite-site]: https://vitejs.dev/
[svelte-site]: https://svelte.dev/
[php-site]: https://www.php.net/