// src/socket.ts
import { io } from 'socket.io-client'

// Forçar apenas WebSocket (sem fallback para polling)
const socket = io('http://localhost:6001', {
    transports: ['websocket'],
})

export default socket
