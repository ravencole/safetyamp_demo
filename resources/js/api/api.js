import axios from 'axios'

const fetchClient = () => {
  const defaultOptions = {
    baseURL: `${process.env.MIX_APP_URL}/${process.env.MIX_API_BASE}`,
    headers: {
      'Content-Type': 'application/json',
    },
  }

  let instance = axios.create(defaultOptions)

  instance.interceptors.request.use(function (config) {
    const token = localStorage.getItem(`${process.env.MIX_AUTH_TOKEN_LOCALSTORAGE_KEY}`)

    config.headers.Authorization =  token ? token : ''

    return config
  })

  return instance
};

export default fetchClient()
