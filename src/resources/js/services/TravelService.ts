import axios from 'axios'

export interface TravelForm {
    destination: string
    departure_date: string
    return_date: string
    status?: string
}

export default {
    async listar(params: Record<string, string>) {
        const { data } = await axios.get('/viagens', { params })
        return data || []
    },

    async criar(dados: TravelForm) {
        const { data } = await axios.post('/viagens', dados)
        return data
    },

    async atualizar(id: number, dados: TravelForm) {
        const { data } = await axios.put(`/viagens/${id}`, dados)
        return data
    },

    async deletar(id: number) {
        const { data } = await axios.delete(`/viagens/${id}`)
        return data
    }
}
