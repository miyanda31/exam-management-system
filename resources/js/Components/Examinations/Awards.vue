<template>
    <Layout>
        <Head><title>Eduket | Awards</title></Head>
        <page-header current-page="School Awards" page="Awards"/>
        <div class="card-box pd-20 height-100-p mb-30">
            <h5  class="h5">
                Examination Awards
                <div   class="pull-right">
                    <button  v-if="grades.length>0" class="btn btn-sm btn-primary m-r-5"><i class="fa fa-print"></i> Print</button>
                    <label class="line-up" >
                        <select @change="loadCategory"   v-model="number"  class="form-control input-sm">
                            <option disabled value="">Class</option>
                            <option v-for="n in 4">{{n}}</option>
                        </select>
                    </label>

                    <label class="line-up" >
                        <select @change="loadCategory"  v-model="year"  class="form-control input-sm">
                            <option disabled value="">Term</option>
                            <option v-for="term in terms" :value="term.id">T{{term.number}} - {{term.year}}</option>
                        </select>

                    </label>

                </div>
            </h5>

            <div v-if="grades.length > 0" class="p-b-10">

                <div class="table-responsive">
                    <table id="top"   class="table table-hover table-bordered">
                        <thead>
                        <tr>
                            <th colspan="4" class="text-center">
                                TOP 10 IN FORM {{number?number:1}}
                            </th>
                        </tr>
                        <tr>
                            <th>Name</th>
                            <th>Gender</th>
                            <th>Aggregate</th>
                            <th>Position</th>
                        </tr>
                        </thead>

                        <tbody>
                        <tr  v-for="(student,index) in grades" :key="index">
                            <td>{{student.user.fname+' '+student.user.lname}}</td>
                            <td>{{student.user.gender.substring(0,1)}}</td>
                            <td>
                                {{student.aggregate}}
                            </td>
                            <td>
                                {{student.position}}
                            </td>

                        </tr>
                        </tbody>
                    </table>
                </div>
                <!-- /.mail-box-messages -->
            </div>
            <length :category="grades" message="No results have been approved at this moment. When administrators have approved, classes will appear here"></length>

        </div>
    </Layout>
</template>

<script>
export default {
    name: "Awards",
    props:{
        term:{},
        terms:[],
        grades:[]
    },
    data() {
        return {
            year: '',
            number: '',
        }
    },
    methods: {
        loadCategory() {
            this.$inertia.get(this.$route('awards.index'),{'t':this.year,'f':this.number},{preserveState:true,preserveScroll:true})
        },
    },
}
</script>

<style scoped>

</style>
