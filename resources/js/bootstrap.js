import axios from 'axios';
import * as Bootstrap from 'bootstrap';

window.axios = axios;
window.bootstrap = Bootstrap;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
