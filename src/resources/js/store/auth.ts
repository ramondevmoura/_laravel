// No seu composable store/auth.js ou auth.ts (dependendo do seu setup)
import { defineStore } from 'pinia'
import { ref } from 'vue'
import AuthService from '../services/AuthService'
import { router } from '@inertiajs/vue3'

export const useAuthStore = defineStore('auth', () => {
    const user = ref(null)
    const loading = ref(false)

    async function fetchUser() {
        try {
            const response = await AuthService.getUser()
            user.value = response.data
        } catch {
            user.value = null
        }
    }

    async function login(email, password) {
        loading.value = true
        try {
            await AuthService.getCsrfCookie()
            const response = await AuthService.login(email, password)
            await fetchUser()
            return response.data

        } catch (error) {
            console.error('Erro ao fazer login:', error)
            throw error
        } finally {
            loading.value = false
        }
    }

    async function logout() {
        await AuthService.logout()
        user.value = null
        router.visit('/')
    }

    return { user, loading, fetchUser, login, logout }
})
