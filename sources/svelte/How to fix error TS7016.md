# How to fix error TS7016

When TypeScript is used by you or a module, you might have run into this error :

```text
Could not find a declaration file for module 'XXX'. '/path/to/node_modules/XXX/index.js' implicitly has an 'any' type.
Try `npm i --save-dev @types/XXX` if it exists or add a new declaration (.d.ts) file containing `declare module 'XXX';`ts(7016)
```

## XXX is a direct dependency of the project ?

Follow the instructions from the error message and install the @types/XXX.

```shell
$ npm i --save-dev @types/XXX
```

## Types not available, declare the module

Sometimes the types might not be available and the npm install command fails. In that case, declare the module.


   1. Create a folder called typings
   2. Create a file in that folder called `XXX.d.ts`
   3. Declare the module(s) like this:
      `declare module 'XXX';`

Lastly, you also need to add the path to your `XXX.d.ts` in the `tsconfig.json` file under the typeRoots element, like this:

```json
{
   "compilerOptions" :
   {
      "typeRoots" : [
         "./typings",
         "./node_modules/@types/"
      ]
   }
}
```

### For all the modules

if you don't care about the typings of external libraries and want all libraries without typings to be imported as any :

   1. Create a file in that folder called `index.d.ts`

```javascript
   declare module '*';
```

> The benefit (and downside) of this is that you can import absolutely anything and TS will compile.

## For any reason, Error still here

Add the following statement to the `tsconfig.json` file to disable the ‘any’ type errors:

```json
   "noImplicitAny": false
```

> Note that disabling this will disable the rule for your project as well.