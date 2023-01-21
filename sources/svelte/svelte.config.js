// svelte.config.js
import sveltePreprocess from 'svelte-preprocess'
import autoprefixer from 'autoprefixer'
import * as sass from 'sass'

const production = !process.env.ROLLUP_WATCH

export default {
   // Consult https://github.com/sveltejs/svelte-preprocess
   // for more information about preprocessors
   preprocess : sveltePreprocess({

      sourceMap : false,

      postcss : {
         plugins : [
            // require('tailwindcss')(),
            autoprefixer()
         ]
      },

      sass : {
         sync           : true,
         implementation : sass
      },

      compilerOptions : {
         // enable run-time checks when not in production
         dev           : !production,
         css           : true,
         customElement : true
      },

      emitCss : false
   })
}