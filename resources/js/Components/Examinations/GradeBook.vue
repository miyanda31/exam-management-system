<template>
    <Layout>
        <Head><title>Eduket | Grade</title></Head>
        <page-header current-page="Grade Book" page="Grades">
            <label>
                <select @change="loadCategory" v-model="form" class="form-control input-sm ml-2 mr-2">
                    <option disabled value="">Class</option>
                    <option v-for="c in classes" :value="c.id">{{c.number}}{{c.name}}</option>
                </select>
            </label>
        </page-header>
        <div class="pd-20 height-100-p mb-30">
            <div class="col-xl-10 col-lg-10 col-md-8 col-sm-12 mr-auto ml-auto">
                <div class="row">
                    <Link :href="$route('gradebook.show',[form.id,{t:term.id}])" :key="form.id" v-for="form in forms" class="col-lg-3 col-md-6 col-sm-12 mb-30">
                        <div class="card-box pd-30 height-100-p bg-info" >
                            <div class="progress-box text-center">
                                <span  class="dw dw-file-22 fa-5x text-white "></span>
                                <h4  class="padding-top-10 h3 text-white ">{{form.number}}{{form.name}}</h4>
                            </div>
                        </div>
                    </Link>
                </div>

            </div>
            <div v-if="this.forms.length === 0" class="col-xl-10 col-lg-10 col-md-8 col-sm-12 card-box pd-20  mr-auto ml-auto">
                <div class="row">
                    <length message="Data has not been submitted for the classes" :category="forms"></length>
                </div>

            </div>
        </div>
    </Layout>
</template>

<script>
export default {
    name: "GradeBook",
    props:{
        classes:[],
        term:{},
    },
    data() {
        return {
            form: '',
            forms: this.classes
        }
    },
    methods: {
        loadCategory() {
            this.forms = this.classes.filter((data)=>data.number === Number(this.form))

        }
    },
}
</script>

<style scoped>

</style>
