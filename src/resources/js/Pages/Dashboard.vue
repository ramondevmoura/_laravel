<script setup lang="ts">
import { ref, onMounted, watch } from 'vue'
import Datepicker from '@vuepic/vue-datepicker'
import '@vuepic/vue-datepicker/dist/main.css'
import { ptBR } from 'date-fns/locale'
import { format, parseISO } from 'date-fns'
import { Eye, Pencil, Trash2, X, PlusCircle, Loader2 } from 'lucide-vue-next'
import { useAuthStore } from '../store/auth'
import { useToast } from 'vue-toastification'
import TravelService from '../services/TravelService'
import Swal from 'sweetalert2'

const auth = useAuthStore()
const toast = useToast()

const viagens = ref<any[]>([])
const filtroStatus = ref('')
const filtroDestino = ref('')
const filtroDe = ref('')
const filtroAte = ref('')

const modalAberto = ref(false)
const modoEdicao = ref(false)
const viagemSelecionada = ref<any>(null)
const form = ref({ destination: '', departure_date: '', return_date: '', status: 'solicitado' })
const saving = ref(false)
const deletingId = ref<number | null>(null)
const carregando = ref(false)

const carregarViagens = async () => {
    carregando.value = true
    const params: Record<string, string> = {}
    if (filtroStatus.value) params.status = filtroStatus.value
    if (filtroDestino.value) params.destination = filtroDestino.value
    if (filtroDe.value) params.from = filtroDe.value
    if (filtroAte.value) params.to = filtroAte.value

    try {
        const response = await TravelService.listar(params)
        const todasViagens = response?.viagens || []

        viagens.value = auth.user?.role === 'admin'
            ? todasViagens
            : todasViagens.filter((v: any) => v.user_id === auth.user?.id)
    } finally {
        carregando.value = false
    }
}
onMounted(async () => {
    await auth.fetchUser()
    await carregarViagens()
})

watch([filtroStatus, filtroDestino, filtroDe, filtroAte], carregarViagens)

const formatarData = (dataISO: string) => {
    if (!dataISO) return ''
    return format(parseISO(dataISO), 'dd/MM/yyyy', { locale: ptBR })
}

const verViagem = (viagem: any) => {
    viagemSelecionada.value = viagem
    modalAberto.value = true
    modoEdicao.value = false
}

const abrirCriarModal = () => {
    viagemSelecionada.value = null
    form.value = { destination: '', departure_date: '', return_date: '', status: 'solicitado' }
    modalAberto.value = true
    modoEdicao.value = true
}

const editarViagem = (viagem: any) => {
    viagemSelecionada.value = viagem
    form.value = {
        destination: viagem.destination,
        departure_date: viagem.departure_date,
        return_date: viagem.return_date,
        status: viagem.status || 'solicitado'
    }
    modalAberto.value = true
    modoEdicao.value = true
}

const salvarViagem = async () => {
    saving.value = true
    try {
        if (viagemSelecionada.value) {
            await TravelService.atualizar(viagemSelecionada.value.id, form.value)
            toast.success('Viagem atualizada com sucesso')
        } else {
            await TravelService.criar(form.value)
            toast.success('Viagem criada com sucesso')
        }
        modalAberto.value = false
        await carregarViagens()
    } catch (err) {
        toast.error('Erro ao salvar viagem')
    } finally {
        saving.value = false
    }
}

const confirmarExclusao = async (id) => {
    const result = await Swal.fire({
        title: 'Confirmar exclusão?',
        text: 'Esta ação não poderá ser desfeita.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e3342f',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sim, apagar!',
        cancelButtonText: 'Cancelar'
    })

    if (result.isConfirmed) {
        await apagarViagem(id)
    }
}

const apagarViagem = async (id: number) => {
    if (confirm('Tem certeza que deseja apagar este pedido?')) {
        deletingId.value = id
        try {
            await TravelService.deletar(id)
            toast.success('Viagem apagada com sucesso')
            await carregarViagens()
        } catch (error) {
            toast.error('Erro ao apagar o pedido.')
        } finally {
            deletingId.value = null
        }
    }
}


</script>

<template>
    <div class="max-w-7xl mx-auto mt-10 text-gray-800 dark:text-gray-100">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Pedidos de Viagem</h2>
            <button @click="abrirCriarModal" class="flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                <PlusCircle class="w-5 h-5" /> Novo Pedido
            </button>
        </div>

        <div class="flex gap-4 mb-4 flex-wrap">
            <select v-model="filtroStatus" class="h-9 w-40 border rounded p-1 bg-white dark:bg-gray-800 dark:border-gray-600">
                <option value="">Todos os status</option>
                <option value="solicitado">Solicitado</option>
                <option value="aprovado">Aprovado</option>
                <option value="cancelado">Cancelado</option>
            </select>

            <input
                v-model="filtroDestino"
                type="text"
                placeholder="Filtrar por destino"
                class="h-9 w-60 border rounded p-2 bg-white dark:bg-gray-800 dark:border-gray-600"
            />

            <div class="w-48">
                <Datepicker
                    v-model="filtroDe"
                    :format="'dd-MM-yyyy'"
                    locale="pt-BR"
                    placeholder="Data de ida"
                    input-class="h-10 border rounded p-2 bg-white dark:bg-gray-800 dark:border-gray-600"
                />
            </div>

            <div class="w-48">
                <Datepicker
                    v-model="filtroAte"
                    :format="'dd-MM-yyyy'"
                    locale="pt-BR"
                    placeholder="Data de volta"
                    input-class="h-10 border rounded p-2 bg-white dark:bg-gray-800 dark:border-gray-600"
                />

            </div>

        </div>

        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:bg-gray-900 dark:border-gray-700">
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                    <tr class="bg-gray-100 text-gray-700 text-left dark:bg-gray-800 dark:text-gray-200">
                        <th class="px-6 py-3">ID</th>
                        <th class="px-6 py-3">Solicitante</th>
                        <th class="px-6 py-3">Destino</th>
                        <th class="px-6 py-3">Ida</th>
                        <th class="px-6 py-3">Volta</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3">Ações</th>
                    </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    <tr v-for="viagem in viagens" :key="viagem.id" class="hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                        <td class="px-6 py-4">{{ viagem.id || '---' }}</td>
                        <td class="px-6 py-4">{{ viagem.user?.name || '---' }}</td>
                        <td class="px-6 py-4">{{ viagem.destination }}</td>
                        <td class="px-6 py-4">{{ formatarData(viagem.departure_date) }}</td>
                        <td class="px-6 py-4">{{ formatarData(viagem.return_date) }}</td>
                        <td class="px-6 py-4">
                <span :class="{
                  'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300 px-2 py-1 rounded': viagem.status === 'solicitado',
                  'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300 px-2 py-1 rounded': viagem.status === 'aprovado',
                  'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300 px-2 py-1 rounded': viagem.status === 'cancelado'
                }">
                  {{ viagem.status }}
                </span>
                        </td>
                        <td class="px-6 py-4 flex gap-3">
                            <button @click="verViagem(viagem)" class="text-blue-500 hover:text-blue-700" title="Visualizar"><Eye class="w-5 h-5" /></button>
                            <button @click="editarViagem(viagem)" class="text-yellow-500 hover:text-yellow-700" title="Editar"><Pencil class="w-5 h-5" /></button>
                            <button @click="confirmarExclusao(viagem.id)" class="text-red-500 hover:text-red-700" title="Apagar">
                                <Loader2 v-if="deletingId === viagem.id" class="w-5 h-5 animate-spin" />
                                <Trash2 v-else class="w-5 h-5" />
                            </button>
                        </td>
                    </tr>
                    <tr v-if="carregando">
                        <td colspan="7" class="text-center py-6">
                            <Loader2 class="w-6 h-6 mx-auto animate-spin text-gray-600 dark:text-gray-300" />
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Carregando viagens...</p>
                        </td>
                    </tr>

                    <!-- Mensagem quando não há viagens e não está carregando -->
                    <tr v-else-if="viagens.length === 0">
                        <td colspan="7" class="text-center py-6 text-gray-500 dark:text-gray-400">
                            Nenhum pedido encontrado
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal de viagem -->
        <div v-if="modalAberto" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white dark:bg-gray-800 p-6 rounded shadow max-w-md w-full relative">
                <button @click="modalAberto = false" class="absolute top-3 right-3 text-gray-500 hover:text-gray-800 dark:text-gray-400 dark:hover:text-white">
                    <X class="w-5 h-5" />
                </button>
                <h3 class="text-lg font-bold mb-4">{{ modoEdicao ? 'Cadastro/Edição de Viagem' : 'Detalhes da Viagem' }}</h3>

                <div v-if="modoEdicao" class="space-y-4">
                    <input v-model="form.destination" placeholder="Destino" class="w-full border rounded p-2 bg-white dark:bg-gray-700 dark:border-gray-600" />
                    <Datepicker v-model="form.departure_date" placeholder="Data de Ida" :format="'yyyy-MM-dd'" locale="pt-BR" input-class="w-full border rounded p-2 bg-white dark:bg-gray-700 dark:border-gray-600" />
                    <Datepicker v-model="form.return_date" placeholder="Data de Volta" :format="'yyyy-MM-dd'" locale="pt-BR" input-class="w-full border rounded p-2 bg-white dark:bg-gray-700 dark:border-gray-600" />
                    <select v-if="auth.user?.role === 'admin'" v-model="form.status" class="w-full border rounded p-2 bg-white dark:bg-gray-700 dark:border-gray-600">
                        <option value="solicitado">Solicitado</option>
                        <option value="aprovado">Aprovado</option>
                        <option value="cancelado">Cancelado</option>
                    </select>
                    <button @click="salvarViagem" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded flex items-center justify-center gap-2">
                        <Loader2 v-if="saving" class="w-4 h-4 animate-spin" />
                        <span>{{ saving ? 'Salvando...' : 'Salvar' }}</span>
                    </button>
                </div>

                <div v-else>
                    <p><strong>Destino:</strong> {{ viagemSelecionada?.destination }}</p>
                    <p><strong>Status:</strong> {{ viagemSelecionada?.status }}</p>
                    <p><strong>Data de Ida:</strong> {{ formatarData(viagemSelecionada?.departure_date) }}</p>
                    <p><strong>Data de Volta:</strong> {{ formatarData(viagemSelecionada?.return_date) }}</p>
                </div>
            </div>
        </div>
    </div>
</template>


