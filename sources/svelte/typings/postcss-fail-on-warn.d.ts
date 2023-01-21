declare module 'postcss-fail-on-warn'
{
//export default function postFailOnWarn(): import('postcss').Plugin;
   function pluginCreator(): import('postcss').Plugin;
   namespace pluginCreator {
      export { postcss };
   }
   var postcss: true;
}