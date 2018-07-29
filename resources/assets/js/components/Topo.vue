<template>
    <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
        <div class="container">
            <a class="navbar-brand" v-bind:href="url">
                {{ titulo }}
            </a>
            

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav mr-auto">

                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ml-auto">
                    <!-- Authentication Links -->
                    <slot> </slot>
                </ul>
            </div>
        </div>
    </nav>
</template>

<script>
    export default {
        props:['titulo', 'url']
    }
</script>
