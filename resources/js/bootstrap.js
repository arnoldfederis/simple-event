import Vue from 'vue'
import Axios from 'axios'
import VueRouter from 'vue-router'
import _ from 'lodash'
import PopperJs from 'popper.js';
import Form from './utils/Form';
import BootstrapVue from 'bootstrap-vue';
import 'bootstrap-vue/dist/bootstrap-vue.css'

window.Vue = Vue;
window.axios = Axios;
window.VueRouter = VueRouter;
window._ = _;
window.PopperJs = PopperJs;
window.Form = Form;

Vue.use(VueRouter);
Vue.use(BootstrapVue)

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
