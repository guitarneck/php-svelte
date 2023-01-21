<script>
   import '../../assets/bainsley/bainsley.css'
   // const font = 'bainsley'
   const rnd = parseInt(Math.random() * 1000000000000)
</script>

<svelte:head>
	<link rel="stylesheet" href="css/fonts.css?{ rnd }">
</svelte:head>