<template>
    <Layout>
        <Head><title>Eduket | Classes</title></Head>
        <page-header current-page="Classes" page="Classes"/>
        <div class="card-box pd-20 height-100-p mb-30 col-md-8 ml-auto mr-auto">
            <h5 class="h5">Class Listing
<!--                <a v-if="classes.total === 0" data-toggle="modal" data-target="#exampleModal" href="" class="btn btn-sm btn-primary pull-right"><i class="fa fa-plus"></i></a>-->
            </h5>
            <pagination :previous-pages="previousPages" :next-pages="nextPages" :data="classes" ></pagination>
            <table  v-if="classes.total>0" class="table table-bordered table-sm">
                <thead>
                <tr>
                    <th>Form</th>
                    <th>Type</th>
                    <th>Shift</th>
                    <th>Students</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="cl in classes.data">
                    <td>{{cl.number}}</td>
                    <td>{{cl.name}}</td>
                    <td>{{cl.shift}}</td>
                    <td>{{cl.students}}</td>
                </tr>
                </tbody>
            </table>
            <empty :category="classes" message="No classes have been set please ensure this is done before students are able to access the system "></empty>
        </div>

    </Layout>

</template>

<script>
export default {
    name: "Classes",
    props: {
        classes:{},
    },
    data() {
        return {
            form:this.$inertia.form({
                shift:'',
                stream:'',
            }),
            samples:[]
        }
    },

    methods: {
        addClass() {
            this.form.reset()
            this.samples = []
        },

        nextPages() {
            if (this.classes.next_page_url === null) {
                return;
            }

            this.$inertia.get(this.classes.next_page_url,{},{preserveState:true,preserveScroll:true})
        },

        previousPages() {
            if (this.classes.prev_page_url === null) {
                return;
            }

            this.$inertia.get(this.classes.prev_page_url,{},{preserveState:true,preserveScroll:true})
        },


    },
}
</script>

<style scoped>

</style>
