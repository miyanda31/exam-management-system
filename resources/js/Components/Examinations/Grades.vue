<template>
    <Layout>
        <Head><title>Eduket | Grades</title></Head>
        <page-header :current-page="'Form '+form.number+form.name" page="Grades">
            <button v-if="status === 1 && subject !== null" @click.prevent="approveResults" class="btn btn-sm btn-success m-l-5">Approve</button>
            <button v-if="status === 2 && subject !== null" @click.prevent="allowEditing" class="btn btn-sm btn-info m-l-5">Allow Editing</button>
        </page-header>
        <div class="height-100-p mb-30">

            <div  class="row">
                <div  class="col-xl-8 col-lg-8 col-md-8 col-sm-12 mb-30 ml-auto mr-auto">
                    <div class="card-box pd-20">
                        <div class="profile-tab height-100-p">
                            <pagination :previous-pages="previousPages" :next-pages="nextPages" :data="users" >
                                <label>
                                    <select @change="loadCategory" v-model="subject" class="form-control input-sm ml-2 mr-2">
                                        <option disabled value="">Subject</option>
                                        <option v-for="sub in subjects" :value="sub.id" >{{sub.name}}</option>
                                    </select>
                                </label>
                            </pagination>

                            <table v-if="users.total > 0" id="table" class="table table-hover table-bordered">
                                <thead>
                                <tr v-if="Number(form.number) < 4">
                                    <th>#</th>
                                    <th>Full Name</th>
                                    <th>Gender</th>
                                    <th>CA1</th>
                                    <th>CA2</th>
                                    <th>ET</th>
                                    <th v-if="status === 2">Final</th>
                                    <th v-if="status === 2">Grade</th>
                                    <th v-if="status === 2">Remark</th>
                                </tr>

                                <tr v-else>
                                    <th>#</th>
                                    <th>Full Name</th>
                                    <th>Gender</th>
                                    <th v-for="paper in papers">P{{paper.paper}}</th>
                                    <th v-if="status === 2">Final</th>
                                    <th v-if="status === 2">Grade</th>
                                    <th v-if="status === 2">Remark</th>
                                </tr>
                                </thead>

                                <tbody>
                                <tr v-if="Number(form.number) < 4" v-for="(student,index) in users.data" :key="index">
                                    <td>{{student.student_id?student.student_id:''}}</td>
                                    <td>{{student.name}}</td>
                                    <td style="width: 40px;">{{student.gender}}</td>
                                    <td>
                                        {{student.grade?student.grade.scores.first:''}}
                                    </td>
                                    <td>
                                        {{student.grade?student.grade.scores.second:''}}
                                    </td>
                                    <td>
                                        {{student.grade?student.grade.scores.final:''}}
                                    </td>

                                    <td v-if="status === 2">{{student.grade?student.grade.score:''}}</td>
                                    <td v-if="status === 2">{{student.grade && student.grade.grading?student.grade.grading.grade:''}}</td>
                                    <td v-if="status === 2">{{student.grade && student.grade.grading?student.grade.grading.remark:''}}</td>

                                </tr>
                                <tr v-else v-for="student in users.data">
                                    <td>{{student.student_id}}</td>
                                    <td>{{student.name}}</td>
                                    <td>{{student.gender}}</td>
                                    <td v-for="paper in papers">{{student.grade?student.grade.scores['P'+paper.paper]:''}}</td>
                                    <td v-if="status === 2">{{student.grade?student.grade.score:''}}</td>
                                    <td v-if="status === 2">{{student.grade && student.grade.grading?student.grade.grading.grade:''}}</td>
                                    <td v-if="status === 2">{{student.grade && student.grade.grading?student.grade.grading.remark:''}}</td>

                                </tr>

                                </tbody>
                            </table>
                            <empty :category="users" :message="subject.length>0?'Results for selected subject have not been uploaded yet.' +
                             'If uploaded, they will be made available in this panel where you can approve and publish':'To load results for the class, please select subject'"/>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </Layout>
</template>

<script>
export default {
    name: "Grades",
    props:{
        subjects:[],
        papers:[],
        form:{},
        term:{},
        users:{},
        allocation:{},
        status:0
    },
    data() {
        return {
            subject: ''
        }
    },
    methods: {
        approveResults() {
            Swal.fire({
                title: 'Are you sure?',
                text: "Final marks for the class will be calculated!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, perform calculations!'
            }).then((result) => {
                if (result.value) {
                    this.$inertia.post(this.$route('gradebook.store'),{allocation:this.allocation.id},{
                        preserveScroll:true,
                        preserveState:true,
                        onSuccess: ()=> {
                            this.status = 2
                            Swal.fire(
                                'Approved!',
                                'Results were successfully compiled',
                                'success'
                            )
                        }
                    })

                }
            })

        },
        allowEditing() {
            Swal.fire({
                title: 'Are you sure?',
                text: "This will allow subject teacher to edit results until you re-approve, final marks will be calculated upon re-approval!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, allow editing!'
            }).then((result) => {
                if (result.value) {
                    this.$inertia.put(this.$route('gradebook.update',this.allocation.id),{
                    },{
                        preserveScroll:true,
                        preserveState:true,
                        onSuccess: ()=> {
                            Swal.fire(
                                'Approved!',
                                'You have successfully allowed editing',
                                'success'
                            )
                        }
                    })

                }
            })

        },

        loadCategory() {
            this.$inertia.get(this.$route('gradebook.show',this.form),{'t':this.term.id,'s':this.subject},{preserveState:true,preserveScroll:true})
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
    },
}
</script>


<style scoped>

</style>
