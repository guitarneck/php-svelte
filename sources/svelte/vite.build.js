// vite.build.js

import { build } from 'vite'
// import { viteStaticCopy } from 'vite-plugin-static-copy' // npm i -D vite-plugin-static-copy
import { svelte as vitesvelte } from '@sveltejs/vite-plugin-svelte'

import cssOnly from 'rollup-plugin-css-only'
import resolve from '@rollup/plugin-node-resolve'
// import commonjs from '@rollup/plugin-commonjs'

import fs from 'fs'
import path from 'path'

// Svelte configuration
import svelteConfig from './svelte.config.js'

// unwarn
import unwarn from './javascripts/unwarn.js'

// __filename & __dirname:
import { fileURLToPath } from 'url'

const __filename = fileURLToPath(new URL(import.meta.url)),
      __dirname = path.dirname(fileURLToPath(new URL(import.meta.url)))
global.__filename = __filename // fix terser __filename
global.__dirname = __dirname

unwarn([
   '(?:.+)referenced in(?:.+)resolve at build time(?:.+)'
])

// The project paths:
const WWW = path.join(__dirname, '..', '..', 'www'), // Site root. here : 'www/'
      STYLESHEET = 'css',
      JAVASCRIPT = 'js',
      COMPONENTS = path.join(__dirname, 'components'),

// The choosen minifier:
      minifier = {
         name : false, // 'terser' || 'esbuild' || false
         is   : function (n) { return this.name === n }
      }

function directories (root) {
   return fs.readdirSync(root, { withFileTypes : true }).filter(dirent => dirent.isDirectory()).map(dirent => dirent.name)
}

// const webExternal = (id) => !id.startsWith('/fonts/') && !path.isAbsolute(id)

directories(COMPONENTS).forEach(async (name) => {
   const buildOptions = {
      sourcemap   : false,
      // appType: 'custom', // Pas besoin, puisque 'lib' est initialisé
      outDir      : WWW,
      emptyOutDir : false,

      // cssCodeSplit:false,

      lib :
      {
         entry    : path.join(COMPONENTS, name, 'main.js'),
         name,
         formats  : ['iife'],
         fileName : path.join(JAVASCRIPT, `${name}.js`)
      },

      rollupOptions : {
         /*
         // make sure to externalize deps that shouldn't be bundled
         // into your library
         external: ['vue'],
         output: {
            // Provide global variables to use in the UMD build
            // for externalized deps
            globals: {
               vue: 'Vue',
            },
         },
         */
         // external : webExternal,
         output : {
            // sourcemap      : false,
            format         : 'iife',
            // name           : name,
            // dir            : WWW,
            entryFileNames : path.join(JAVASCRIPT, `${name}.js`),
            generatedCode  : {
               preset         : 'es5',
               constBindings  : true,
               arrowFunctions : true
            }
         }
      },

      minify : minifier.name
   }

   if (minifier.is('terser')) {
      buildOptions.terserOptions = {
         compress : {
            keep_fnames     : true,
            keep_classnames : true
         },
         output : { comments : false }
      }
   }

   await build({
      configFile : false,
      logLevel   : 'info',
      /*
      # publicDir
         Type: string | false
         Default: "public"
         Directory to serve as plain static assets. Files in this directory are served at / during dev and copied to the root of outDir during build, and are always served or copied as-is without transform. The value can be either an absolute file system path or a path relative to project root.

         Defining publicDir as false disables this feature.

         ---

         Assets in this directory will be served at root path / during dev, and copied to the root of the dist directory as-is.

         The directory defaults to <root>/public, but can be configured via the publicDir option.

         Note that:

         You should always reference public assets using root absolute path - for example, public/icon.png should be referenced in source code as /icon.png.
         Assets in public cannot be imported from JavaScript.
      */
      publicDir  : false, //
      plugins    : [
         vitesvelte(svelteConfig),
         cssOnly({
            output : path.join(STYLESHEET, `${name}.css`)
         }),
         resolve({
            jsnext  : true,
            browser : true,
            dedupe  : ['svelte']
         })
         /*,
         viteStaticCopy({
            targets : [
               {
                     src: 'node_modules/chart.js/dist/chart.js',
                     dest: 'public/js/chart.js'
               },
               ...
            ]
         */
      ],
      build : buildOptions
   })
})