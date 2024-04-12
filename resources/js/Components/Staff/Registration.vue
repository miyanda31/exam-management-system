<template>
    <Layout>
        <Head><title>Eduket | Staff</title></Head>
        <page-header current-page="Staff Registration" page="Registration"/>
        <div class="card-box pd-20 height-100-p mb-30">
            <h5 class="h5">
                Staff Registration
                <a @click="addParent" data-toggle="modal" data-target="#exampleModal" href="" class="btn btn-sm btn-primary float-right"><i class="fa fa-plus"></i></a>
            </h5>

            <pagination :previous-pages="previousPages" :next-pages="nextPages" :data="users">
                <label >
                    <select v-on:change="loadCategory"  v-model="pages" class="form-control input-sm  ml-2 mr-2">
                        <option value="" disabled>Show</option>
                        <option v-for="i in 10">{{i*10}}</option>
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

            <staff-data v-if="users.total > 0">
                <template #body>
                    <tr v-for="(user,i) in users.data">
                        <td>{{i+1}}</td>
                        <td>{{user.lname + ' '+ user.fname}}</td>
                        <td>{{user.gender}}</td>
                        <td>
                            <button @click="editParents(user)"   class="btn btn-sm btn-success"><i class="fa fa-pencil"></i> Edit</button>
                            <button @click="deleteUser(user.id)"  class="btn btn-sm btn-danger"><i class="fa fa-trash"></i> Delete</button>
                        </td>
                    </tr>

                </template>
            </staff-data>
            <empty :category="users" message="No staff were found in this category. If you declare any staff in this category they will appear"></empty>
        </div>

        <Modal id="exampleModal" :title="(editMode?'Edit ':'Add ')+'Staff'" :submit="editMode?updateParent:createParent" >
            <template #body>
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
            </template>
            <template #footer>
                <button type="submit" class="btn btn-primary">{{editMode?'Update':'Create'}}</button>
            </template>
        </Modal>
    </Layout>
</template>

<script>
import StaffData from "./StaffData";
export default {
    name: "Registration",
    components: {StaffData},
    props: {
        users:{}
    },
    data() {
        return {
            editMode:false,
            gender:'',
            role:'',
            pages:'',
            title:'',
            categories:[],
            causes:[],
            form:this.$inertia.form({
                id:'',
                fname:'',
                lname:'',
                gender:'Male',
            }),
        }
    },

    methods: {

        loadCategory() {
            this.$inertia.get(this.$route('staff.index'),{'g':this.gender,'n':this.pages},{preserveState:true,preserveScroll:true})
        },
        loadParents(){
            this.$inertia.reload({only:['users']})
        },

        updateParent() {
            this.form.put(this.$route('staff.update',this.form.id),{
                preserveState:true,
                onSuccess: ()=> {
                    $('#exampleModal').modal('hide')
                    swal(
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
            this.form.phone = student.phone
            this.editMode = true

            $('#exampleModal').modal('show')
        },
        addParent(){
            this.form.reset()
            this.editMode = false
            $('#exampleModal').modal('show')
        },

        searchNames(query) {
            if (query) {
                axios.get(this.$route('staff.index',{s:query}),).then(({data})=>{
                    this.wards = data
                })
            }
        },



        createParent() {
            this.form.post(this.$route('staff.store'),{
                preserveState:true,
                preserveScroll:true,
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
                text: "All details of the user will be removed?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete user!'
            }).then((result) => {
                if (result.value) {

                    this.$inertia.delete(this.$route('staff.destroy',id),{
                        preserveState:true,
                        preserveScroll:true,
                        onSuccess: ()=> {
                            $('#exampleModal').modal('hide')
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

