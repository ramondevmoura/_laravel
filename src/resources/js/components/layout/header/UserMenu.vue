<template>
  <div class="relative" ref="dropdownRef">
    <button
      class="flex items-center text-gray-700 dark:text-gray-400"
      @click.prevent="toggleDropdown"
    >
    <span class="mr-3 overflow-hidden rounded-full h-11 w-11 bg-gray-200 flex items-center justify-center">
      <svg class="w-6 h-6 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
        <path d="M10 10a4 4 0 100-8 4 4 0 000 8zm-7 8a7 7 0 0114 0H3z" />
      </svg>
    </span>

        <span class="block mr-1 font-medium">{{ auth.user?.name || 'Usuário' }}</span>

      <ChevronDownIcon :class="{ 'rotate-180': dropdownOpen }" />
    </button>

    <!-- Dropdown Start -->
    <div
      v-if="dropdownOpen"
      class="absolute right-0 mt-[17px] flex w-[260px] flex-col rounded-2xl border border-gray-200 bg-white p-3 shadow-theme-lg dark:border-gray-800 dark:bg-gray-dark"
    >
      <div>
       <span class="block font-medium text-gray-700 dark:text-gray-400">
          {{ auth.user?.name || 'Nome do usuário' }}
        </span>
                  <span class="mt-0.5 block text-theme-xs text-gray-500 dark:text-gray-400">
          {{ auth.user?.email || 'email@exemplo.com' }}
        </span>
      </div>

      <ul class="flex flex-col gap-1 pt-4 pb-3 border-b border-gray-200 dark:border-gray-800">
        <li v-for="item in menuItems" :key="item.href">
        </li>
      </ul>
        <Link
            href="/"
            method="post"
            as="button"
            @click.prevent="signOut"
            class="flex items-center gap-3 px-3 py-2 mt-3 font-medium text-gray-700 rounded-lg group  hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-gray-300"
        >
            <LogoutIcon
                class="text-gray-500 group-hover:text-gray-700 dark:group-hover:text-gray-300"
            />
            Sign out
        </Link>
    </div>
    <!-- Dropdown End -->
  </div>
</template>

<script setup>
import { UserCircleIcon, ChevronDownIcon, LogoutIcon, SettingsIcon, InfoCircleIcon } from '../../../icons'
import { Link } from '@inertiajs/vue3'
import { ref, onMounted, onUnmounted } from 'vue'
import { useAuthStore } from '../../../store/auth'
import { router } from '@inertiajs/vue3'
import { useToast } from 'vue-toastification'

const dropdownOpen = ref(false)
const dropdownRef = ref(null)
const auth = useAuthStore()
const toast = useToast()

const menuItems = [
  { href: '/profile', icon: UserCircleIcon, text: 'Edit profile' },
  { href: '/chat', icon: SettingsIcon, text: 'Account settings' },
  { href: '/profile', icon: InfoCircleIcon, text: 'Support' },
]

const toggleDropdown = () => {
  dropdownOpen.value = !dropdownOpen.value
}

const closeDropdown = () => {
  dropdownOpen.value = false
}

const signOut = async () => {
    try {
        await auth.logout()
        toast.success('Logout realizado com sucesso')
        router.visit('/')
    } catch (error) {
        toast.error('Erro ao realizar logout')
        console.error(error)
    }
}

const handleClickOutside = (event) => {
  if (dropdownRef.value && !dropdownRef.value.contains(event.target)) {
    closeDropdown()
  }
}

onMounted(() => {
  document.addEventListener('click', handleClickOutside)
})

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside)
})
</script>
