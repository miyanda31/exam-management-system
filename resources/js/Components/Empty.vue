<template>
    <div v-if="category.total===0" style="padding: 15%; margin-top: 30px;"  class="col-md-12">
            <img style="display: block; margin-left: auto; margin-right: auto;" class="img-responsive" src="/img/searchlist.png" alt="searchlist">
        <p class="text-center">
            {{message}}</p>
    </div>
</template>

<script>
    export default {
        props:['category','message'],
        name: "Empty",

    }
</script>
