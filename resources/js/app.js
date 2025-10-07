// =====================
// Bootstrap & Dependencies
// =====================

// Laravel's bootstrap.js (Axios, CSRF setup, etc.)
import './bootstrap';

// Import moment and date range picker
import 'moment';
import 'bootstrap-daterangepicker';

// Metronic core
import '../metronic/core/index';
import '@keenthemes/ktui/src/index';

// Import Bootstrap bundle into a variable (includes Popper)
// import * as bootstrap from 'bootstrap/dist/js/bootstrap.bundle.min.js';
// window.bootstrap = bootstrap; // make it globally available

// console.log(
//     'bootstrap.Modal is defined:',
//     typeof bootstrap.Modal !== 'undefined'
// );

// Metronic compiled core scripts
import '../metronic/dist/assets/js/core.bundle.js';

// =====================
// Alpine.js
// =====================
import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();

// =====================
// Toastr
// =====================
import toastr from 'toastr';
window.toastr = toastr;

toastr.options = {
    closeButton: true,
    debug: false,
    newestOnTop: false,
    progressBar: true,
    positionClass: 'toast-top-right',
    preventDuplicates: false,
    onclick: null,
    showDuration: '300',
    hideDuration: '1000',
    timeOut: '5000',
    extendedTimeOut: '1000',
    showEasing: 'swing',
    hideEasing: 'linear',
    showMethod: 'fadeIn',
    hideMethod: 'fadeOut',
};

// =====================
// Custom page scripts
// =====================
import './pages/license-list.js';

