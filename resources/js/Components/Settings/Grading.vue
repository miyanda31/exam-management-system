<template>
    <Layout>
        <Head><title>Eduket | Grading</title></Head>
        <page-header current-page="Grading System" page="Grading"/>
        <div class="col-xl-12 col-lg-8 col-md-8 col-sm-12 ml-auto mr-auto text-center">
            <div class="row justify-content-center">
                <Link :href="$route('grading.show',data.page)" :key="data.color" v-for="data in info" class="col-lg-3 col-md-4 col-sm-12 mb-30">
                    <div class="card-box pd-30 height-100-p" :class="data.color">
                        <div class="progress-box text-center">
                            <span :class="data.icon" class="dw fa-5x text-white"></span>
                            <h4  class="padding-top-10 h3 text-white ">{{data.title}}</h4>
                        </div>
                    </div>
                </Link>
            </div>

        </div>
    </Layout>
</template>

<script>
export default {
    name: "Grading",
    data() {
        return {
            info: [
                {
                    icon: 'dw-books',
                    title:'JCE',
                    color:'bg-info',
                    page:'jce'
                },
                {
                    icon: 'dw-file',
                    title:'MSCE',
                    color:'bg-success',
                    page:'msce'
                },
            ]
        }
    },
}
</script>

<style scoped>

</style>
