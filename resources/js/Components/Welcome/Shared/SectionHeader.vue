<script setup>
defineProps({
    title: {
        type: String,
        required: true,
    },
    subtitle: {
        type: String,
        default: '',
    },
});
</script>

<template>
    <div class="text-center mb-16 scroll-animate">
        <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">
            {{ title }}
        </h2>
        <p v-if="subtitle" class="text-lg text-gray-600 max-w-2xl mx-auto">
            {{ subtitle }}
        </p>
    </div>
</template>
