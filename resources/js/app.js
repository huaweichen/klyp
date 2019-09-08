import Vue from 'vue';
import App from './components/app'

require('./bootstrap');

const app = new Vue({
    el: '#app',
    components: {
        App
    }
});
