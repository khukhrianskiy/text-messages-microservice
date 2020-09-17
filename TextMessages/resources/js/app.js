require('./bootstrap');

import Vue from 'vue'
import LogIndex from './components/log/LogIndex'

Vue.use(require('vue-moment'));

new Vue({
    el: '#app',
    template: '<LogIndex />',
    components: { LogIndex }
})
