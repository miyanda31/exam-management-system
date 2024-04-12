<template>
    <TeacherLayout>
        <Head><title>Eduket | Grade</title></Head>
        <page-header current-page="Grade Book" page="Grades" />

        <div class="pd-20 height-100-p mb-30">
            <div class="col-xl-10 col-lg-10 col-md-8 col-sm-12 mr-auto ml-auto">
                <div class="row">
                    <Link :href="$route('data-entry.show',[form.form.id,{t:term.id,s:form.subject.id}])" :key="form.id" v-for="form in allocations" class="col-lg-3 col-md-6 col-sm-12 mb-30">
                        <div class="card-box pd-30 height-100-p bg-info" >
                            <div class="progress-box text-center">
                                <span  class="dw dw-file-22 fa-5x text-white "></span>
                                <h4  class="padding-top-10 h4 text-white ">{{form.form.number}}{{form.form.name}}</h4>
                                <h4  class="h4 text-white ">{{form.subject.name}}</h4>
                            </div>
                        </div>
                    </Link>
                </div>

            </div>
            <div v-if="allocations.length === 0" class="col-xl-10 col-lg-10 col-md-8 col-sm-12 card-box pd-20  mr-auto ml-auto">
                <div class="row">
                    <length message="You have not been allocated any classes yet. Please contact your administrator to make an allocation for you" :category="allocations"></length>
                </div>

            </div>
        </div>
    </TeacherLayout>
</template>

<script>
import TeacherLayout from "../TeacherLayout";
export default {
    name: "GradeBook",
    components: {TeacherLayout},
    props:{
        allocations:[],
        term:{},
    },
}
</script>

<style scoped>

</style>
