// src/socket.ts
import { io } from 'socket.io-client'

// Forçar apenas WebSocket (sem fallback para polling)
const socketWpp = io('http://localhost:6002', {
    transports: ['websocket'],
})

export default socketWpp
