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
    iconBgClass: {
        type: String,
        default: 'bg-indigo-100',
    },
    hoverIconBgClass: {
        type: String,
        default: 'group-hover:bg-indigo-600',
    },
    iconColorClass: {
        type: String,
        default: 'text-indigo-600',
    },
    hoverIconColorClass: {
        type: String,
        default: 'group-hover:text-white',
    },
    hoverBorderColor: {
        type: String,
        default: 'hover:border-indigo-200',
    },
    hoverTextColor: {
        type: String,
        default: 'group-hover:text-indigo-600',
    },
    animationDelay: {
        type: String,
        default: '',
    },
});
</script>

<template>
    <div
        class="group scroll-animate bg-white border border-gray-100 rounded-2xl p-6 hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 cursor-pointer"
        :class="hoverBorderColor"
        :style="animationDelay ? `animation-delay: ${animationDelay};` : ''"
    >
        <div
            class="w-12 h-12 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-all duration-300"
            :class="[iconBgClass, hoverIconBgClass]"
        >
            <slot name="icon">
                <svg
                    class="w-6 h-6 transition-colors duration-300"
                    :class="[iconColorClass, hoverIconColorClass]"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <slot name="icon-path"></slot>
                </svg>
            </slot>
        </div>
        <h3 class="text-xl font-semibold text-gray-900 mb-2 transition-colors" :class="hoverTextColor">
            {{ title }}
        </h3>
        <p class="text-gray-600">
            {{ description }}
        </p>
    </div>
</template>
