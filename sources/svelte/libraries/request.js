const url = new URL(window.location.href)

/**
 * Get the request parameters.
 * @returns {URLSearchParams} The request parameters.
 */
function request () {
   return url.searchParams
}

export {
   url,
   request
}