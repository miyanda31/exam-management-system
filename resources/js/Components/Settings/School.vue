<template>
    <Layout>
        <Head><title>Eduket | School</title></Head>
        <page-header current-page="School Information" page="School"/>
        <div class="col-md-8 mr-auto ml-auto">
            <div class="card-box pd-20 height-100-p mb-30">
                <h5 class="h5">School Profile
                    <a href="" @click="editMode?editInformation(school):addInformation" data-toggle="modal" data-target="#exampleModal" class="btn btn-sm pull-right" :class="editMode?'btn-success':'btn-primary'">
                        <span :class="editMode?'fa-pencil':'fa-plus'" class="fa"></span> {{editMode?'Edit':'Add'}}
                    </a>
                </h5>
                <br>
                <div v-if="Object.keys(school).length > 0" class="table-responsive">
                    <table class="table table-condensed table-bordered">
                        <tbody>
                        <tr>
                            <th>Name</th>
                            <td>{{school.name}}</td>
                        </tr>
                        <tr>
                            <th>Address</th>
                            <td>{{school.address}}</td>
                        </tr>
                        <tr>
                            <th>Motto</th>
                            <td>{{school.motto}}</td>
                        </tr>
                        <tr>
                            <th>Phone</th>
                            <td>{{school.phone}}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{school.email}}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <empty :category="school" message="No terms have been added for this academic calendar. Please ensure that this is set up as terms are the most important aspect of an academic calendar"></empty>
            </div>
        </div>

        <Modal id="exampleModal" :title="(editMode?'Edit':'Add') +' School Information'" :submit="editMode?updateInformation:createInformation">
            <template #body>
                <div class="form-group">
                    <label >School Name</label>
                    <input  v-model="form.name"  type="text" class="form-control"  >
                    <strong class="text-danger"  v-if="form.errors.name">{{form.errors.name}}</strong>
                </div>
                <div class="form-group">
                    <label >School Motto</label>
                    <input  v-model="form.motto" class="form-control"  >
                    <strong class="text-danger"  v-if="form.errors.motto">{{form.errors.motto}}</strong>
                </div>
                <div class="form-group">
                    <label >School Address</label>
                    <textarea  v-model="form.address" class="form-control"  ></textarea>
                    <strong class="text-danger"  v-if="form.errors.address">{{form.errors.address}}</strong>
                </div>
                <div class="form-group">
                    <label >School Phone</label>
                    <input  v-model="form.phone"  type="text" class="form-control"  >
                    <strong class="text-danger"  v-if="form.errors.phone">{{form.errors.phone}}</strong>
                </div>
                <div class="form-group">
                    <label >School Email</label>
                    <input  v-model="form.email"  type="text" class="form-control"  >
                    <strong class="text-danger"  v-if="form.errors.email">{{form.errors.email}}</strong>
                </div>

                <div class="form-group">
                    <label>School Logo</label>
                    <div class="custom-file">
                        <input @input="uploadPhoto" type="file" class="custom-file-input">
                        <label class="custom-file-label">{{form.logo?form.logo.name:'Choose file'}}</label>
                    </div>
                    <strong class="text-danger"  v-if="form.errors.logo">{{form.errors.logo}}</strong>
                </div>
            </template>
            <template #footer>
                <button type="submit"  class="btn btn-primary">{{editMode?'Update':'Create'}}</button>
            </template>
        </Modal>


    </Layout>
</template>

<script>
export default {
    name: "School",
    props: {
        school:[]
    },
    data() {
        return {
            form: this.$inertia.form({
                id:'',
                name:'',
                phone:'',
                email:'',
                address:'',
                motto:'',
                logo:'',
            }),
            editMode:!!this.school.name,

        }
    },
    methods: {

        uploadPhoto(event) {
            this.form.logo = event.target.files[0]
        },

        addInformation() {
            this.form.reset()
            this.editMode = false;
        },

        editInformation(information) {
            this.form.id = information.id
            this.form.name = information.name
            this.form.email = information.email
            this.form.address = information.address
            this.form.phone = information.phone
            this.form.motto = information.motto
            this.editMode = true;
        },


        updateInformation(){

            this.form.post(this.$route('school.update',[this.form.id,{'_method':'put'}]),{
                preserveState:true,
                preserveScroll:true,
                onSuccess: ()=> {
                    $('#exampleModal').modal('hide')
                    Swal.fire(
                        'Updated!',
                        'Information updated',
                        'success'
                    )
                }
            })

        },

        createInformation(){
            this.form.post(this.$route('school.store'),{
                preserveState:true,
                preserveScroll:true,
                onSuccess: ()=> {
                    $('#exampleModal').modal('hide')
                    Swal.fire(
                        'Created!',
                        'Information  added',
                        'success'
                    )
                }
            })
        }


    },
}
</script>

<style scoped>

</style>
