<template>
    <Layout>
        <Head><title>Eduket | Subjects</title></Head>
        <page-header current-page="Manage Subjects" page="Subjects"/>
        <div class="card-box pd-20 height-100-p mb-30 col-md-8 ml-auto mr-auto">
            <h5 class="h5">Subjects Offered
                <a data-toggle="modal" data-target="#subjects" href="" class="btn btn-sm btn-primary pull-right"><i class="fa fa-plus"></i></a>
            </h5>
            <br>
            <div class="table-responsive">
                <table v-if="subjects.length>0" class="table table-bordered table-sm">
                    <tbody>
                    <tr>
                        <th >#</th>
                        <th>Name</th>
                        <th>Abbr</th>
                        <th>Code</th>
                        <th></th>
                    </tr>
                    <tr v-for="(subject,index) in subjects">
                        <td>{{index+1}}</td>
                        <td>{{subject.name}}</td>
                        <td>{{subject.short_code}}</td>
                        <td>{{subject.code}}</td>
                        <td >
                            <a  @click.prevent="deleteSubject(subject.id)" href="" class="btn btn-sm btn-danger btn-flat"><i class="fa fa-trash"></i></a>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <length :category="subjects" message="No subjects have been added which are offered by your school. Please ensure that this is done as soon as possible as many activities may depend on this"></length>
            </div>
        </div>

        <Modal id="subjects" title="Add Subjects" :submit="addSubjects">
            <template #body>
                <multiselect label="name" track-by="id" placeholder="Select subjects to add"  v-model= "form.subject" :options="remaining" multiple />
            </template>
            <template #footer>
                <button class="btn btn-primary">Add</button>
            </template>
        </Modal>

    </Layout>
</template>

<script>
export default {
    name: "Subjects",
    props: {
        subjects:[] ,
        remaining:[] ,
    },
    data() {
        return {
            form: this.$inertia.form({
                subject:[]
            })
        }
    },
    methods: {
        addSubjects() {
            this.form.transform((data)=>({
                ...data,
                subject:this.form.subject.map((user)=>user.id)
            })).post(this.$route('subjects.store'),{
                preserveState:true,
                preserveScroll:true,
                onSuccess: ()=> {
                    this.form.reset()
                    $('#subjects').modal('hide')
                    Swal.fire(
                        'Created!',
                        'Subjects have been added',
                        'success'
                    )
                }
            })

        },

        deleteSubject(id){
            Swal.fire({
                title: 'Are you sure?',
                text: "Subject will no longer be part of your school. You can re-add at any time!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, remove subject!'
            }).then((result) => {
                if (result.value) {

                    this.form.delete(this.$route('subjects.destroy',id),{
                        preserveState:true,
                        preserveScroll:true,
                        onSuccess: ()=> {
                            $('#exampleModal').modal('hide')
                            Swal.fire(
                                'Removed!',
                                'Subject was removed',
                                'success'
                            )

                        }
                    })

                }
            })
        },
    },
}
</script>

<style scoped>

</style>
