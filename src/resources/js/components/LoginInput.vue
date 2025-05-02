<template>
    <div class="flex flex-col gap-1 w-full max-w-md">
        <label
            :for="type"
            class="text-sm font-medium text-black-700 dark:text-black-800"
        >
            {{ label }}
        </label>
        <input
            :id="type"
            :type="type"
            v-model="modelValue"
            :placeholder="placeholder"
            :class="[
        'px-4 py-2 rounded-md border text-sm focus:outline-none focus:ring-2 transition-all',
        error
          ? 'border-red-500 focus:ring-red-400'
          : 'border-gray-300 focus:ring-blue-400',
      ]"
            @input="$emit('update:modelValue', modelValue)"
        />
        <p v-if="error" class="text-xs text-red-500">{{ error }}</p>
    </div>
</template>

<script setup lang="ts">
import { ref, defineProps, defineEmits, watch } from 'vue'

const props = defineProps<{
    modelValue: string
    placeholder?: string
    error?: string
    label: string
    type?: string
}>()

const emit = defineEmits(['update:modelValue'])
const modelValue = ref(props.modelValue)

watch(
    () => props.modelValue,
    (val) => {
        modelValue.value = val
    }
)
</script>
