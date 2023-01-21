import { ESLint } from 'eslint';

(async function main () {
   // 1. Create an instance.
   const eslint = new ESLint(),

   // 2. Lint files.
         results = await eslint.lintFiles(['components/**/*.js', 'libraries/**/*.js']),

   // 3. Format the results.
         formatter = await eslint.loadFormatter('stylish'),
         resultText = formatter.format(results)

   // 4. Output it.
   console.log(resultText)
})().catch((error) => {
   process.exitCode = 1
   console.error(error)
})