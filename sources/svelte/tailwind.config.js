const production = !process.env.ROLLUP_WATCH

/*
module.exports = {
  theme: {
    extend: {}
  },
  variants: {},
  plugins: []
}
*/

export const content = ['./src/**/*.{html,js,svelte}']
export const future = {
   purgeLayersByDefault         : true,
   removeDeprecatedGapUtilities : true
}
export const theme = {
   extend : {}
}
export const plugins = []
export const enabled = production // disable purge in dev