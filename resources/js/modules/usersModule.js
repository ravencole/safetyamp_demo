import router from '../router'
import { UserService } from '../api'

export default {
  namespaced: true,
  getters: {
    employees: state => state.users.filter(u => u.role == 'employee'),
    supervisors: state => state.users.filter(u => u.role == 'supervisor'),
    userIsSupervisor: state => state.user.role == 'supervisor',
    userIsEmployee: state => state.user.role == 'employee'
  },
  state: {
    user: {},
    users: [],
    userIsSignedIn: false,
    token: '',
  },
  mutations: {
    setUser(state, user) {
      state.user = user
      state.userIsAdmin = user.is_admin
      state.userIsTuner = user.is_tuner
    },
    signInUser(state) {
      state.userIsSignedIn = true;
    },
    setToken(state, token) {
      state.token = token
    },
    logoutUser(state) {
      state.userIsSignedIn = false
      state.token = ''
      state.user = {}
    },
    setAllUsers(state, users) {
      state.users = users
    }
  },
  actions: {
    getUser(context) {
      UserService.getUser()
        .then(user => {
          context.commit('setUser', user)
          router.push('/home')
        })
        .catch(err => {
          console.log(err.response)
        })
    },
    logout(context) {
      localStorage.removeItem(process.env.MIX_AUTH_TOKEN_LOCALSTORAGE_KEY)
      localStorage.removeItem('vuex')
      context.commit('logoutUser')
      router.push('/login')
    },
    getAllUsers(context) {
      UserService.getAllUsers()
        .then(users => {
          context.commit('setAllUsers', users)
        })
    },
    loginUser(context, user) {
      UserService.loginUser(user)
        .then(res => {
          context.commit('setToken', res.access_token)
          context.commit('signInUser')
          this.dispatch('usersModule/getUser')
        })
        .catch(err => {
          console.log(err)
        })
    }
  }
}
