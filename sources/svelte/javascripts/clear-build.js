import fs from 'fs/promises'
import path from 'path'
import { fileURLToPath } from 'url'

const testable = false,

      __dirname = path.dirname(fileURLToPath(new URL(import.meta.url))),

      root = path.resolve(path.join(__dirname, '..', '..', '..')),

      www = [
         path.join(root, 'www', 'css'),
         path.join(root, 'www', 'js')
      ]

try {
   for (const dir of www) {
      const files = await fs.readdir(dir)

      if (testable)
         for (const file of files) console.log(path.join(dir, file))
      else {
         await Promise.all(
            files.map(async (file) => {
               return fs.rm(path.join(dir, file))
            })
         )
      }
   }
}
catch (e) {}