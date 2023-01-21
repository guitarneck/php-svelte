import { format } from 'util'

// saving console.warn function
global.warn = console.warn

let badWarn = /(?:.+)referenced in(?:.+)resolve at build time(?:.+)/

console.warn = function () {
   const params = Array.prototype.slice.call(arguments, 1),
         message = params.length ? format(arguments[0], ...params) : arguments[0]

   if (badWarn.test(message)) return

   global.warn.apply(console, arguments)
}

export default function (filters) {
   badWarn = new RegExp(filters.join('|'))
}