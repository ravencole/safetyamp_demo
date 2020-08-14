import http from './api'

export default {
  getAll() {
    return new Promise((resolve, reject) => {
      http.get(`jhas?orderBy=updated_at&sort=desc`)
        .then(res => {
          resolve(res.data.data)
        })
        .catch(err => {
          reject(err)
        })
    })
  },
  createJHA(payload) {
    return new Promise((resolve, reject) => {
      http.post(`/jhas`, payload)
        .then(res => {
          resolve(res.data.data)
        })
        .catch(err => {
          reject(err)
        })
    })
  },
  getDepartments() {
    return new Promise((resolve, reject) => {
      http.get(`departments`)
        .then(res => {
          resolve(res.data.data)
        })
        .catch(err => {
          reject(err)
        })
    })
  },
  updateJHA(id, payload) {
    return new Promise((resolve, reject) => {
      http.put(`jhas/${id}`, payload)
        .then(res => {
          resolve(res.data.data)
        })
        .catch(err => {
          reject(err)
        })
    })
  },
  approveJHA(id) {
    return new Promise((resolve, reject) => {
      http.post(`jhas/${id}/approve`)
        .then(res => {
          resolve(res.data.data)
        })
        .catch(err => {
          reject(err)
        })
    })
  },
  reviewJHA(id) {
    return new Promise((resolve, reject) => {
      http.post(`jhas/${id}/review`)
        .then(res => {
          resolve(res.data.data)
        })
        .catch(err => {
          reject(err)
        })
    })
  },
  deleteJHA(id) {
    return new Promise((resolve, reject) => {
      http.delete(`jhas/${id}`)
        .then(res => {
          resolve(true)
        })
        .catch(err => {
          reject(err)
        })
    })
  }
}
