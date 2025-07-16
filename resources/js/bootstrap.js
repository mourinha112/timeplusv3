import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

import './../../vendor/power-components/livewire-powergrid/dist/powergrid'
