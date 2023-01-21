import Spinner from './Spinner.svelte'

const svelteSpinner = document.querySelector('svelte-spinner'),

      spinner = new Spinner({
         target : svelteSpinner,
         props  : {
            size  : parseInt(svelteSpinner.getAttribute('size') || 150),
            speed : parseInt(svelteSpinner.getAttribute('speed') || 300)
         }
      })

export default spinner