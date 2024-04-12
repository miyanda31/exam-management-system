<template>
    <Layout>
        <Head><title>Eduket | Terms</title></Head>
        <page-header current-page="School Terms" page="Terms"/>
        <div class="card-box pd-20 height-100-p mb-30">
            <h5 class="h5">Terms | {{calendar.academic}}
                <a href="" @click="addTerm" data-toggle="modal" data-target="#exampleModal" class="btn btn-sm btn-success pull-right">
                    <span class="fa fa-plus"></span>
                </a>
            </h5>
            <br>
            <div v-if="terms.length > 0" class="table-responsive">
                <table class="table table-condensed">
                    <tbody>
                    <tr>
                        <th>Term</th>
                        <th>Opening Date</th>
                        <th>Closing Date</th>
                        <th>Weeks</th>
                        <th></th>
                    </tr>
                    <tr v-for="term in terms">
                        <td><span v-if="term.status === 1" class="fa fa-check-square mr-2 text-success"></span> {{term.number }}</td>
                        <td>{{term.opening | myDate}}</td>
                        <td>{{term.closing | myDate}}</td>
                        <td>{{diffInWeeks(term)}}</td>
                        <td>
                            <a href="" v-if="term.status === 1" class="btn btn-sm btn-flat btn-danger" @click.prevent="manageTerm(term)">Close</a>
                            <a href="" v-if="term.status === 0" class="btn btn-sm btn-flat btn-success" @click.prevent="manageTerm(term)">Activate</a>
                            <button disabled v-if="term.status === 2" class="btn btn-sm btn-flat btn-behance" >Closed</button>

                            <a href="" v-if="term.status === 1" data-toggle="modal" data-target="#exampleModal" class="btn btn-sm btn-flat btn-info" @click.prevent="editTerm(term)">Edit</a>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <length :category="terms" message="No terms have been added for this academic calendar. Please ensure that this is set up as terms are the most important aspect of an academic calendar"></length>
        </div>

        <Modal id="exampleModal" :title="editMode?'Edit Term':'Add Term'" :submit="editMode?updateTerm:createTerm">
            <template #body>
                <div class="form-group">
                    <label >Term Number</label>
                    <select class="form-control" v-model="form.number">
                        <option value="">Term</option>
                        <option>1</option>
                        <option>2</option>
                        <option>3</option>
                    </select>
                    <strong class="text-danger"  v-if="form.errors.number">{{form.errors.number}}</strong>
                </div>

                <div class="form-group">
                    <label >Opening Date</label>
                    <datetime format="yyyy-MM-dd" title="Opening Date"   v-model="form.opening" input-class="form-control"></datetime>
                    <strong class="text-danger"  v-if="form.errors.opening">{{form.errors.opening}}</strong>
                </div>
                <div class="form-group">
                    <label >Closing Date</label>
                    <datetime format="yyyy-MM-dd" title="Closing Date"   v-model="form.closing" input-class="form-control"></datetime>
                    <strong class="text-danger"  v-if="form.errors.closing">{{form.errors.closing}}</strong>
                </div>

                <div class="form-group">
                    <label >Status</label>
                    <select class="form-control" v-model="form.status" id="">
                        <option value="">Status</option>
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
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
    name: "Terms",
    props:{
        terms:[],
        calendar:{}
    },
    data() {
        return {
            form: this.$inertia.form({
                id:'',
                opening:'',
                number:'',
                closing:'',
                status:'',
            }),
            editMode:false,

        }
    },
    methods: {
        diffInWeeks(term) {
            return moment(term.closing).diff(moment(term.opening),'weeks')
        },
        manageTerm(year){
            Swal.fire({
                title: 'Are you sure?',
                text: "Change the status of this term entry?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, modify term!'
            }).then((result) => {
                if (result.value) {
                    this.form.put(this.$route('term.update',[year.id,{s:year.status ===1?0:1}]),{
                        preserveState:true,
                        onSuccess:()=> {
                            Swal.fire(
                                'Closed!',
                                'Year was activated. Please proceed to creating terms',
                                'success'
                            )
                        }
                    })

                }
            })
        },
        addTerm() {
            this.form.reset()
            this.editMode = false;
        },

        editTerm(term) {
            this.form.id = term.id
            this.form.opening = term.opening
            this.form.closing = term.closing
            this.editMode = true;
        },


        updateTerm(){
            this.form.transform((data)=>({
                ...data,
                opening:moment(this.form.opening).format('YYYY-MM-DD') !== 'Invalid date'?moment(this.form.opening).format('YYYY-MM-DD') :"",
                closing:moment(this.form.closing).format('YYYY-MM-DD') !== 'Invalid date'?moment(this.form.closing).format('YYYY-MM-DD') :"",
                calendar:this.calendar.id,
            })).put(this.$route('term.update',this.form.id),{
                preserveState:true,
                onSuccess: ()=> {
                    $('#exampleModal').modal('hide')
                    Swal.fire(
                        'Updated!',
                        'Term updated',
                        'success'
                    )
                }
            })

        },

        createTerm(){
            this.form.transform((data)=>({
                ...data,
                opening:moment(this.form.opening).format('YYYY-MM-DD') !== 'Invalid date'?moment(this.form.opening).format('YYYY-MM-DD') :"",
                closing:moment(this.form.closing).format('YYYY-MM-DD') !== 'Invalid date'?moment(this.form.closing).format('YYYY-MM-DD') :"",
                calendar:this.calendar.id,
            })).post(this.$route('term.store'),{
                preserveState:true,
                preserveScroll:true,
                onSuccess: ()=> {
                    $('#exampleModal').modal('hide')
                    Swal.fire(
                        'Created!',
                        'Term  added',
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
