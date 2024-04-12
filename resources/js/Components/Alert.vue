<template>
    <div v-if="alert !==''" :class="'alert-'+alert"  class="alert alert-dismissible" role="alert">
        <button type="button" class="close" @click="alert = ''" aria-label="Close"><span aria-hidden="true">×</span></button>
        {{message}}
    </div>
</template>

<script>
export default {
    name: "Alert",
    props:['message','alert']
}
</script>
