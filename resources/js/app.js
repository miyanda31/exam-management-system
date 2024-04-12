import Vue from 'vue'
import {createInertiaApp, Head, Link} from '@inertiajs/inertia-vue'

import { InertiaProgress } from '@inertiajs/progress'
import Layout from "./Components/Layout";
import Empty from "./Components/Empty";
import PageHeader from "./Components/PageHeader";
import Length from "./Components/Length";
import Swal from "sweetalert2";
import {Datetime} from "vue-datetime";
import Alert from "./Components/Alert";
import Modal from "./Components/Modal";
import SimplePagination from "./Components/SimplePagination";

import VueDatePicker from '@mathieustan/vue-datepicker';

Vue.use(VueDatePicker);

Vue.prototype.$route = route

window.axios = require('axios')
// window.$ = window.jQuery
window.Swal = Swal
Vue.component('Layout',Layout)
Vue.component('empty',Empty)
Vue.component('PageHeader',PageHeader)
Vue.component('Length',Length)
Vue.component('Multiselect',window.VueMultiselect.default)
Vue.component('datetime',Datetime)
Vue.component('Alert',Alert)
Vue.component('Modal',Modal)
Vue.component('Pagination',SimplePagination)

Vue.use(VueChartkick)

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

Vue.filter('daysToGo',function(created){
    return moment(created).endOf('days').fromNow();
});

Vue.filter('fullTime',function(created){
   return  $.fullCalendar.formatDate(created,'DD MMMM YYYY');
});

Vue.filter('myDate',function(created){
    return moment(created).format('Do MMMM YYYY')
});

Vue.filter('times',function(created){
    return moment(new Date().toDateString()+' '+ created).parseZone().format('HH:mm')
});



InertiaProgress.init()

createInertiaApp({
    resolve: name => require(`./Components/${name}`),
    setup({ el, App, props, plugin }) {
        Vue.use(plugin)
            .component('Link',Link)
            .component('Head',Head)

        new Vue({
            render: h => h(App, props),
        }).$mount(el)
    },

})
