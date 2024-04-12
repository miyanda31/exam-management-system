<template>
    <div class="page-header">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="title">
                    <h4>{{currentPage}}
                        <div class="pull-right">
                            <slot/>
                        </div>
                    </h4>

                </div>
                <nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><Link :href="$route('home')">Home</Link></li>
                        <li v-for="link in links" class="breadcrumb-item active" aria-current="page"><Link :href="$route(link.name)">{{link.title}}</Link></li>
                        <li class="breadcrumb-item active" aria-current="page">{{page}}</li>
                    </ol>

                </nav>

            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "PageHeader",
    props: {
        links:[],
        page:'',
        currentPage:''
    }
}
</script>

<style scoped>

</style>
