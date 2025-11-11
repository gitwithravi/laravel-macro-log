<script setup>
defineProps({
    imageSrc: {
        type: String,
        required: true,
    },
    imageAlt: {
        type: String,
        required: true,
    },
    badgeText: {
        type: String,
        required: true,
    },
    badgeGradient: {
        type: String,
        default: 'from-indigo-600 to-purple-600',
    },
    title: {
        type: String,
        required: true,
    },
    description: {
        type: String,
        required: true,
    },
    animationDelay: {
        type: String,
        default: '',
    },
    hoverRotation: {
        type: String,
        default: '-rotate-1',
    },
});
</script>

<template>
    <div
        class="scroll-animate group"
        :style="animationDelay ? `animation-delay: ${animationDelay};` : ''"
    >
        <div class="relative">
            <div class="relative mx-auto" style="max-width: 280px;">
                <img
                    :src="imageSrc"
                    :alt="imageAlt"
                    class="w-full h-auto rounded-2xl transform transition-all duration-500 group-hover:scale-105"
                    :class="`group-hover:${hoverRotation}`"
                    loading="lazy"
                />

                <!-- Floating badge -->
                <div
                    class="absolute -top-4 -right-4 bg-gradient-to-r text-white px-4 py-2 rounded-full text-sm font-semibold shadow-lg transform rotate-12 group-hover:rotate-0 transition-transform duration-300"
                    :class="badgeGradient"
                >
                    {{ badgeText }}
                </div>
            </div>
        </div>

        <!-- Description -->
        <div class="mt-8 text-center">
            <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ title }}</h3>
            <p class="text-gray-600 text-sm">
                {{ description }}
            </p>
        </div>
    </div>
</template>
