<script setup>
import { Link } from '@inertiajs/vue3';
import logoUrl from '/public/icons/application.png';

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
    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-400 py-16 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <!-- Main Footer Content -->
            <div class="grid md:grid-cols-2 lg:grid-cols-5 gap-8 mb-12">
                <!-- Brand Column -->
                <div class="md:col-span-1">
                    <div class="flex items-center space-x-2 mb-4">
                        <img
                            :src="logoUrl"
                            alt="MacroLog"
                            class="w-8 h-8"
                        />
                        <span class="text-lg font-bold bg-gradient-to-r from-indigo-400 via-purple-400 to-pink-400 bg-clip-text text-transparent">MacroLog</span>
                    </div>
                    <p class="text-sm text-gray-400 mb-4">
                        Smart nutrition tracking powered by AI. No subscriptions, complete control.
                    </p>
                    <a
                        href="mailto:care@macrolog.online"
                        class="text-sm text-indigo-400 hover:text-indigo-300 transition-colors"
                    >
                        care@macrolog.online
                    </a>
                </div>

                <!-- Product Column -->
                <div>
                    <h3 class="text-white font-semibold mb-4">Product</h3>
                    <ul class="space-y-2 text-sm">
                        <li>
                            <a href="#features" class="hover:text-white transition-colors">Features</a>
                        </li>
                        <li>
                            <a href="#screenshots" class="hover:text-white transition-colors">Screenshots</a>
                        </li>
                        <li>
                            <a href="#pricing" class="hover:text-white transition-colors">Pricing</a>
                        </li>
                        <li>
                            <a href="#comparison" class="hover:text-white transition-colors">Compare</a>
                        </li>
                    </ul>
                </div>

                <!-- Resources Column -->
                <div>
                    <h3 class="text-white font-semibold mb-4">Resources</h3>
                    <ul class="space-y-2 text-sm">
                        <li>
                            <a href="#how-it-works" class="hover:text-white transition-colors">How It Works</a>
                        </li>
                        <li>
                            <a href="#faq" class="hover:text-white transition-colors">FAQ</a>
                        </li>
                        <li>
                            <Link :href="route('terms.show')" class="hover:text-white transition-colors">
                                Terms of Service
                            </Link>
                        </li>
                        <li>
                            <Link :href="route('policy.show')" class="hover:text-white transition-colors">
                                Privacy Policy
                            </Link>
                        </li>
                    </ul>
                </div>

                <!-- Open Source Column -->
                <div>
                    <h3 class="text-white font-semibold mb-4">Open Source</h3>
                    <ul class="space-y-2 text-sm">
                        <li>
                            <a
                                href="https://github.com/gitwithravi/laravel-macro-log"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="hover:text-white transition-colors flex items-center gap-2"
                            >
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0 1.005-.315 3.3 1.23.96-.27 1.98-.405 3-.405s2.04.135 3 .405c2.295-1.56 3.3-1.23 3.3-1.23.66 1.65.24 2.88.12 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0024 12c0-6.63-5.37-12-12-12z"/>
                                </svg>
                                View on GitHub
                            </a>
                        </li>
                    </ul>
                    <p class="text-sm text-gray-400 mt-4 leading-relaxed">
                        Want more control? Install and use the application on your own server.
                    </p>
                </div>

                <!-- Support Column -->
                <div>
                    <h3 class="text-white font-semibold mb-4">Support</h3>
                    <ul class="space-y-2 text-sm">
                        <li>
                            <a href="#contact" class="hover:text-white transition-colors">Contact Us</a>
                        </li>
                        <li>
                            <a href="#faq" class="hover:text-white transition-colors">Help Center</a>
                        </li>
                        <li>
                            <Link v-if="canRegister" :href="route('register')" class="hover:text-white transition-colors">
                                Get Started
                            </Link>
                        </li>
                        <li>
                            <Link v-if="canLogin && !$page.props.auth.user" :href="route('login')" class="hover:text-white transition-colors">
                                Sign In
                            </Link>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Bottom Bar -->
            <div class="pt-8 border-t border-gray-800">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                    <div class="text-sm text-gray-500">
                        &copy; 2025 MacroLog. All rights reserved. Built with Laravel & Vue.js.
                    </div>
                    <div class="flex items-center gap-6 text-sm">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-gray-500">No Subscription</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-gray-500">AI-Powered</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-gray-500">Privacy First</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</template>
