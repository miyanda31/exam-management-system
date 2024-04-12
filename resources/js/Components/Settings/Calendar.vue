<template>
    <Layout>
        <Head><title>Eduket | Calendar</title></Head>
        <page-header current-page="School Calendar" page="Calendar"/>
        <div class="card-box pd-20 height-100-p mb-30">
            <div  class="table-responsive">
                <h5 class="h5">Calendar Management
                    <button  @click="addCalendar" data-toggle="modal" data-target="#exampleModal" class="btn btn-sm btn-primary pull-right">
                        <span class="fa fa-plus"></span>
                    </button>
                </h5>
                <br>
                <table v-if="calendar.length>0" class="table table-condensed table-sm table-bordered">
                    <tbody>
                    <tr>
                        <th>Opening</th>
                        <th>Closing</th>
                        <th>Year</th>
                        <th>Terms</th>
                        <th></th>
                    </tr>
                    <tr v-for="year in calendar ">
                        <td>{{year.opening | myDate}}</td>
                        <td>{{year.closing | myDate}}</td>
                        <td>{{year.academic}}</td>
                        <td>{{year.terms}}</td>
                        <td>
                            <a @click.prevent="manageCalendar(year)" href="" class="btn btn-sm" :class="year.status===0?'btn-success':'btn-danger'" >{{year.status === 0?'Open':'Close'}}</a>
                            <a href="" data-toggle="modal" data-target="#exampleModal" class="btn btn-sm btn-flat btn-primary" @click.prevent="editCalendar(year)"><span class="fa fa-pencil"></span></a>
                            <Link :href="$route('calendar.show',year.id)"  class="btn btn-sm btn-flat btn-primary"><span class="fa fa-cog"></span></Link>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <length :category="calendar" message="Calendar Information has not been set. Please ensure that these are set up as school activities are fully depended on th calendar"></length>
        </div>

        <Modal id="exampleModal" :title="(editMode?'Edit':'Add') +' Academic Calendar'" :submit="editMode?updateCalendar:createCalendar">
            <template #body>
                <div class="form-group">
                    <label >Start Date</label>
                    <datetime format="yyyy-MM-dd"   v-model="form.opening" input-class="form-control"></datetime>
                </div>
                <div class="form-group">
                    <label >End Date</label>
                    <datetime format="yyyy-MM-dd"   v-model="form.closing" input-class="form-control"></datetime>
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
    name: "Calendar",
    props: {
        calendar:[]
    },
    data() {
        return {
            form: this.$inertia.form({
                id:'',
                opening:'',
                closing:'',
                year:'',
            }),
            editMode:false,

        }
    },
    methods: {

        manageCalendar(year){
            Swal.fire({
                title: 'Are you sure?',
                text: "Change the status of this calendar entry?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, modify calendar!'
            }).then((result) => {
                if (result.value) {
                    this.form.put(this.$route('calendar.update',[year.id,{s:year.status ===1?0:1}]),{
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
        addCalendar() {
            this.form.reset()
            this.editMode = false;
        },

        editCalendar(calendar) {
            this.form.id = calendar.id
            this.form.opening = calendar.opening
            this.form.closing = calendar.closing
            this.editMode = true;
        },


        updateCalendar(){
            this.form.transform((data)=>({
                ...data,
                opening:moment(this.form.opening).format('YYYY-MM-DD'),
                closing:moment(this.form.closing).format('YYYY-MM-DD'),
                year:moment(this.form.opening).format('YYYY'),
            })).put(this.$route('calendar.update',this.form.id),{
                preserveState:true,
                onSuccess: ()=> {
                    $('#exampleModal').modal('hide')
                    Swal.fire(
                        'Updated!',
                        'Calendar updated',
                        'success'
                    )
                }
            })

        },

        createCalendar(){
            this.form.transform((data)=>({
                ...data,
                opening:moment(this.form.opening).format('YYYY-MM-DD'),
                closing:moment(this.form.closing).format('YYYY-MM-DD'),
                year:moment(this.form.opening).format('YYYY'),
            })).post(this.$route('calendar.store'),{
                preserveState:true,
                onSuccess: ()=> {
                    $('#exampleModal').modal('hide')
                    Swal.fire(
                        'Created!',
                        'Calendar  added',
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
