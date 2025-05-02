import axios from 'axios'

axios.defaults.withCredentials = true
axios.defaults.baseURL = 'http://localhost:8000' // ajuste conforme seu ambiente

export default {
    getCsrfCookie() {
        return axios.get('/sanctum/csrf-cookie')
    },

    login(email: string, password: string) {
        return axios.post('/login', { email, password })
    },

    logout() {
        return axios.post('/logout')
    },

    getUser() {
        return axios.get('/user')
    }
}
