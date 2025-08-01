import './bootstrap';
import 'moment';
import 'bootstrap-daterangepicker';
import '../metronic/core/index';
import '@keenthemes/ktui/src/index';
import 'bootstrap/dist/js/bootstrap.bundle.min.js';
import '../metronic/dist/assets/js/core.bundle.js';

import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

// Global toastr configuration
import toastr from 'toastr';
window.toastr = toastr;
toastr.options = {
    "closeButton": true,
    "debug": false,
    "newestOnTop": false,
    "progressBar": true,
    "positionClass": "toast-top-right",
    "preventDuplicates": false,
    "onclick": null,
    "showDuration": "300",
    "hideDuration": "1000",
    "timeOut": "5000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
};

// Import custom scripts AFTER Metronic core is initialized and its init functions are called
import './pages/license-list.js';