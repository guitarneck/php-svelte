<script context="module">
    export const displayer = {
        visible     : true,
        counter     : 0,
        callback    : function ( cb ) {
            this.cb = cb;
        },
        show        : function () {
            this.counter++;
            this.visible = true;
            this.cb();
        },
        hide        : function () {
            if ( ! this.visible ) return;
            if ( --this.counter <= 0 )
            {
                this.counter = 0;
                this.visible = false;
                this.cb();
            }
        }
    };
</script>