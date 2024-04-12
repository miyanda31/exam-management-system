<template>
    <div class="container mt-15">
        <div class="row">
            <div class="col">
                <div class="float-left">
                    <slot></slot>
                </div>
                <div v-if="data && data.total > 0" class="float-right">
                    Page {{data.current_page}}/{{data.last_page}}
                    <div class="btn-group">
                        <button @click="previousPages" type="button" class="btn btn-default btn-sm"><i class="fa fa-chevron-left"></i></button>
                        <button @click="nextPages" type="button" class="btn btn-default btn-sm"><i class="fa fa-chevron-right"></i></button>
                    </div>
                    <!-- /.btn-group -->
                </div>
            </div>

        </div>
    </div>
</template>

<script>
export default {
    name: "SimplePagination",
    props: {
        data:null,
        previousPages:null,
        nextPages:null,
    }
}
</script>

<style scoped>

</style>
