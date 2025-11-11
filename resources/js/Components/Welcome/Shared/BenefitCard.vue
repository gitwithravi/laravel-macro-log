<script setup>
defineProps({
    title: {
        type: String,
        required: true,
    },
    description: {
        type: String,
        required: true,
    },
    iconBgGradient: {
        type: String,
        default: 'from-green-100 to-emerald-100',
    },
    iconColor: {
        type: String,
        default: 'text-green-600',
    },
    hoverTextColor: {
        type: String,
        default: 'group-hover:text-green-600',
    },
    animationDelay: {
        type: String,
        default: '',
    },
});
</script>

<template>
    <div
        class="text-center scroll-animate group"
        :style="animationDelay ? `animation-delay: ${animationDelay};` : ''"
    >
        <div
            class="w-16 h-16 bg-gradient-to-br rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 group-hover:rotate-6 transition-all duration-300 group-hover:shadow-lg"
            :class="iconBgGradient"
        >
            <svg
                class="w-8 h-8"
                :class="iconColor"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <slot name="icon-path"></slot>
            </svg>
        </div>
        <h3
            class="text-xl font-semibold text-gray-900 mb-3 transition-colors"
            :class="hoverTextColor"
        >
            {{ title }}
        </h3>
        <p class="text-gray-600">
            {{ description }}
        </p>
    </div>
</template>
