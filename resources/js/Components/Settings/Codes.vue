<template>
    <Layout>
        <Head><title>Eduket | Codes</title></Head>
        <page-header current-page="Manage Codes" page="Codes"/>
        <div class="card-box pd-20 height-100-p mb-30">
            <h5 class="panel-title">
                Registration Codes Management
                <button @click="addCodes" data-toggle="modal" data-target="#exampleModal" class="btn btn-md btn-sm btn-primary pull-right">
                    <span class="fa fa-plus"></span>
                </button>

            </h5>
            <pagination :previous-pages="previousPages" :next-pages="nextPages" :data="codes" >
                <label>
                    <select @change="loadCategory" v-model="user" class="form-control">
                        <option value="" disabled>Select User</option>
                            <option>Teacher</option>
                            <option>Admin</option>
                    </select>
                </label>

            </pagination>
            <div class="table-wrap">
                <table  v-if="codes.total>0" class="table table-bordered table-hover table-sm">
                    <tbody>
                    <tr>
                        <th class="hidden-xs">#</th>
                        <th>Code</th>
                        <th>User Type</th>
                        <th>User</th>
                    </tr>
                    <tr v-for="(code,i) in codes.data">
                        <td class="hidden-xs">{{i+1}}</td>
                        <td>{{code.code}}</td>
                        <td>{{code.user.type}}</td>
                        <td>{{code.user.fname+' '+code.user.lname}}</td>
                    </tr>
                    </tbody>
                </table>

                <empty :category="codes" message="No codes have been generated at this point. Select add then generate for user type you want"></empty>
            </div>
        </div>
        <Modal id="exampleModal" title="Create Codes" :submit="createCodes">
            <template #body>
                  <div class="form-group">
                      <select v-model="form.user" class="form-control">
                          <option value="" disabled>Select User</option>
                              <option>Teacher</option>
                              <option>Admin</option>
                      </select>
                  </div>
            </template>
            <template #footer>
                <button type="submit"  class="btn btn-primary">Create</button>
            </template>
        </Modal>
    </Layout>
</template>

<script>
export default {
    name: "Codes",
    props: {
        classes: [],
        codes: {},
    },
    data() {
        return {
            form:this.$inertia.form({
                user:'',
            }),
            user:''
        }
    },
    methods: {
        addCodes() {
            this.form.reset()
        },
        createCodes() {
            this.form.post(this.$route('codes.store'),{
                preserveState:true,
                preserveScroll:true,
                onError:()=>{
                    Swal.fire(
                        'Warning!',
                        'No codes were generated as there were no users in selected category',
                        'info'
                    )
                    $('#exampleModal').modal('hide')
                },
                onSuccess: ()=> {

                    $('#exampleModal').modal('hide')
                    Swal.fire(
                        'Generated!',
                        'Codes have been successfully generated',
                        'success'
                    )
                }
            })
        },


        nextPages() {
            if (this.codes.next_page_url === null) {
                return;
            }

            this.$inertia.get(this.codes.next_page_url,{},{preserveState:true,preserveScroll:true})
        },

        previousPages() {
            if (this.codes.prev_page_url === null) {
                return;
            }

            this.$inertia.get(this.codes.prev_page_url,{},{preserveState:true,preserveScroll:true})
        },
        loadCategory() {
            this.$inertia.get(this.$route('settings.show','codes'),{'t':this.user},{preserveState:true,preserveScroll:true})
        },
    },
}
</script>

<style scoped>

</style>
