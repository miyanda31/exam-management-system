<template>
<Layout>
    <Head><title>Eduket | Allocations</title></Head>
    <page-header current-page="Class Allocations" page="Allocations"/>
    <div class="card-box pd-20 height-100-p mb-30">
        <div class="clearfix mb-20">
                <h5 class="text-blue h5">Teacher Allocations
                    <button type="button" @click="addAllocation"  data-toggle="modal" data-target="#add" class="btn btn-sm btn-primary float-right"><i class="fa fa-plus"></i> Allocation</button>
                </h5>

            <div >
                <br>

                <label style="display: inline-flex;" class="pull-left">&nbsp;
                    <select v-on:change="loadCategory"  v-model="entries" class="form-control input-sm">
                        <option value="" disabled>Show</option>
                        <option v-for="i in 10">{{i*10}}</option>
                    </select>
                </label>
                <label style="display: inline-flex;" class="pull-left">&nbsp;
                    <select v-on:change="loadCategory"  v-model="number" class="form-control input-sm">
                        <option value="" disabled>Form</option>
                        <option v-for="i in classes" :value="i.id">{{i.name}}</option>
                    </select>
                </label>
                <label style="display: inline-flex;" class="pull-left">&nbsp;
                    <select v-on:change="loadCategory"  v-model="subject" class="form-control input-sm">
                        <option value="" disabled>Subject</option>
                        <option v-for="sub in subjects" :value="sub.id">{{sub.short_code}}</option>
                    </select>
                </label>

                <div v-if="allocations.total>0" class="float-right">

                   Page {{allocations.current_page + ' of '+allocations.last_page}}
                    <div class="btn-group">
                        <button  @click="previousPages" type="button" class="btn btn-default btn-sm"><i class="fa fa-chevron-left"></i></button>
                        <button @click="nextPages" type="button" class="btn btn-default btn-sm"><i class="fa fa-chevron-right"></i></button>
                    </div>
                    <!-- /.btn-group -->
                </div>
            </div>

        </div>
        <table v-if="allocations.total>0" class="table">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Subject</th>
                <th scope="col">Class</th>
                <th scope="col"></th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(allocation,i) in allocations.data">
                <th scope="row">{{i+1}}</th>
                <td>{{allocation.user?allocation.user.fname:''}} {{allocation.user?allocation.user.lname:''}}</td>
                <td>{{allocation.subject.name}}</td>
                <td>{{allocation.form.number}}{{allocation.form.name}}</td>
                <td>
                    <button data-placement="top" data-toggle="tooltip" title="Edit Allocation" @click="editAllocation(allocation)" class="btn btn-success btn-sm">
                        <i class="fa fa-pencil"></i>
                    </button>
                    <a href="" @click.prevent="deleteAllocation(allocation.id)" class="btn btn-danger btn-sm" data-placement="top" data-toggle="tooltip" title="Remove Allocation">
                        <i class="fa fa-trash-o"></i>
                    </a>
                </td>
            </tr>
            </tbody>
        </table>
        <empty :category="allocations" message="No allocations have been made for this term. Please make allocations in order to manage"/>

        <div v-if="allocations.total>0" class="float-right">
            Page {{allocations.current_page + ' of '+allocations.last_page}}
            <div class="btn-group">
                <button  @click="previousPages" type="button" class="btn btn-default btn-sm"><i class="fa fa-chevron-left"></i></button>
                <button @click="nextPages" type="button" class="btn btn-default btn-sm"><i class="fa fa-chevron-right"></i></button>
            </div>

        </div> <br>

        <div class="modal fade" id="add" tabindex="-1" role="dialog" aria-labelledby="add" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{editMode?'Edit ':'Add '}} Allocation</h4>
                        <button type="button" class="close pull-right" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>

                    </div>
                    <form @submit.prevent="editMode?updateAllocation():createAllocation()">
                    <div class="modal-body">

                        <div class="form-group">
                            <label >Name</label>
                            <multiselect  @searchable="true" placeholder="Search User" track-by="id" label="name" v-model="user" :options="users"></multiselect>
                            <span class="text-danger" v-if="form.errors.id">{{form.errors.id}}</span>
                        </div>
                            <div class="form-group">
                                <label >Subject</label>
                                <select v-model="form.subject"   class="form-control"  >
                                    <option disabled value="">Select Subject</option>
                                    <option v-for="subject in subjects" :value="subject.id">{{subject.short_code}}</option>
                                </select>
                                <span class="text-danger" v-if="form.errors.subject">{{form.errors.subject}}</span>
                            </div>
                            <div class="form-group">
                                <label >Class</label>
                                <select v-model="form.class"  class="form-control" >
                                    <option disabled value="">Select Class</option>
                                    <option v-for="f in classes" :value="f.id">{{f.name}}</option>
                                </select>
                                <span class="text-danger" v-if="form.errors.class">{{form.errors.class}}</span>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button class="btn btn-primary">{{editMode?'Update':'Create'}}</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</Layout>
</template>

<script>
export default {
    name: "Allocations",
    props: {
        subjects:[],
        classes:[],
        users:[],
        allocations: {},
    },
    data() {
        return {
            subject:'',
            entries:'',
            number:'',
            id:'',
            user:{},
            editMode:false,
            form:this.$inertia.form({
                id:'',
                subject:'',
                class:'',
            }),
        }
    },
    methods:{
        searchUser() {

        },
        addAllocation() {
            this.editMode = false;
            this.form.reset()
            $('#add').modal('show');
        },
        editAllocation(allocation) {
            this.editMode = true;
            this.form.subject=allocation.form.id
            this.form.id=allocation.user.id
            this.id=allocation.id
            this.user= {id:this.form.id,name:allocation.user.lname + ' '+ allocation.user.fname}
            this.form.class=allocation.form.id
            $('#add').modal('show');
        },

        updateAllocation() {
            this.form.put(this.$route('allocations.update',this.id),{
                preserveScroll:true,
                preserveState:true,
                onSuccess: ()=> {
                    $('#add').modal('hide');
                    this.form.reset()
                    this.user = {}
                    this.$inertia.reload({only:['allocations']})
                    Swal.fire(
                        'Updated!',
                        'User details updated',
                        'success'
                    )
                }
            })
        },

        createAllocation() {
            this.form.transform((data)=>({
                ...data,
                id:this.user.id
            })).post(this.$route('allocations.store'),{
                preserveScroll:true,
                preserveState:true,
                onSuccess: ()=> {
                    $('#add').modal('hide');
                    this.form.reset()
                    this.user = {}
                    this.$inertia.reload({only:['allocations']})
                    Swal.fire(
                        'Created!',
                        'User was allocated',
                        'success'
                    )
                }
            })

        },

        deleteAllocation(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "Removing allocation will also remove any data associated with it!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete!'
            }).then((result) => {
                if (result.value) {
                    this.$inertia.delete(this.$route('allocations.destroy',id),{
                        preserveScroll:true,
                        preserveState:true,
                        onSuccess:()=> {
                            Swal.fire(
                                'Deleted!',
                                'Allocation has been removed',
                                'success'
                            )
                        }
                    })

                }
            })
        },

        loadCategory() {
            this.$inertia.get(this.$route('allocations.index'),{'n':this.number,'p':this.entries,'s':this.subject},{preserveState:true})
        },
        nextPages() {
            if (this.allocations.next_page_url === null) {
                return;
            }

            this.$inertia.get(this.allocations.next_page_url,{},{preserveState:true})
        },

        previousPages() {
            if (this.allocations.prev_page_url === null) {
                return;
            }

            this.$inertia.get(this.allocations.prev_page_url,{},{preserveState:true})
        },
    }
}
</script>

<style scoped>

</style>
