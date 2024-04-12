<template>
    <Layout>
        <Head>
            <title>Eduket | Fields</title>
        </Head>
        <page-header current-page="Fields" page="Fields"/>
        <div class="card-box pd-20 height-100-p mb-30">

            <h5>
                Manage {{category.name}}
                <button  @click="addField" data-toggle="modal" data-target="#fields"  class="btn btn-primary btn-sm pull-right text-white"><i class="fa fa-plus"></i></button>
            </h5>
            <br>

            <table v-if="fields.length>0" class="table table-bordered table-sm">
                <tbody>
                <tr>
                    <th>Field Name</th>
                    <th>Type</th>
                    <th>Required</th>
                    <th>Options</th>
                    <th></th>
                </tr>
                <tr :id="'field'+p.id" v-for="(p,index) in fields">
                    <td>{{p.name}}</td>
                    <td>{{p.type}}</td>
                    <td>{{p.required === 1?'Yes':'No'}}</td>
                    <td>{{ p.value}}</td>
                    <td>
                        <a @click="editFields(p)" data-toggle="modal" data-target="#fields" href="" class="btn btn-sm btn-primary"><i class="fa fa-pencil"></i></a>
                        <a @click.prevent="deleteFields(p.id)" href="" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>
                    </td>
                </tr>
                </tbody>
            </table>

            <length message="No fields were found in the selected category. Please add some fields" :category="fields"></length>

        </div>

        <Modal id="fields" :title="(editMode?'Edit ':'Add ')+'Field' " :submit="editMode?updateField:createField">
            <template #body>
                <div class="form-group ">
                    <label>Field Name</label>
                    <input type="text" v-model="form.name" class="form-control" placeholder="Field Name e.g Phone Number">
                    <span class="text-danger" v-if="form.errors.name">{{form.errors.name}}</span>
                </div>
                <div class="form-group ">
                    <select @change="loadData" class="form-control" v-model="form.type">
                        <option value="" disabled>Data Type</option>
                        <option>Text</option>
                        <option>Large Text</option>
                        <option>Yes/No</option>
                        <option>Date</option>
                        <option>Options</option>
                    </select>
                    <span class="text-danger" v-if="form.errors.type">{{form.errors.type}}</span>
                </div>
                <div id="options" style="display: none"  class="mb-30 form-group">
                    <label class="h5">Enter Options (<code>Type and hit enter</code>)</label> <br>
                    <input id="values" placeholder="add options" type="text" data-role="tagsinput" class="form-control">
                </div>
                <div class="custom-control custom-checkbox mb-5">
                    <input v-model="form.required" type="checkbox" class="custom-control-input" id="customCheck1">
                    <label class="custom-control-label" for="customCheck1">Field must be required</label>
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
    name: "Fields",
    props: {
        fields: [],
        category: {},
    },
    data() {
        return {
            form: this.$inertia.form({
                id:'',
                name:'',
                values:'',
                type:'',
                required:'',
                category:''
            }),
            editMode:false
        }
    },
    mounted() {
      this.$nextTick(()=>{
          $.ajax({
              url: '/js/tagsinput.js',
              dataType:'script',
              cache:true,
          })
      })
    },
    methods: {
        loadData() {
            if (this.form.type === 'Options') {
                $('#options').css({display: 'block'})
            } else {
                $('#options').css({display: 'none'})
                this.form.values = ''
            }
        },
        updateField() {
            this.form.values = ('#values').val()
            this.form.put(this.$route('categories.update',this.form.id),{
                preserveState:true,
                preserveScroll:true,
                onSuccess: ()=> {
                    this.form.reset()
                    $('#fields').modal('hide')
                    Swal.fire(
                        'Field Updated!',
                        'Field was successfully updated',
                        'success'
                    )
                }
            })
        },

        createField() {

            this.form.values = $('#values')?$('#values').val():''
            this.form.category = this.category.id

            this.form.post(this.$route('categories.store'),{
                preserveState:true,
                preserveScroll:true,
                onSuccess: ()=> {
                    this.form.reset()
                    $('#fields').modal('hide')
                    this.form.values = ''
                    $('#values').val('')
                    Swal.fire(
                        'Field Added!',
                        'Field was successfully added',
                        'success'
                    )
                }
            })
        },


        addField() {
            this.form.reset()
            this.editMode = false
        },
        editFields(field) {
            this.form.id = field.id
            this.form.name = field.name
            this.form.type = field.type
            this.form.values = field.value
            if (field.type === 'Options') {
                $('#options').css({display: 'block'})
                $('#values').val(field.value)
            }
            this.form.editMode = true
        },

        deleteFields(p,i) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete field!'
            }).then((result) => {
                if (result.value) {
                    this.$inertia.delete(this.$route('categories.destroy'),{
                        preserveState:true,
                        preserveScroll:true,
                        onSuccess:()=>{
                            Swal.fire(
                                'Deleted!',
                                'Field has been deleted',
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
@import url('/css/tagsinput.css');
</style>
