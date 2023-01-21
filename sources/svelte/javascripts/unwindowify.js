// Function to turn window path separator into linux like.
function unwindowify (v) { return v.replace(/\\/g, '/') }

module.exports = unwindowify