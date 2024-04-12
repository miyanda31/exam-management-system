<template>
    <TeacherLayout>
        <Head><title>Eduket | Grades</title></Head>
        <page-header :current-page="'Form '+form.number+form.name" page="Grades">
            <button v-if="status === 0" @click.prevent="submitResults" class="btn btn-sm m-l-5 btn-success">Submit</button>
        </page-header>
        <div class="height-100-p mb-30">

            <div  class="row">
                <div :class="status > 0?'col-xl-10 col-lg-10':'col-xl-8 col-lg-8'" class="col-md-12 col-sm-12 mb-30 ml-auto mr-auto">
                    <div class="card-box pd-20">
                        <div class="profile-tab height-100-p">
                            <pagination :previous-pages="previousPages" :next-pages="nextPages" :data="users" >
                             <h5 class="h5">{{subject.name}}</h5>
                            </pagination>


                                <table v-if="Object.keys(users).length > 0 && users.data.length > 0" id="table" class="table table-hover table-bordered">
                                    <thead>
                                    <tr v-if="Number(form.number) < 4">
                                        <th>#</th>
                                        <th>Name</th>
                                        <th >Gender</th>
                                        <th>CA1</th>
                                        <th>CA2</th>
                                        <th>ET</th>
                                        <th v-if="status > 1">Final</th>
                                        <th v-if="status > 1">Grade</th>
                                        <th v-if="status > 1">Remark</th>
                                    </tr>

                                    <tr v-if="Number(form.number) === 4">
                                        <th>#</th>
                                        <th>Name</th>
                                        <th >Gender</th>
                                        <th  v-for="paper in papers">P{{paper.paper}}</th>
                                        <th v-if="status > 1">Final</th>
                                        <th  v-if="status > 1">Grade</th>
                                        <th v-if="status > 1">Remark</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    <tr v-if="Number(form.number) < 4" v-for="(student,index) in users.data" :key="index">
                                        <td >{{student.student_id?student.student_id:''}}</td>
                                        <td>{{student.name}}</td>
                                        <td>{{student.gender}}</td>
                                        <td style="cursor: pointer"  :id="'first'+index" @click="enterGrades('first',student.id,index)">
                                            {{student.grade?student.grade.scores.first:''}}
                                        </td>
                                        <td style="cursor: pointer"  :id="'second'+index" @click="enterGrades('second',student.id,index)">
                                            {{student.grade?student.grade.scores.second:''}}
                                        </td>
                                        <td  style="cursor: pointer" :id="'final'+index" @click="enterGrades('final',student.id,index)">
                                            {{student.grade?student.grade.scores.final:''}}
                                        </td>
                                        <td  v-if="status > 1">{{student.grade?student.grade.score:''}}</td>
                                        <td  v-if="status > 1">{{student.grade && student.grade.grading?student.grade.grading.grade:''}}</td>
                                        <td v-if="status > 1">{{student.grade && student.grade.grading?student.grade.grading.remark:''}}</td>

                                    </tr>
                                    <tr v-if="Number(form.number) === 4" :key="index" v-for="(user,index) in users.data">
                                        <td >{{user.student_id}}</td>
                                        <td>{{user.name}}</td>
                                        <td>{{user.gender}}</td>
                                        <td style="cursor: pointer" :id="'P'+paper.paper+(index+1)" @click="enterGrades('P'+paper.paper,user.id,index+1,i)" v-for="(paper,i) in papers">{{user.grade !== null?user.grade.scores['P'+paper.paper]:''}}</td>
                                        <td  v-if="status > 1">{{user.grade !== null?user.grade.score:''}}</td>
                                        <td v-if="status > 1">{{user.grade !== null && user.grade.grading?user.grade.grading.grade:''}}</td>
                                        <td v-if="status > 1">{{user.grade !== null && user.grade.grading?user.grade.grading.remark:''}}</td>
                                    </tr>
                                    </tbody>
                                </table>

                            <input id="grade" @keydown.prevent.enter.tab="createGrade" type="number" placeholder="Enter...">

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </TeacherLayout>
</template>

<script>
import TeacherLayout from "../TeacherLayout";
export default {
    name: "Grades",
    components: {TeacherLayout},
    props:{
        papers:[],
        form:{},
        term:{},
        users:{},
        subject:{},
        allocation:{},
        status:0
    },

    data() {
        return {
            id:'',
            type:'',
            message:'',
            i:'',
            cell:'',
            show:false,
        }
    },
    methods: {

        createGrade(){
            let grade =  $('#grade')
            let v = $('#' + this.cell)
            if (grade.val().trim() !== '' && grade.val() !== v.text()) {
                if ((this.type === 'first' || this.type === 'second') && (grade.val() <0 ||  grade.val()>20)) {
                    Swal.fire(
                        'Invalid Data',
                        'Continuous assessment must not be more than 20',
                        'error'
                    )
                    return
                }
                else if (this.type === 'final' && (grade.val() <0 ||  grade.val()>60 )) {
                    Swal.fire(
                        'Invalid Data',
                        "End of term must not be more than 60",
                        'error'
                    )
                    return
                }
                else if (this.i && this.type === 'P'+this.papers[this.i].paper && (grade.val() <0 ||  grade.val()>this.papers[this.i].score )) {
                    Swal.fire(
                        'Invalid Data',
                        "Grade entered for "+this.subject.name+' '+this.type.toUpperCase()+' must be between 0 and '+this.papers[this.i].score,
                        'error'
                    )
                    return
                }


                axios.post(this.$route('data-entry.store'),{
                    a :this.allocation.id,
                    c:this.type,
                    v :grade.val().trim(),
                    u:this.id
                }).then(()=>{
                    v.text(grade.val().trim())
                    grade.appendTo(v)
                    Swal.fire({
                        position:'top-end',
                        icon:'success',
                        title:'Grade was submitted successfully',
                        timer:1500,
                        heightAuto:true,
                        showConfirmButton:false,
                        toast:true
                    })
                    // grade.val('')
                })

            }
            grade.css({
                visibility:'hidden'
            })
        },

        enterGrades(type,id,index,i){

            if (this.status>0) return

            let grade =  $('#grade')
            let sel = $('#'+type+index);

            this.type = type
            this.id = id
            this.i = i?i:''
            this.cell = type+index


            grade.css({visibility:'visible'})

            grade.val(sel.text().trim())
            grade.height(sel.innerHeight())
            grade.width(sel.innerWidth())

            grade.appendTo(sel)
            grade.select()
            grade.focus()
        },

        submitResults(type){
            Swal.fire({
                title: 'Are you sure?',
                text: "Results will be analyzed and approved by administrators, You can not edit during this time!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, submit results!'
            }).then((result) => {
                if (result.value) {
                    this.$inertia.put(this.$route('data-entry.update',this.allocation.id),{},{
                        onSuccess:()=>{
                            Swal.fire(
                                'Submitted!',
                                'Results were submitted',
                                'success'
                            )
                        }
                    });

                }
            })


        },

        loadCategory() {
            this.$inertia.get(this.$route('data-entry.show',this.form),{'t':this.term.id,'s':this.subject},{preserveState:true,preserveScroll:true})
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
td {
    position: relative;
}


#grade {
    left:0;
    top:0;
    padding:0;
    margin:0;
    border: 0;
    position:absolute;
    visibility: hidden;
}
</style>
