import utils from '@rollup/pluginutils'
import fs from 'fs'
import path from 'path'

const fsPromises = fs.promises

function extractCSS (bundle) {
   return Object.keys(bundle).filter(v => /\.css$/.test(v))
}

function hasFontFace (css) {
   return ~css.indexOf('@font-face')
}

function scanURLs (css) {
   if (!hasFontFace(css)) return null

   const regex = /url\((.+\.(?:ttf|otf|woff(?:2)?))\)/g,
         list  = {}

   let m
   while ((m = regex.exec(css)) !== null) {
      // This is necessary to avoid infinite loops with zero-width matches
      if (m.index === regex.lastIndex) regex.lastIndex++
      list[m[1]] = m[0]
   }

   return list
}

const defaultInclude = ['**/*.ttf', '**/*.otf', '**/*.woff(2)?']

function isEmpty (obj) {
   return obj === null || Object.keys(obj).length === 0
}

function copy (src, dest) {
   return new Promise((resolve, reject) => {
      const read = fs.createReadStream(src)
      read.on('error', reject)
      const write = fs.createWriteStream(dest)
      write.on('error', reject)
      write.on('finish', resolve)
      read.pipe(write)
   })
}

function fontfaceUrl (options) {
   const {
      include = defaultInclude,
      exclude
      // alias
   } = options,

         filter = utils.createFilter(include, exclude)

   return {
      name : 'font-face-url',
      async generateBundle (opts, bundle) {
         const keys = extractCSS(bundle)
         for (const k in keys) {
            const id = keys[k],
                  urls = scanURLs(bundle[id].source)

            if (isEmpty(urls)) continue
            // console.log(this)
            console.log(opts)
            /*
            opts.dir: '/d/sources/php-svelte/multi-components/www',
            */

            for (const url of Object.keys(urls)) {
               const fontSource = utils.normalizePath(path.resolve(options.sourceDir, url))
               if (!filter(fontSource)) continue
               // is in comments ???
               console.log((path.isAbsolute(url) ? 'is absolute' : 'not absolute') + ' ' + url)
               /*
               is absolute /fonts/Bainsley_Bold.woff2
               not absolute fonts/Bainsley_Bold_Italic.woff2
               not absolute ./fonts/Bainsley_Italic.woff2
               */

               const fontDest = utils.normalizePath(path.resolve(options.destDir, url))

               fsPromises.open(fontSource, 'r')
                  .then(async (result) => {
                     // console.log(fontSource)
                     // console.log(fontDest)

                     /*
                     await Promise.all(
                        Object.keys(copies).map(async (name) => {
                           const output = copies[name];
                           // Create a nested directory if the fileName pattern contains
                           // a directory structure
                           const outputDirectory = path.join(base, path.dirname(output));
                           await makeDir(outputDirectory);
                           return copy(name, path.join(base, output));
                        })
                     )
                     */

                     await result.close()
                     await fsPromises.mkdir(path.dirname(fontDest), { recursive : true })
                     await copy(fontSource, fontDest)

                     /*
                     const bytes = await result.readFile()

                     this.emitFile({
                        type     : 'asset',
                        fileName : path.basename(fontSource),
                        source   : bytes,
                        output   : fontDest
                     })
                     */
                  })
                  .catch((error) => {
                     this.warn(error)
                  })
/*
===

this.emitFile = (emittedFile)

emittedFile (type='asset'):
        const source = emittedAsset.source === undefined
            ? undefined
            : getValidSource(emittedAsset.source, emittedAsset, null);
        const consumedAsset = {
            fileName: emittedAsset.fileName,
            name: emittedAsset.name,
            source,
            type: 'asset'
        };
        const referenceId = this.assignReferenceId(consumedAsset, emittedAsset.fileName || emittedAsset.name || String(this.nextIdBase++));
        if (this.output) {
            this.emitAssetWithReferenceId(consumedAsset, referenceId, this.output);
        }
        else {
            for (const fileEmitter of this.outputFileEmitters) {
                fileEmitter.emitAssetWithReferenceId(consumedAsset, referenceId, fileEmitter.output);
            }
        }
        return referenceId;
*/
               // const found = false
               // for (const alias of Object.keys(options.alias)) {
               //    const sysf = url.replace(alias, options.alias[alias])
               //    fs.open(sysf, 'r', (err, data) => {
               //       if (!err && data) {
               //          found = true
               //          console.log('found :' + sysf)
               //       }
               //    })
               // }
               // found must be true, or this.warn('')
            }
         }
      }
   }
}

export { fontfaceUrl as default }