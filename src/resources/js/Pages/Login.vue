<template>
    <FullScreenLayout>
        <div class="relative min-h-screen bg-white dark:bg-white flex flex-col lg:flex-row">
            <!-- Lado esquerdo - Formulário -->
            <div class="flex flex-col justify-center flex-1 px-4 py-8 sm:px-6 lg:px-12">
                <div class="w-full max-w-md mx-auto">
                    <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-800 mb-2">Entrar</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
                        Insira seu email e senha para acessar o painel.
                    </p>

                    <div class="space-y-4">
                        <LoginInput
                            v-model="email"
                            label="Email"
                            type="email"
                            placeholder="Digite seu e-mail"
                            :error="emailError"
                        />

                        <LoginInput
                            v-model="password"
                            label="Senha"
                            type="password"
                            placeholder="Digite sua senha"
                            :error="passwordError"
                        />

                        <button
                            class="w-full bg-blue-700 hover:bg-brand-600 text-white py-3 rounded-md font-semibold text-sm transition disabled:opacity-50 flex items-center justify-center gap-2"
                            @click="submit"
                            :disabled="isLoading"
                        >
                            <svg
                                v-if="isLoading"
                                class="animate-spin h-5 w-5 text-white"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                            >
                                <circle
                                    class="opacity-25"
                                    cx="12"
                                    cy="12"
                                    r="10"
                                    stroke="currentColor"
                                    stroke-width="4"
                                ></circle>
                                <path
                                    class="opacity-75"
                                    fill="currentColor"
                                    d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"
                                ></path>
                            </svg>
                            {{ isLoading ? 'Entrando...' : 'Entrar' }}
                        </button>
                    </div>
                </div>
            </div>

            <!-- Lado direito - Visual apenas em telas grandes -->
            <div class="hidden lg:flex items-center justify-center w-1/2 bg-gray-700 dark:bg-gray-700 p-8">
                <div class="text-center max-w-xs">
                    <img  :src="onflyLogo" alt="Logo" class="mb-4 w-32 mx-auto" />
                    <p class="text-gray-400 dark:text-white/60">
                        Fazendo o teste da Onfly e me divertindo no processo!
                    </p>
                </div>
            </div>
        </div>
    </FullScreenLayout>
</template>


<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import { useAuthStore } from '../store/auth.js'
import { useToast } from 'vue-toastification'
import LoginInput from '../components/LoginInput.vue'
import FullScreenLayout from '../components/FullScreenLayout.vue'
import onflyLogo from '@/assets/images/onfly.png'

const toast = useToast()
const auth = useAuthStore()

const email = ref('')
const password = ref('')
const emailError = ref('')
const passwordError = ref('')
const isLoading = ref(false)

defineOptions({
    layout: null
})
async function submit() {
    emailError.value = ''
    passwordError.value = ''
    let hasError = false

    if (!email.value.includes('@')) {
        emailError.value = 'Email inválido'
        hasError = true
    }

    if (password.value.length < 6) {
        passwordError.value = 'A senha deve ter pelo menos 6 caracteres'
        hasError = true
    }

    if (hasError) return

    isLoading.value = true

    try {
        const response = await auth.login(email.value, password.value)
        console.log(response)
        toast.success('Login realizado com sucesso!')
        if (response?.redirect) {
            router.visit(response.redirect)
        } else {
            router.visit('/')
        }
    } catch (err) {
        passwordError.value = 'Email ou senha incorretos'
        toast.error('Erro ao fazer login. Verifique seus dados.')
    } finally {
        isLoading.value = false
    }
}
</script>
