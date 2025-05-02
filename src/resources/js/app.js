import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'
import { createPinia } from 'pinia'
import Toast from 'vue-toastification'
import 'vue-toastification/dist/index.css'
import '../css/app.css'

import LayoutWrapper from '../js/Pages/App.vue'

createInertiaApp({
    resolve: async name => {
        const page = await resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue'))
        page.default.layout = Object.hasOwn(page.default, 'layout') ? page.default.layout : LayoutWrapper
        return page
    },
    setup({ el, App, props, plugin }) {
        const vueApp = createApp({ render: () => h(App, props) })
        vueApp.use(plugin)
        vueApp.use(createPinia())
        vueApp.use(Toast, {
            position: 'top-right',
            transition: 'Vue-Toastification__fade',
            maxToasts: 5,
            newestOnTop: true,
            toastClassName: 'z-[9999]', // ⬅️ Isso aqui resolve
        })
        vueApp.mount(el)
    },
})
