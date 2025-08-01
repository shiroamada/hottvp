import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

import moment from 'moment';
window.moment = moment;

import toastr from 'toastr';
window.toastr = toastr;