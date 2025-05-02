import { defineStore } from 'pinia'

export const useNotificationStore = defineStore('notificationStore', {
    state: () => ({
        list: [],
        unread: false
    }),
    actions: {
        add(notification) {
            this.list.unshift(notification)
            this.unread = true
        },
        setList(notifications) {
            this.list = notifications
        },
        markAllAsRead() {
            this.unread = false
        },
        setUnread(value) {
            this.unread = value
        }
    }
})
