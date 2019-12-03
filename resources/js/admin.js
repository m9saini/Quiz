/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
require('../../node_modules/admin-lte/plugins/jQuery/jquery-2.2.3.min.js');
require('../../node_modules/admin-lte/plugins/jQueryUI/jquery-ui.min.js');
//require('../../node_modules/admin-lte/plugins/jquery/jquery.min.js');
//require('../../node_modules/admin-lte/plugins/bootstrap/js/bootstrap.bundle.min.js');

require('../../node_modules/admin-lte/plugins/iCheck/icheck.min.js');
require('../../node_modules/jquery-validation/dist/jquery.validate.min.js');
require('../../node_modules/admin-lte/plugins/morris/morris.min.js');
require('../../node_modules/admin-lte/plugins/sparkline/jquery.sparkline.min.js');
//jvectormap -->
require('../../node_modules/admin-lte/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js');
require('../../node_modules/admin-lte/plugins/jvectormap/jquery-jvectormap-world-mill-en.js');
//Knob
require('../../node_modules/admin-lte/plugins/knob/jquery.knob.js');
//Bootstrap WYSIHTML5
//require('../../node_modules/admin-lte/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js');
$.widget.bridge('uibutton', $.ui.button);
//datepicker
//require('../../node_modules/admin-lte/plugins/daterangepicker/daterangepicker.js');
require('../../node_modules/admin-lte/plugins/datepicker/bootstrap-datepicker.js');

//DataTables -->
require('../../node_modules/admin-lte/plugins/jQuery/jquery-2.2.3.min.js');
require('../../node_modules/admin-lte/plugins/datatables/jquery.dataTables.min.js');
require('../../node_modules/admin-lte/plugins/datatables/dataTables.bootstrap.min.js');
//Choosen
require('../../node_modules/chosen-js/chosen.jquery.js');

//Select2 -->
require('../../node_modules/admin-lte/plugins/select2/select2.full.min.js');

//Slimscroll -->
require('../../node_modules/admin-lte/plugins/slimScroll/jquery.slimscroll.min.js');
//FastClick -->
require('../../node_modules/admin-lte/plugins/fastclick/fastclick.js');

// ChartJS 1.0.1 -->
//require('../../node_modules/admin-lte/plugins/chartjs/Chart.min.js');


//Lobibox -->
//require('../../node_modules/lobibox/lib/jquery.1.11.min.js');
//require('../../node_modules/lobibox/js/lobibox.js');

//Coloerbox
require('../../node_modules/jquery-colorbox/jquery.colorbox.js');


require('../../node_modules/admin-lte/dist/js/app.min.js');
require('../../node_modules/admin-lte/dist/js/pages/dashboard.js');
//require('../../node_modules/admin-lte/dist/js/pages/dashboard2.js');
require('../../node_modules/admin-lte/dist/js/demo.js');
require('../js/custom.js');


//window.Vue = require('vue'); //disabled by ND

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

//Vue.component('example-component', require('./components/ExampleComponent.vue').default); //disabled by ND

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

/* //disabled by ND
const app = new Vue({
    el: '#app',
});
*/
