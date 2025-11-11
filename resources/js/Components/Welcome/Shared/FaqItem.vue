<script setup>
defineProps({
    question: {
        type: String,
        required: true,
    },
    answer: {
        type: String,
        required: true,
    },
    animationDelay: {
        type: String,
        default: '',
    },
});
</script>

<template>
    <div
        class="scroll-animate bg-white rounded-2xl p-6 shadow-sm hover:shadow-lg transition-all duration-300 cursor-pointer border border-transparent hover:border-indigo-200"
        :style="animationDelay ? `animation-delay: ${animationDelay};` : ''"
    >
        <h3 class="text-lg font-semibold text-gray-900 mb-2">
            {{ question }}
        </h3>
        <p class="text-gray-600">
            {{ answer }}
        </p>
    </div>
</template>
