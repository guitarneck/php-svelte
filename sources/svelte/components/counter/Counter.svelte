<script>
   // import { request } from './lib/request.js';
   // import Login from './components/login.svelte';
	export let name = 'counter';
	export let min  = 0;
   export let max  = 9;
	export let value= 0;

	import Fa from 'svelte-fa'
	import { faFlag } from '@fortawesome/free-solid-svg-icons'
	import { faGithub, faHtml5 } from '@fortawesome/free-brands-svg-icons';

   // const req   = request();
   // const q     = req.get('q');
   // const coco  = document.cookie;
</script>

<!-- <Login /> -->

<p>Svelte component in <b>PHP</b> !</p>
<input type="number" {name} {min} {max} {value}/>

<Fa icon={faFlag} />
<Fa icon={faGithub} />
<Fa icon={faHtml5} />

<svelte:head>
	<link rel="stylesheet" href="css/counter.css">
</svelte:head>

<style lang="scss">
	p {
		color:blue;
		> b {
			font-weight: bold;
			color: #000;
		}
	}
	@media (min-width: 640px) {
		input {
			max-width: none;
		}
	}
</style>