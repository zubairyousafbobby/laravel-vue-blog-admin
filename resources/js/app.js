require('./bootstrap');

window.Vue = require('vue').default;
import router from './router';
import store from './store';
import ViewUI from 'view-design';
import 'view-design/dist/styles/iview.css';
Vue.use(ViewUI);
import common from './common'
import jsonToHtml from './jsonToHtml'
Vue.mixin(common)
Vue.mixin(jsonToHtml)
// import Editor from 'vue-editor-js/src/index' 
// Vue.use(Editor)
import Editor from 'vue-editor-js'
Vue.use(Editor)


Vue.component('main-component', require('./components/mainapp.vue').default);

const app = new Vue({
    el: '#app',
    router,
    store
});
