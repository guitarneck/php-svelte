import Loger from './Loger.svelte'

const svelteLoger = document.querySelector('svelte-loger'),

      loger = new Loger({
         target : svelteLoger,
         props  : {
            name : svelteLoger.getAttribute('name') || null
         }
      })

export default loger