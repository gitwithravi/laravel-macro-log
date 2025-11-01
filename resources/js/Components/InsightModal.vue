<script setup>
import { ref, watch } from 'vue';

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    mealName: {
        type: String,
        required: true,
    },
    insight: {
        type: String,
        default: null,
    },
    loading: {
        type: Boolean,
        default: false,
    },
    error: {
        type: String,
        default: null,
    },
});

const emit = defineEmits(['close']);

const close = () => {
    emit('close');
};

// Close on escape key
const handleEscape = (e) => {
    if (e.key === 'Escape' && props.show) {
        close();
    }
};

watch(() => props.show, (newValue) => {
    if (newValue) {
        document.addEventListener('keydown', handleEscape);
        document.body.style.overflow = 'hidden';
    } else {
        document.removeEventListener('keydown', handleEscape);
        document.body.style.overflow = '';
    }
});
</script>

<template>
    <teleport to="body">
        <transition
            enter-active-class="transition-opacity duration-300"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition-opacity duration-300"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-if="show"
                class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50"
                @click.self="close"
            >
                <transition
                    enter-active-class="transition-all duration-300 ease-out"
                    enter-from-class="opacity-0 scale-95"
                    enter-to-class="opacity-100 scale-100"
                    leave-active-class="transition-all duration-200 ease-in"
                    leave-from-class="opacity-100 scale-100"
                    leave-to-class="opacity-0 scale-95"
                >
                    <div
                        v-if="show"
                        class="bg-white rounded-2xl shadow-xl max-w-lg w-full max-h-[80vh] overflow-hidden"
                    >
                        <!-- Header -->
                        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-4">
                            <div class="flex items-start justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-semibold text-white">Meal Insight</h3>
                                        <p class="text-sm text-indigo-100 mt-0.5">{{ mealName }}</p>
                                    </div>
                                </div>
                                <button
                                    @click="close"
                                    class="p-1 hover:bg-white/20 rounded-lg transition-colors"
                                >
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="p-6">
                            <!-- Loading State -->
                            <div v-if="loading" class="flex flex-col items-center justify-center py-8">
                                <div class="relative w-16 h-16 mb-4">
                                    <div class="absolute inset-0 border-4 border-indigo-200 rounded-full"></div>
                                    <div class="absolute inset-0 border-4 border-indigo-600 rounded-full border-t-transparent animate-spin"></div>
                                </div>
                                <p class="text-sm text-gray-600">Analyzing your meal...</p>
                            </div>

                            <!-- Error State -->
                            <div v-else-if="error" class="flex flex-col items-center py-8">
                                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mb-4">
                                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <p class="text-sm text-red-600 text-center">{{ error }}</p>
                            </div>

                            <!-- Insight Content -->
                            <div v-else-if="insight" class="space-y-4">
                                <div class="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-xl p-4 border border-indigo-100">
                                    <p class="text-gray-700 leading-relaxed">{{ insight }}</p>
                                </div>

                                <div class="flex items-center gap-2 text-xs text-gray-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>Generated by AI nutrition expert</span>
                                </div>
                            </div>

                            <!-- Empty State -->
                            <div v-else class="flex flex-col items-center py-8">
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                    </svg>
                                </div>
                                <p class="text-sm text-gray-500">No insight available</p>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="border-t border-gray-200 px-6 py-4">
                            <button
                                @click="close"
                                class="w-full px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-medium rounded-lg transition-all shadow-sm hover:shadow-md"
                            >
                                Close
                            </button>
                        </div>
                    </div>
                </transition>
            </div>
        </transition>
    </teleport>
</template>
