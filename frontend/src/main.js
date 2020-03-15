import Vue from 'vue'
import App from './App.vue'
import vuetify from './plugins/vuetify';
import "vue-toastification/dist/index.css";
import Toast from "vue-toastification";
import store from './store/index';

Vue.use(Toast);
Vue.config.productionTip = false;
Vue.config.devtools = true;

new Vue({
    store,
    vuetify,
    render: h => h(App)
}).$mount('#app');
