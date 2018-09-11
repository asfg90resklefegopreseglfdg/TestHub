
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');



/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('test-create', require('./components/Test/Create.vue'));
Vue.component('tests-list', require('./components/Test/List.vue'));
Vue.component('test-cover', require('./components/Test/Cover.vue'));
Vue.component('test-publish', require('./components/Test/Publish.vue'));
Vue.component('test-passing', require('./components/Test/Passing.vue'));
Vue.component('test-show-answers', require('./components/Test/ShowAnswers.vue'));
Vue.component('test-timer', require('./components/Test/Timer.vue'));

Vue.prototype.$eventBus = new Vue();

const app = new Vue({
    el: '#app',
    data: {
        counter: 0
    }
});




