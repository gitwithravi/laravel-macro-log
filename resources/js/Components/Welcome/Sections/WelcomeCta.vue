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
    <!-- Final CTA Section -->
    <section class="relative py-20 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-indigo-600 to-purple-600 overflow-hidden animate-gradient">
        <!-- Animated background elements -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute top-10 left-20 w-64 h-64 bg-white rounded-full mix-blend-overlay filter blur-xl opacity-10 animate-pulse-slow"></div>
            <div class="absolute bottom-10 right-20 w-64 h-64 bg-white rounded-full mix-blend-overlay filter blur-xl opacity-10 animate-pulse-slow" style="animation-delay: 1.5s;"></div>
        </div>

        <div class="max-w-4xl mx-auto text-center relative scroll-animate">
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-white mb-6">
                Ready to Transform Your Nutrition Journey?
            </h2>
            <p class="text-lg sm:text-xl text-indigo-100 mb-8">
                Join MacroLog today and start tracking your meals with AI-powered intelligence
            </p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center mb-6">
                <Link
                    v-if="canRegister"
                    :href="route('register')"
                    class="group relative bg-white text-indigo-600 px-8 py-4 rounded-lg font-semibold hover:bg-gray-50 transition-all shadow-lg hover:shadow-2xl hover:scale-105 text-center overflow-hidden"
                >
                    <span class="relative z-10">Get Started Free</span>
                    <div class="absolute inset-0 bg-gradient-to-r from-indigo-50 to-purple-50 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                </Link>
                <Link
                    v-if="canLogin && !$page.props.auth.user"
                    :href="route('login')"
                    class="bg-indigo-700 text-white px-8 py-4 rounded-lg font-semibold hover:bg-indigo-800 transition-all border-2 border-white/20 hover:border-white/40 hover:scale-105 text-center"
                >
                    Sign In
                </Link>
            </div>

            <p class="text-sm text-indigo-200">
                No credit card required • Bring your own OpenAI API key • Free forever
            </p>
        </div>
    </section>
</template>
