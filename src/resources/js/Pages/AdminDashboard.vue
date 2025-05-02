<template>
    <div class="p-6 space-y-4">
        <h1 class="text-2xl font-bold text-green-700">Usuário autenticado EMPRESA PARCEIRA!</h1>

        <button
            class="bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded transition"
            @click="handleLogout"
        >
            Sair
        </button>
    </div>
</template>

<script setup>
import { useAuthStore } from '../store/auth.ts'
import { router } from '@inertiajs/vue3'
import { useToast } from 'vue-toastification'

const auth = useAuthStore()
const toast = useToast()

async function handleLogout() {
    try {
        await auth.logout()
        toast.success('Logout realizado com sucesso')
        router.visit('/')
    } catch (error) {
        toast.error('Erro ao realizar logout')
        console.error(error)
    }
}
</script>
