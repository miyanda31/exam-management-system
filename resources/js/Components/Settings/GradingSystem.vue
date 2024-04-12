<template>
    <Layout>
        <Head><title>Eduket | Grading</title></Head>
        <page-header current-page="Manage Grading System" page="Grading"/>
        <div class="card-box pd-20 height-100-p mb-30 col-md-8 ml-auto mr-auto">
            <h5 class="h5">
                Manage Grading System
            </h5>
            <div class="table-responsive">
                <table class="table table-sm table-bordered">
                    <tbody>
                    <tr>
                        <th>Min</th>
                        <th>Max</th>
                        <th>Grade</th>
                        <th>Remark</th>
                    </tr>
                    <tr style="cursor: pointer" v-for="(grading,index) in gradings">
                        <td :id="'min'+index" @click="editSystem('min',grading.id,index)">{{grading.min }}</td>
                        <td :id="'max'+index" @click="editSystem('max',grading.id,index)">{{ grading.max}}</td>
                        <td >{{grading.grade}}</td>
                        <td :id="'remark'+index" @click="editSystem('remark',grading.id,index)">{{grading.remark}}</td>

                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <input @blur="removeEditor" id="edit" @keydown.enter.tab="updateSystem" type="text" placeholder="Update...">
    </Layout>
</template>

<script>
export default {
    name: "GradingSystem",
    props: {
        gradings: [],
    },
    data() {
        return {
            id:'',
            type:'',
            cell:'',
            filteredSystem: [],
            load:false
        }
    },
    methods: {
        editSystem(type,id,index){
            let grade =  $('#edit')
            let sel = $('#' + type+index);

            this.type = type
            this. id = id
            this.cell = type+index


            grade.css({visibility:'visible'})

            grade.val(sel.text().trim())
            grade.height(sel.innerHeight())
            grade.width(sel.innerWidth())

            grade.appendTo(sel)
            grade.select()
            grade.focus()
        },
        removeEditor () {
            let grade =  $('#edit')
            grade.css({
                visibility:'hidden'
            })
        },

        updateSystem() {
            let grade =  $('#edit')
            let v = $('#' + this.cell)
            if (grade.val().trim() !== '' && grade.val() !== v.text()) {

                this.$inertia.put(this.$route('grading.update',this.id),{
                    t :this.type,
                    v :grade.val()
                },{
                    preserveState:true,
                    preserveScroll:true,
                    onSuccess:()=>{
                        grade.css({
                            visibility:'hidden'
                        })
                    }
                })

            }


        }
    },
}
</script>

<style scoped>

td {
    position: relative;
}

#edit,#add {
    left:0;
    top:0;
    padding:0;
    margin:0;
    border: 0;
    color: #67757c;
    position:absolute;
    visibility:hidden
}
</style>
