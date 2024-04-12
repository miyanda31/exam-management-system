<template>
    <Layout>
        <Head><title>Eduket | Promote</title></Head>
        <page-header current-page="Promote Classes" page="Promote"/>
        <div class="card-box pd-20 height-100-p mb-30">
            <h5 class="h5">Promote Students</h5>
            <div class="table-wrap table-responsive">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Class</th>
                        <th>Stream</th>
                        <th>Total</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr :id="'form'+f.id" v-for="(f,index) in classes">
                        <th scope="row">{{index+1}}</th>
                        <td>{{f.name}}</td>
                        <td>{{f.shift}}</td>
                        <td>{{f.total}}</td>
                        <td>
                            <button  @click="addStudents('All',f.id)" href="" class="btn btn-sm btn-primary">All</button>
                            <button @click="addStudents('s',f.id)" href="" class="btn btn-sm btn-success">Select</button>
                        </td>
                    </tr>

                    </tbody>
                </table>
            </div>
        </div>

        <Modal id="promote" title="Promote Students" :submit="promoteStudents">
            <template #body>
                <div class="form-group">
                    <label>Academic Year </label>
                        <select v-model="form.calendar" class="form-control input-sm">
                            <option value="" disabled>Academic Year</option>
                            <option v-for="year in years" :value="year.id">{{year.academic}}</option>
                        </select>
                    <span class="help-block"><small>Select academic year students will start after promotion</small></span>
                </div>
                <div v-if="load" class="form-group">
                    <label>Students to Promote </label>
                    <multiselect multiple @searchable="true" @search-change="loadStudents" v-model="form.users" track-by="id" label="name" :options="users"></multiselect>
                    <span class="help-block text-primary"><small>Instead of selecting students to promote, select those that
                                    will note be promoted first because they are few in number</small></span>
                </div>
            </template>
            <template #footer>
                <button type="submit"  class="btn btn-primary">Promote</button>
            </template>
        </Modal>

    </Layout>
</template>

<script>
export default {
    name: "Promotion",
    props: {
        classes:[],
        years:[]
    },
    data() {
        return {
            load:false,
            form:this.$inertia.form({
                users:[],
                calendar:'',
                id:'',

            }),

            users:[],
        }
    },
    methods: {
        addStudents(type,id){
            if (this.years.length === 0) {
                Swal.fire(
                    'No Calendar!',
                    'No new calendar has been set to promote students to. Please add in calendars',
                    'error'
                )
                return
            }

            this.form.id = id
            this.load = type !== 'All';
            $('#promote').modal('show')

        },
        loadStudents(query) {
            if (query) {
                axios.get(this.$route('promote.index',{s:query,f:this.form.id})).then(({data})=>{
                    this.users = data
                })
            }
        },

        promoteStudents() {
            Swal.fire({
                title: 'Are you sure?',
                text: "If student's next class is greater than 4, they'll become alumni automatically!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, promote students!'
            }).then((result) => {
                if (result.value) {
                    this.form.transform((data)=>({
                        ...data,
                        users:this.form.users.map((data)=>data.id)
                    })).post(this.$route('promote.store'),{
                        onSuccess:()=>{
                            $('#promote').modal('hide')
                            this.student = ''
                            this.form.reset()
                            Swal.fire(
                                'Promoted!',
                                'Students were promoted to the new class',
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
