import router from '../router'
import { JHAsService } from '../api'

export default {
  namespaced: true,
  getters: {
  },
  state: {
    jhas: [],
    departments: [],
    focusedJHA: null
  },
  mutations: {
    addJHA(state, jha) {
      const jhas = state.jhas
      jhas.push(jha)
      state.jhas = jhas
    },
    setJHAs(state, jhas) {
      state.jhas = jhas
    },
    setFocusedJHA(state, jha) {
      state.focusedJHA = jha
    },
    updateJHA(state, updated) {
      const jhas = state.jhas

      jhas.map(j => {
        if(updated.id == j)
          return updated
        return j
      })

      state.jhas = jhas

      state.focusedJHA = updated
    },
    setDepartments(state, departments) {
      state.departments = departments
    },
    removeJHA(state, id) {
      const jhas = state.jhas

      jhas.reduce((a,j) => {
        if(id == j.id)
          return a
        a.push(j)
        return a
      }, [])

      state.jhas = jhas
    },
    unfocusJHA(state) {
      state.focusedJHA = null
    }
  },
  actions: {
    getAll(context) {
      JHAsService.getAll()
        .then(jhas => {
          context.commit('setJHAs', jhas)
        })
        .catch(err => {
          console.log(err.response)
        })
    },
    setFocusedJHA(context, id) {
      const jha = context.state.jhas.filter(j => j.id == id)

      if(jha.length)
        context.commit('setFocusedJHA', jha[0])
    },
    updateJHA(context, payload) {
      JHAsService.updateJHA(payload.id, payload.values)
        .then(jha => {
          context.commit('updateJHA', jha)
          alert('Update Successful')
        })
    },
    getDepartments(context) {
      JHAsService.getDepartments()
        .then(departments => {
          context.commit('setDepartments', departments)
        })
    },
    unfocusJHA(context) {
      context.commit('unfocusJHA') 
    },
    approveJHA(context, id) {
      JHAsService.approveJHA(id)
        .then(jha => {
          context.commit('updateJHA', jha)
        })
    },
    reviewJHA(context, id) {
      JHAsService.reviewJHA(id)
        .then(jha => {
          context.commit('updateJHA', jha)
        })
    },
    deleteJHA(context, id) {
      JHAsService.deleteJHA(id)
        .then(jha => {
          context.commit('removeJHA', id)
          if(context.state.focusedJHA.id == id) {
            context.commit('unfocusJHA')
            router.push('/jhas')
          }
        })
    },
    submitJHA(context, payload) {
      JHAsService.createJHA(payload)
        .then(jha => {
          context.commit('addJHA', jha)
          router.push(`/jha/${jha.id}`)
        })
    }
  }
}
