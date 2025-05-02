<template>
    <Link href="/" class="lg:hidden">
        <img class="dark:hidden" :src="onflyLogo" alt="Logo" />
        <img class="hidden dark:block" :src="onflyLogo" alt="Logo" />
    </Link>
</template>


<script setup>
import { Link } from '@inertiajs/vue3'
import onflyLogo from '@/assets/images/onfly.png'

</script>
