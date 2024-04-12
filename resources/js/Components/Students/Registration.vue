<template>
    <Layout :search-admin="searchNames" :s="true">
        <Head><title>Eduket | Students</title></Head>
        <page-header current-page="Student Registration" page="Registration"/>
        <div class="card-box pd-20 height-100-p mb-30">
            <h5 class="h5">
                Manage Student Registration
                <a @click="addParent" data-toggle="modal" data-target="#exampleModal" href="" class="btn btn-sm btn-primary float-right"><i class="fa fa-plus"></i></a>
            </h5>

            <pagination :previous-pages="previousPages" :next-pages="nextPages" :data="users">
                <label >
                    <select v-on:change="loadCategory"  v-model="pages" class="form-control input-sm  ml-2 mr-2">
                        <option value="" disabled>Show</option>
                        <option v-for="i in 10">{{i*10}}</option>
                    </select>
                </label>
                <label >
                    <select @change="loadCategory" v-model="level" class="form-control input-sm ml-2 mr-2">
                        <option disabled value=""> Class</option>
                        <option v-for="f in classes" :value="f.id">{{f.name}}</option>
                    </select>
                </label>
                <label>
                    <select @change="loadCategory" v-model="gender" class="form-control input-sm ml-2 mr-2">
                        <option disabled value=""> Gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </label>

            </pagination>

            <student-data v-if="users.total > 0">
                <template #body>
                    <tr v-for="(user,i) in users.data">
                        <td>{{user.student_id}}</td>
                        <td>{{user.lname + ' '+ user.fname}}</td>
                        <td>{{user.form.length>0?user.form[0].number + user.form[0].name:"N/A"}}</td>
                        <td>{{user.gender}}</td>
                        <td>
                            <button @click="editParents(user)"   class="btn btn-sm btn-success"><i class="fa fa-pencil"></i> Edit</button>
                            <button @click="deleteUser(user.id)"  class="btn btn-sm btn-danger"><i class="fa fa-trash"></i> Delete</button>
                        </td>
                    </tr>

                </template>
            </student-data>
            <empty :category="users" message="No students were found in this category. If you declare any students in this category they will appear"></empty>
        </div>

        <Modal id="exampleModal" :title="(editMode?'Edit ':'Add ')+'Student Details'" :submit="editMode?updateParent:createParent" >
            <template #body>
                <progress v-if="form.progress" :value="form.progress.percentage" max="100">
                    {{form.progress.percentage}}%
                </progress>
                <div class="form-group">
                    <label >First Name</label>
                    <input v-model="form.fname"   type="text" class="form-control"  >
                    <strong class="text-danger"  v-if="form.errors.fname">{{form.errors.fname}}</strong>
                </div>
                <div class="form-group">
                    <label >Last Name</label>
                    <input  v-model="form.lname"  type="text" class="form-control"  >
                    <strong class="text-danger"  v-if="form.errors.lname">{{form.errors.lname}}</strong>
                </div>
                <div class="form-group">
                    <label>Gender</label>
                    <div class="d-flex">
                        <div class="custom-control custom-radio mb-5 mr-20">
                            <input type="radio" id="customRadio4" value="Male" v-model="form.gender" class="custom-control-input">
                            <label class="custom-control-label weight-400" for="customRadio4">Male</label>
                        </div>
                        <div class="custom-control custom-radio mb-5">
                            <input type="radio" id="customRadio5" value="Female" v-model="form.gender" class="custom-control-input">
                            <label class="custom-control-label weight-400" for="customRadio5">Female</label>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Class</label>
                    <select v-model="form.class" class="form-control">
                        <option value="">Select class....</option>
                        <option  v-for="f in classes" :value="f.id">{{f.name}}</option>
                    </select>
                    <strong class="text-danger"  v-if="form.errors.class">{{form.errors.class}}</strong>
                </div>
            </template>
            <template #footer>
                <button type="submit" class="btn btn-primary">{{editMode?'Update':'Create'}}</button>
            </template>
        </Modal>

    </Layout>
</template>

<script>
import StudentData from "./StudentData";
export default {
    name: "Registration",
    components: {StudentData},
    props: {
        classes: [],
        reasons: [],
        users:{}
    },
    data() {
        return {
            editMode:false,
            category:'Active',
            gender:'',
            level:'',
            start:'',
            search:'',
            end:'',
            pages:'',
            type:'',
            categories:[],
            cause:[],
            date: new Date(),
            currentDate: new Date(),
            form:this.$inertia.form({
                id:'',
                fname:'',
                lname:'',
                class:'',
                gender:'Male',
                photo:'',
            }),
        }
    },

    methods: {
        setDates() {
            this.start = this.date.start;
            this.end = this.date.end;

            this.loadCategory()
        },

        loadCategory() {

            this.$inertia.get(this.$route('students.index'),{'f':this.level,'g':this.gender,'n':this.pages,'q':this.search},{preserveState:true,preserveScroll:true})
        },
        loadParents(){
            this.$inertia.reload({only:['users']})
        },

        updateParent() {
            this.form.transform((data)=>({
                ...data,
                enrolled:moment(this.form.enrolled).format('Y-MM-D')
            })).put(this.$route('students.update',this.form.id),{
                preserveState:true,
                onSuccess: ()=> {
                    $('#exampleModal').modal('hide')
                    Swal.fire(
                        'Updated!',
                        'User profile was updated',
                        'success'
                    )

                }
            })
        },

        editParents(student){
            this.form.id = student.id
            this.form.fname = student.fname
            this.form.lname = student.lname
            this.form.gender = student.gender
            this.form.enrolled = student.enrolled
            this.form.class = student.form.length>0?student.form[0].id:""
            this.editMode = true

            $('#exampleModal').modal('show')
        },
        addParent(){
            this.form.reset()
            this.editMode = false
            $('#exampleModal').modal('show')
        },

        searchNames(query) {
            this.search = query
            this.loadCategory()
        },



        createParent() {
            this.form.transform((data)=>({
                ...data,
                enrolled:moment(this.form.enrolled).format('Y-MM-D')
            })).post(this.$route('students.store'),{
                preserveState:true,
                onSuccess: ()=> {
                    $('#exampleModal').modal('hide')
                    Swal.fire(
                        'Registered!',
                        'User profile was created',
                        'success'
                    )
                }
            })

        },

        deleteUser(id){
            Swal.fire({
                title: 'Are you sure?',
                text: "All details of the user will be removed. This action can not be undone?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete user!'
            }).then((result) => {
                if (result.value) {

                    this.form.delete(this.$route('students.destroy',id),{
                        preserveState:true,
                        preserveScroll:true,
                        onSuccess: ()=> {

                            Swal.fire(
                                'Removed!',
                                'User was removed',
                                'success'
                            )

                        }
                    })

                }
            })
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


