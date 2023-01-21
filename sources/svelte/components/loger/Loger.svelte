<script>
   import { onMount } from 'svelte';

   export let name;

   onMount(() => {
      console.log('We are in !')
   })
</script>

<section id="login">
{#if name }
   <i>Vous êtes connecté en tant que <b>{ name }</b></i> <a href="/logout">logout</a>
{:else}
   <form action="/login" method="post">
      <input type="text" name="login" autocapitalize="off" autocorrect="off" autocomplete="off" spellcheck="false" />
      <input type="password" name="passwd" autocapitalize="off" autocorrect="off" autocomplete="off" spellcheck="false" />
      <input type="submit" />
   </form>
{/if}
</section>

<svelte:head>
	<link rel="stylesheet" href="css/loger.css">
</svelte:head>

<style lang="scss">
   #login {
      > a {
         text-decoration: none;
         :hover {
            text-decoration: underline;
         }
         color:#66d;
      }
      > form {
         display: inline-block;
         > input {
            background-color: #eee;
            color:#66d;
            &[type="submit"] {
               border:.01em solid #888;
               background-color: #66d;
               color:#eee;
               font-weight: bold;
            }
         }
      }
   }
</style>