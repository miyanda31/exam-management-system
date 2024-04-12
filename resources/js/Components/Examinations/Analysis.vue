<template>
    <Layout>
        <Head><title>Eduket | Analysis</title></Head>
        <page-header current-page="Report Analysis" page="Analysis"/>
        <div class="row clearfix mb-30">
            <div class="col-md-8">
                <div class="card-box pd-20 mb-30">
                    <table class="table table-borderless table-sm">
                        <tbody>
                        <tr>
                            <th>
                                <h5> Class Performance Tracker</h5>
                            </th>
                            <td>

                                <label>
                                    <select v-model="form"  class="form-control form-control-sm">
                                        <option disabled value="">Form</option>
                                        <option v-for="c in classes" :value="c.id">{{c.name}}</option>
                                    </select>
                                </label>
                                <label>
                                    <select v-model="term"  class="form-control form-control-sm">
                                        <option disabled value="">Term</option>
                                        <option v-for="c in terms" :value="c.id">{{c.number}} - {{c.year}}</option>
                                    </select>
                                </label>
                                <button @click="loadClassPerformance" class="btn-primary btn btn-sm ml-2 mr-2">Load</button>
                            </td>
                            <td class="text-right">
                                <label><button data-toggle="modal" data-target="#exampleModal" class="btn-outline-success btn btn-sm pull-right ml-2 mr-2">Compare</button></label>

                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <div id="currentTermPerformance"></div>
                    <div id="currentTermPerformance2"></div>
                    <length :category="data" message="No data loaded, please make a selection"/>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card-box pd-20 mb-30">
                    <h5> Average Performance</h5>
                    <div id="currentAverageTermPerformance"></div>
                    <div id="currentAverageTermPerformance2"></div>
                    <length :category="data" message="No data loaded, please make a selection"/>
                </div>
            </div>
            <OrdinaryModal id="exampleModal" title="Compare Class Performance">
                <template #body>
                    <div class="form-group">
                        <select v-model="fo"  class="form-control form-control-sm">
                            <option disabled value="">Select Class</option>
                            <option v-for="c in classes" :value="c.id">{{c.name}}</option>
                        </select>

                    </div>
                    <div class="form-group">
                        <select v-model="first"  class="form-control form-control-sm">
                            <option disabled value="">Select Term</option>
                            <option v-for="c in terms" :value="c.id">{{c.number}} - {{c.year}}</option>
                        </select>

                    </div>
                    <div class="form-group">
                        <select v-model="second"  class="form-control form-control-sm">
                            <option disabled value="">Comparative Term</option>
                            <option v-for="c in terms" :value="c.id">{{c.number}} - {{c.year}}</option>
                        </select>

                    </div>
                </template>
                <template #footer>
                    <button @click="compareClassPerformance" type="button" class="btn btn-primary">Compare</button>
                </template>
            </OrdinaryModal>
        </div>
        <div class="row mb-30">
            <div class="col-md-8">
                <div class="card-box pd-20 height-100-p mb-30">
                    <h5 class="h5">Student Performance</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <multiselect @search-change="loadUser" placeholder="Search Student" v-model="user" :options="users" track-by="id" label="name" />
                        </div>
                        <div class="col-md-6">
                            <label>
                                <select v-model="start"  class="form-control form-control-sm">
                                    <option disabled value="">Term</option>
                                    <option v-for="y in terms" :value="y.id">T{{y.number}} - {{y.year}}</option>
                                </select>
                            </label>
                            <label>
                                <select  v-model="end"  class="form-control form-control-sm">
                                    <option disabled value="">Term</option>
                                    <option v-for="y in terms" :value="y.id">T{{y.number}} - {{y.year}}</option>
                                </select>
                            </label>
                            <button @click="compareStudentResults" class="btn-primary btn btn-sm ml-2 mr-2">Load</button>
                        </div>
                    </div>
                    <div id="compareStudentResults" ></div>
                    <length :category="data2" message="No data loaded, please make a selection"/>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card-box pd-20 mb-30">
                    <h5> Average Performance</h5>
                    <div id="compareAverageTermPerformance"></div>
                    <length :category="data2" message="No data loaded, please make a selection"/>
                </div>
            </div>
        </div>
    </Layout>
</template>

<script>
import OrdinaryModal from "../OrdinaryModal";
export default {
    name: "Analysis",
    props:{
        classes:[],
        terms:[],
    },
    components: {OrdinaryModal},
    data() {
        return {
            data: [],
            data2: [],
            user: '',
            users: [],
            term:'',
            form:'',
            fo:'',
            first:'',
            second:'',
            start:'',
            end:'',
        }
    },
    mounted() {
        this.$nextTick(()=>{
            $.ajax({
                url: '/js/apexcharts.min.js',
                dataType:'script',
                cache:true,
            })
        })
    },
    methods: {
        barGraphOptions(data, category) {
            return {
                chart: {
                    height: 350,
                    type: 'bar',
                    parentHeightOffset: 0,
                    fontFamily: 'Poppins, sans-serif',
                    toolbar: {
                        show: false,
                    },

                },
                colors: ['#1b00ff', '#f56767'],
                grid: {
                    borderColor: '#c7d2dd',
                    strokeDashArray: 5,
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '25%',
                        endingShape: 'rounded'
                    },
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    show: true,
                    width: 2,
                    colors: ['transparent']
                },
                series: data,
                xaxis: {
                    categories: category,
                    labels: {
                        style: {
                            colors: ['#353535'],
                            fontSize: '16px',
                        },
                    },
                    axisBorder: {
                        color: '#8fa6bc',
                    }
                },
                yaxis: {
                    title: {
                        text: ''
                    },
                    labels: {
                        style: {
                            colors: '#353535',
                            fontSize: '16px',
                        },
                    },
                    axisBorder: {
                        color: '#f00',
                    }
                },
                legend: {
                    horizontalAlign: 'right',
                    position: 'bottom',
                    fontSize: '16px',
                    offsetY: 0,
                    labels: {
                        colors: '#353535',
                    },
                    markers: {
                        width: 10,
                        height: 10,
                        radius: 15,
                    },
                    itemMargin: {
                        vertical: 0
                    },
                },
                fill: {
                    opacity: 1

                },
                tooltip: {
                    style: {
                        fontSize: '15px',
                        fontFamily: 'Poppins, sans-serif',
                    },
                    y: {
                        formatter: function (val) {
                            return val
                        }
                    }
                }
            };
        },
        radialChartOptions(data, title) {
            return {
                series: [data.average],
                labels: [title],
                chart: {
                    height: 350,
                    type: 'radialBar',
                    offsetY: 0
                },
                colors: ['#0B132B', '#222222'],
                plotOptions: {
                    radialBar: {
                        startAngle: -135,
                        endAngle: 135,
                        dataLabels: {
                            name: {
                                fontSize: '16px',
                                color: undefined,
                                offsetY: 120
                            },
                            value: {
                                offsetY: 76,
                                fontSize: '22px',
                                color: undefined,
                                formatter: function (val) {
                                    return val + "%";
                                }
                            }
                        }
                    }
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shade: 'dark',
                        shadeIntensity: 0.15,
                        inverseColors: false,
                        opacityFrom: 1,
                        opacityTo: 1,
                        stops: [0, 50, 65, 91]
                    },
                },
                stroke: {
                    dashArray: 4
                },
            };
        },

        donutChartOptions(data, titles) {
            return {
                series: data,
                labels: titles,
                chart: {
                    type: 'pie',
                    width: '100%',
                    legend: {
                        position: 'bottom'
                    }
                },
                responsive: [{
                    breakpoint: 480,
                    options: {
                        chart: {
                            width: 200
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                }]
            };
        },

        loadUser(query) {
            if (query) {
                axios.get(this.$route('analysis.show',query)).then(({data})=>{
                    this.users = data
                });
            }
        },

        loadCategory() {

        },

        loadClassPerformance() {
            if (this.form ==='' || this.term === '') {
                Swal.fire({
                    icon:'error',
                    title:'Please select term and class to compare',
                })
                return
            }
            $('#currentTermPerformance').show()
            $('#currentAverageTermPerformance').show()
            $('#currentTermPerformance2').hide()
            $('#currentAverageTermPerformance2').hide()
            this.data = []
            axios.get(this.$route('analysis.index', {'c': 1, 't': this.term, 'f': this.form}),).then(({data}) => {
                this.data = data
                var chart = new ApexCharts(document.querySelector("#currentTermPerformance"), this.barGraphOptions([data.data], data.subjects));
                chart.render();

                var chart2 = new ApexCharts(document.querySelector("#currentAverageTermPerformance"), this.radialChartOptions(data, 'Average Performance'));
                chart2.render();
            })
        },

        compareClassPerformance() {
            if (this.fo ==='' || this.first === '' || this.second === '') {
                Swal.fire({
                    icon:'error',
                    title:'Make sure terms to be compared and class are selected',
                })
                return
            }
            $('#currentTermPerformance').hide()
            $('#currentAverageTermPerformance').hide()
            $('#currentTermPerformance2').show()
            $('#currentAverageTermPerformance2').show()
            this.data = []
            axios.get(this.$route('analysis.index', {
                'p': 1,
                'f': this.fo,
                't2': this.first,
                't1': this.second
            }),).then(({data}) => {

                this.data = data

                var chart = new ApexCharts(document.querySelector("#currentTermPerformance2"), this.barGraphOptions(data.data, data.subjects));
                chart.render();


                var chart2 = new ApexCharts(document.querySelector("#currentAverageTermPerformance2"), this.donutChartOptions(data.average, ['Initial Term', 'Compared Term']));
                chart2.render();

                $('#exampleModal').modal('hide')
            })
        },


        compareStudentResults() {
            if (Object.keys(this.user).length === 0 || this.start === '' || this.end === '') {
                Swal.fire({
                    icon:'error',
                    title:'Make sure user and terms/class to be compared are selected',
                })
                return
            }
            this.data2 = []
            axios.get(this.$route('analysis.index', {'s': 1, 't1': this.start, 't2': this.end,'u':this.user.id}),).then(({data}) => {
                this.data2 = data
                var chart = new ApexCharts(document.querySelector("#compareStudentResults"), this.barGraphOptions(data.data, data.subjects));
                chart.render();

                var chart2 = new ApexCharts(document.querySelector("#compareAverageTermPerformance"), this.donutChartOptions(data.average, ['Initial Term', 'Compared Term']));
                chart2.render();
            })
        },

    }

    }
</script>

<style scoped>

</style>
