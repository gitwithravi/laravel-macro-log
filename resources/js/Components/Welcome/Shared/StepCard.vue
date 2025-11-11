<script setup>
defineProps({
    stepNumber: {
        type: Number,
        required: true,
    },
    title: {
        type: String,
        required: true,
    },
    description: {
        type: String,
        required: true,
    },
    showConnector: {
        type: Boolean,
        default: false,
    },
    animationDelay: {
        type: String,
        default: '',
    },
});
</script>

<template>
    <div
        class="relative scroll-animate"
        :style="animationDelay ? `animation-delay: ${animationDelay};` : ''"
    >
        <div class="bg-white rounded-2xl p-8 shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
            <div class="w-12 h-12 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-xl flex items-center justify-center text-white font-bold text-xl mb-6 animate-gradient">
                {{ stepNumber }}
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-3">{{ title }}</h3>
            <p class="text-gray-600 mb-6">
                {{ description }}
            </p>
            <!-- Mockup -->
            <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-lg p-4 aspect-[4/3] flex items-center justify-center">
                <div class="text-center text-gray-400 text-sm">
                    <slot name="icon">
                        <svg class="w-12 h-12 mx-auto mb-2" fill="currentColor" viewBox="0 0 20 20">
                            <slot name="icon-path"></slot>
                        </svg>
                    </slot>
                    <slot name="mockup-label"></slot>
                </div>
            </div>
        </div>
        <!-- Connector Arrow (hidden on mobile) -->
        <div v-if="showConnector" class="hidden md:block absolute top-1/2 -right-4 transform -translate-y-1/2 z-10">
            <svg class="w-8 h-8 text-indigo-400 animate-pulse" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
            </svg>
        </div>
    </div>
</template>
