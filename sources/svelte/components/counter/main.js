import Counter from './Counter.svelte'

const svelteCounter = document.querySelector('svelte-counter'),

      counter = new Counter({
         target : svelteCounter,
         props  : {
            name  : svelteCounter.getAttribute('name') || 'counter',
            min   : parseInt(svelteCounter.getAttribute('min') || 0),
            max   : parseInt(svelteCounter.getAttribute('max') || 9),
            value : svelteCounter.getAttribute('value') || 0
         }
      })

export default counter