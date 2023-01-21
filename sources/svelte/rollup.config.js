import svelte from 'rollup-plugin-svelte'
import resolve from '@rollup/plugin-node-resolve'
import commonjs from '@rollup/plugin-commonjs'
import cssOnly from 'rollup-plugin-css-only'
// import babel from '@rollup/plugin-babel'
// import fontfaceUrl from './plugin/rollup-plugin-fontface-url.js'

import terser from '@rollup/plugin-terser'
import { minify as esbuildMinify } from 'rollup-plugin-esbuild'

// Svelte configuration
import svelteConfig from './svelte.config.js'

import fs from 'fs'
import path from 'path'
// import { sass } from 'svelte-preprocess-sass'
// import scss from 'rollup-plugin-scss'
// import svg from 'rollup-plugin-svg-import'
// import json from '@rollup/plugin-json'

import { fileURLToPath } from 'url'
const __filename = fileURLToPath(new URL(import.meta.url)),
      __dirname = path.dirname(fileURLToPath(new URL(import.meta.url)))
global.__filename = __filename // fix terser __filename
global.__dirname = __dirname

/* minify */
const minifier = {
   name : 'terser', // 'terser' || 'esbuild'
   is   : function (n) { return this.name === n }
},

/* Paths --- */
      WWW = path.join(__dirname, '..', '..', 'www'), // Site root. here : 'www/'
      STYLESHEET = 'css',
      JAVASCRIPT = 'js',
      COMPONENTS = path.join(__dirname, 'components'),

      production = false // !process.env.ROLLUP_WATCH

function plugins (name, index) {
   return [
      // svg({ stringify : true }),
      // json({}),
      /*
      scss({
         output : `css/scss-${name}.css`
      }),
      */

      svelte(
         svelteConfig
      ),

      cssOnly({
         output : path.join(STYLESHEET, `${name}.css`)
         // output : function (css, styles, bundle) {
         //    console.log('css:')
         //    console.log(css)
         //    console.log('styles:')
         //    console.log(styles)
         //    console.log('bundle:')
         //    console.log(bundle)
         // }
      }),

      /*
      fontfaceUrl({
         sourceDir : path.resolve(__dirname, 'stylesheets'),
         destDir   : WWW,
         alias     : {
            './fonts' : path.resolve(__dirname, 'stylesheets', 'fonts')
         }
      }),
      */

      // If you have external dependencies installed from
      // npm, you'll most likely need these plugins. In
      // some cases you'll need additional configuration -
      // consult the documentation for details:
      // https://github.com/rollup/plugins/tree/master/packages/commonjs
      resolve({
         jsnext  : true,
         browser : true,
         dedupe  : ['svelte']
         // alias   : {
         //    './fonts' : path.resolve(__dirname, 'stylesheets', 'fonts')
         // }
      }),

      commonjs({
         sourceMap : false
      }),
/*
      babel({
         exclude : [
            'node_modules/**',
            '*.css'
         ],
         babelHelpers : 'inline'
      }),
*/
      // If we're building for production (npm run build
      // instead of npm run dev), minify
      minifier.is('terser') && production && terser({
         compress : {
            keep_fnames     : true,
            keep_classnames : true
         },
         output : { comments : false }
      }),

      minifier.is('esbuild') && production && esbuildMinify({
         sourceMap : false
      })
   ]
}

function directories (root) {
   return fs.readdirSync(root, { withFileTypes : true }).filter(dirent => dirent.isDirectory()).map(dirent => dirent.name)
}

// const isExternal = id => console.log(id) // !id.startsWith('./fonts/') && !path.isAbsolute(id)

export default directories(COMPONENTS).map((name, index) => ({
   input  : path.join(COMPONENTS, name, 'main.js'),
   output : {
      sourcemap      : false,
      format         : 'iife',
      name           : name,
      dir            : WWW,
      entryFileNames : path.join(JAVASCRIPT, `${name}.js`),
      generatedCode  : {
         preset         : 'es5',
         constBindings  : true,
         arrowFunctions : true
      }
   },
   // external : isExternal,
   plugins : plugins(name, index),
   watch   : { clearScreen : false }
}))