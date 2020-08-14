import jquery from 'jquery'
import Vue from 'vue'

import App from './App.vue'
import router from './router'
import store from './store'
import moment from 'moment'

import '../sass/app.scss'

Vue.filter('formatDate', function(value) {
  if (value) 
    return moment(String(value)).format('MM/DD/YYYY hh:mm')
});

new Vue({
  router,
  store,
  render: h => h(App)
}).$mount('#app')
