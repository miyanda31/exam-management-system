<template>
    <Layout>
        <Head><title>Eduket | Sheets</title></Head>
        <page-header current-page="Mark Sheets" page="Sheets"/>
        <div class="pd-20 card-box mb-30">

                <h5 class="h5 mb-20">

                    Mark Sheet | Form {{form.number}}{{form.name}}
                    <span class="pull-right">
                    <label>
                        <select @change="loadCategory" v-model="selected"  class="form-control">
                            <option disabled value="">Class</option>
                            <option v-for="name in classes" :value="name.id">{{name.name}}</option>
                        </select>
                    </label>
                    <label >
                        <select @change="loadCategory"  v-model="year"  class="form-control">
                            <option disabled value="">Year</option>
                            <option v-for="y in terms" :value="y.id">Term {{y.number}} - {{y.year}}</option>
                        </select>
                    </label>
                </span>
                </h5>
            <div class="clearfix"></div>
           <div class="row">
               <pagination :previous-pages="previousPages" :next-pages="nextPages" :data="users" >

                   <a v-if="status === 0"  @click.prevent="publishResults" href="" class="btn btn-sm btn-primary"><span class="fa fa-check"></span> Approve</a>
                   <a v-if="status > 0" :href="$route('reports.index',{f:form.id,t:term.id,m:1,c:type})" class="btn btn-sm btn-danger"><span class="fa fa-download"></span> Mark Sheet</a>
                   <a v-if="status > 0" :href="$route('reports.index',{f:form.id,t:term.id,r:1})" class="btn btn-sm btn-primary"><span class="fa fa-download"></span> Reports</a>
                   <label >
                       <select  v-model="type"  class="form-control form-control-sm">
                           <option>Score</option>
                           <option>Grade</option>
                       </select>
                   </label>
               </pagination>
           </div>
            <br>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th v-for="subject in subjects">{{subject.short_code}}</th>

                        </tr>
                        </thead>
                        <tbody class="bg-white">
                        <tr v-for="student in users.data">
                            <td>
                                {{student.name}}
                            </td>
                            <td v-for="subject in subjects">
                                <span v-for="grades in student.grades" v-if="subject.id===grades.subject">{{type==='Score'?grades.score:grades.grade}}</span>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>


        </div>
    </Layout>
</template>

<script>
export default {
    name: "MarkSheet",
    props:{
        classes:[],
        users:{},
        subjects:[],
        terms:[],
        term:{},
        form: {},
        status:0,
    },
    data() {
        return {
            selected:'',
            year:'',
            type:'Score',
        }
    },
    methods: {
        loadCategory() {
            //TODO: Load class data when selection is made
        },
        nextPages() {
            if (this.users.next_page_url === null) {
                return;
            }

            this.$inertia.get(this.users.next_page_url,{},{preserveState:true,preserveScroll:true})
        },

        previousPages() {
            if (this.users.prev_page_url === null) {
                return;
            }

            this.$inertia.get(this.users.prev_page_url,{},{preserveState:true,preserveScroll:true})
        },
        publishResults() {

            Swal.fire({
                title: 'Are you sure?',
                text: "Publishing results will calculate final scores and positions for school reports!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, publish results!'
            }).then((result) => {
                if (result.value) {
                    this.$inertia.put(this.$route('mark-sheets.update',this.form.id),{t:this.term.id},{
                        preserveState:true,
                        preserveScroll:true,
                        onSuccess:()=> {
                            Swal.fire(
                                'Published!',
                                'Results published',
                                'success'
                            )
                         }
                    })
                }
            })
        }
    },
}
</script>

<style scoped>

</style>
