<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    canLogin: {
        type: Boolean,
        default: false,
    },
    canRegister: {
        type: Boolean,
        default: false,
    },
});
</script>

<template>
    <!-- Hero Section -->
    <section class="relative pt-24 pb-20 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-indigo-50 via-white to-purple-50 overflow-hidden">
        <!-- Animated background elements -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute top-20 left-10 w-72 h-72 bg-purple-300 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-pulse-slow"></div>
            <div class="absolute top-40 right-10 w-72 h-72 bg-indigo-300 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-pulse-slow" style="animation-delay: 1s;"></div>
            <div class="absolute -bottom-8 left-1/2 w-72 h-72 bg-pink-300 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-pulse-slow" style="animation-delay: 2s;"></div>
        </div>

        <div class="max-w-7xl mx-auto relative">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <!-- Hero Content -->
                <div class="text-center lg:text-left scroll-animate">
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-gray-900 leading-tight mb-6">
                        Smart Nutrition Tracking,
                        <span class="bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                            Powered by AI
                        </span>
                    </h1>
                    <p class="text-lg sm:text-xl text-gray-600 mb-8 max-w-2xl">
                        Track your meals effortlessly with natural language input.
                        No subscription fees—bring your own OpenAI API key and take control of your nutrition journey.
                    </p>

                    <!-- CTAs -->
                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        <Link
                            v-if="canRegister"
                            :href="route('register')"
                            class="group relative bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-8 py-4 rounded-lg font-semibold hover:from-indigo-700 hover:to-purple-700 transition-all shadow-lg hover:shadow-2xl hover:scale-105 text-center overflow-hidden"
                        >
                            <span class="relative z-10">Get Started Free</span>
                            <div class="absolute inset-0 bg-gradient-to-r from-purple-600 to-indigo-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        </Link>
                        <Link
                            v-if="canLogin && !$page.props.auth.user"
                            :href="route('login')"
                            class="bg-white text-gray-700 px-8 py-4 rounded-lg font-semibold border-2 border-gray-200 hover:border-indigo-400 hover:text-indigo-600 hover:bg-indigo-50 transition-all hover:scale-105 text-center"
                        >
                            Sign In
                        </Link>
                    </div>

                    <!-- Trust Indicators -->
                    <div class="mt-8 flex flex-wrap gap-6 justify-center lg:justify-start text-sm text-gray-600">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            No app download needed
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            No subscription required
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            Your data, your control
                        </div>
                    </div>
                </div>

                <!-- Hero Images - Asymmetric Layout -->
                <div class="relative scroll-animate hidden lg:block">
                    <div class="relative h-[550px] w-full max-w-[500px] mx-auto">
                        <!-- Dashboard Screenshot - Larger, positioned left with slight rotation -->
                        <div class="absolute top-0 left-0 z-10 transform hover:scale-105 transition-all duration-500 hover:z-20 -rotate-6 hover:rotate-0">
                            <img
                                src="/screens/dashboard.png"
                                alt="MacroLog Dashboard"
                                class="w-[300px] rounded-2xl"
                                loading="eager"
                            />
                        </div>

                        <!-- History Screenshot - Overlapping bottom-right with counter rotation -->
                        <div class="absolute top-24 right-0 z-20 transform hover:scale-105 transition-all duration-500 hover:z-30 rotate-6 hover:rotate-0">
                            <img
                                src="/screens/history.png"
                                alt="MacroLog History"
                                class="w-[280px] rounded-2xl"
                                loading="eager"
                            />
                        </div>

                        <!-- Decorative gradient blur - centered behind images -->
                        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-gradient-to-br from-indigo-200 via-purple-200 to-pink-200 rounded-full mix-blend-multiply filter blur-3xl opacity-40 animate-pulse-slow -z-10"></div>
                    </div>
                </div>

                <!-- Mobile/Tablet Fallback - Single Image -->
                <div class="relative scroll-animate lg:hidden">
                    <div class="relative mx-auto" style="max-width: 320px;">
                        <img
                            src="/screens/dashboard.png"
                            alt="MacroLog Dashboard"
                            class="w-full rounded-2xl transform hover:scale-105 transition-transform duration-500"
                            loading="eager"
                        />
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
