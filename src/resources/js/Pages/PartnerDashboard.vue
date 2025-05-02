<script setup lang="ts">
import AppAdmin from './App.vue'
import socket from '../socket'
import { ref, onMounted, onUnmounted } from 'vue'


defineOptions({
    layout: AppAdmin
})

const mensagens = ref([])
const novaMensagem = ref('')

const enviarMensagem = async () => {
    if (novaMensagem.value.trim()) {
        const content = novaMensagem.value

        // Envia via WebSocket
        socket.emit('message', content)

        // Salva no banco
        await fetch('/mensagens', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            },
            body: JSON.stringify({ message: content })
        })

        novaMensagem.value = ''
    }
}

// Conecta ao servidor WebSocket e escuta mensagens
onMounted(async () => {
    const res = await fetch('/mensagens')
    const data = await res.json()
    mensagens.value = data.map((msg: any) => msg.content)

    socket.on('message', (mensagem) => {
        mensagens.value.push(mensagem)
    })
})

// Remove o listener ao desmontar o componente
onUnmounted(() => {
    socket.off('message')
})
</script>

<template>
    <div class="max-w-xl mx-auto mt-10 ">
        <h2 class="text-2xl font-bold mb-4">🗨️ Chat em Tempo Real</h2>

        <div class="border p-4 rounded h-64 overflow-y-auto mb-4 bg-white shadow">
            <div v-for="(msg, i) in mensagens" :key="i" class="mb-1">
                {{ msg }}
            </div>
        </div>

        <div class="flex gap-2">
            <input
                v-model="novaMensagem"
                @keyup.enter="enviarMensagem"
                class="border rounded p-2 flex-1"
                placeholder="Digite uma mensagem"
            />
            <button @click="enviarMensagem" class="bg-blue-600 text-white px-4 py-2 rounded">Enviar</button>
        </div>
    </div>

    <div class="p-6">
        <h1 class="text-2xl font-bold text-blue-700">Bem-vindo, parceiro!</h1>
        <p class="mt-2 text-gray-600">Você está logado como parceiro.</p>
    </div>
</template>
