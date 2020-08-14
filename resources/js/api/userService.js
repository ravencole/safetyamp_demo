import http from './api'

export default {
  loginUser(user) {
    return new Promise((resolve, reject) => {
      http.post(`${process.env.MIX_APP_URL}/oauth/token`, {
        grant_type: 'password',
          client_id: `${process.env.MIX_BACKEND_CLIENT_ID}`,
          client_secret: `${process.env.MIX_BACKEND_CLIENT_KEY}`,
          username: user.email,
          password: 'password'
      })
        .then(res => {
          const token = 'Bearer ' + res.data.access_token

          localStorage.setItem(`${process.env.MIX_AUTH_TOKEN_LOCALSTORAGE_KEY}`, 'Bearer ' + res.data.access_token)

          resolve(true)
        })
        .catch(err => {
          reject(err)
        })
    })
  },
  getAllUsers() {
    return new Promise((resolve, reject) => {
      http.get('users')
        .then(res => {
          resolve(res.data.data)
        })
        .catch(err => {
          reject(err)
        })
    })
  },
  getUser() {
    return new Promise((resolve, reject) => {
      http.get('me')
        .then(res => {
          resolve(res.data.data)
        })
        .catch(err => {
          reject(err)
        })
    })
  }
}
