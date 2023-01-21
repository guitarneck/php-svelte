export const environment = {
   debug         : /[&?]debug/.test(location.search),
   isDevelopment : dev(),
   isProduction  : !dev()
}

function dev () {
   return /(?:local|127\.0\.0)/.test(location.hostname)
}