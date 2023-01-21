<!--
    SPINNER
-->
<script>
    import { fade }     from 'svelte/transition';
    import { displayer } from './Spinner+displayer.svelte';

	export let size     = 150;
	export let speed    = 300;

    // svelte needs a direct assigmnent to fire updated state...
    $: {
        const displayerTrigger = () => displayer.visible = displayer.visible;
        displayer.callback(displayerTrigger);
    }
</script>

{#if displayer.visible}
<div id="spinner-preloader" style="--spinner-size: {size}px" class="spinner-screen" in:fade="{{duration:speed}}" out:fade="{{duration:speed}}">
    <div class="spinner-flash"></div>
</div>
{/if}

<style type="text/css">
    @-webkit-keyframes spinner-flash {
        to {
            -webkit-transform:rotate(360deg);
            transform:rotate(360deg)
        }
    }
    @keyframes spinner-flash {
        to {
            -webkit-transform:rotate(360deg);
            transform:rotate(360deg)
        }
    }

    .spinner-screen {
        position:fixed;
        display:block;
        top:0;
        left:0;
        z-index:9998;
        width:100%;
        height:100%;
        background-color: rgba(0, 0, 0, .5)
    }

    .spinner-flash {
        position:relative;
        top:50%;
        left:50%;
        display:inline-block;
        width:var(--spinner-size);
        height:var(--spinner-size);
        vertical-align:middle;
        border: calc(var(--spinner-size)/10) solid currentcolor;
        border-right-color:transparent;
        border-radius:50%;
        -webkit-animation:spinner-flash 1.25s linear infinite;
        animation:spinner-flash 1.25s linear infinite;
        z-index:9999;
    }
</style>