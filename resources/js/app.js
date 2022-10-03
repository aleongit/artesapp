//import Vue from 'vue';
import { createApp } from 'vue'; //vue3
//import * as Vue from 'vue';

//component pare
import App from './App.vue';

//router
import router from './router';

//store
import store from './store';

//estils
import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap';
import '../css/app.css';

//vue flash message
import FlashMessage from '@smartweb/vue-flash-message';

/*
new Vue({
    el: '#app',
    router,
    render: h => h(App),
});
*/

//creem app en vue3
const app = createApp(App);

//usem router amb rutes per vue3
app.use(router);

//usar store per vue3 i vuex4
app.use(store);

//usem flash messager per vue3
app.use(FlashMessage);

//muntem
app.mount('#app');


