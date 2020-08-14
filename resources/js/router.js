import Vue from 'vue'
import Router from 'vue-router'
import Home from './views/Home.vue'
import Login from './views/Login.vue'
import JHAs from './views/JHAs.vue'
import JHAsShow from './views/JHAsShow.vue'
import JHAsEdit from './views/JHAsEdit.vue'
import JHACreate from './views/JHACreate.vue'
import store from './store'

Vue.use(Router)

export default new Router({
  routes: [
    {
      path: '/home',
      name: 'home',
      component: Home
    },
    {
      path: '/jhas',
      name: 'jhas',
      component: JHAs
    },
    {
      path: '/jhas/:jha/edit',
      name: 'jhasEdit',
      component: JHAsEdit
    },
    {
      path: '/jhas/create',
      name: 'jhaCreate',
      component: JHACreate
    },
    {
      path: '/jhas/:jha',
      name: 'jhasShow',
      component: JHAsShow
    },
    {
      path: '/login',
      name: 'login',
      component: Login
    }
  ]
})
