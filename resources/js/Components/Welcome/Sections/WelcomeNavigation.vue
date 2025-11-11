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
    isScrolled: {
        type: Boolean,
        default: false,
    },
    isMobileMenuOpen: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(['scrollToTop', 'toggleMobileMenu', 'closeMobileMenu']);
</script>

<template>
    <!-- Navigation Header -->
    <header
        :class="[
            'fixed top-0 left-0 right-0 z-50 transition-all duration-300',
            isScrolled ? 'bg-white shadow-lg' : 'bg-white/80 backdrop-blur-md border-b border-gray-100'
        ]"
    >
        <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <div class="flex items-center space-x-2 group cursor-pointer" @click="emit('scrollToTop')">
                    <img
                        :src="logoUrl"
                        alt="MacroLog"
                        class="w-8 h-8 transform transition-transform group-hover:scale-110 duration-300"
                    />
                    <span class="text-xl font-bold bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 bg-clip-text text-transparent">
                        MacroLog
                    </span>
                </div>

                <!-- Navigation Links - Hidden on mobile, shown on desktop -->
                <div class="hidden lg:flex items-center space-x-6">
                    <a href="#features" class="text-gray-700 hover:text-indigo-600 font-medium transition-colors">
                        Features
                    </a>
                    <a href="#screenshots" class="text-gray-700 hover:text-indigo-600 font-medium transition-colors">
                        Screenshots
                    </a>
                    <a href="#how-it-works" class="text-gray-700 hover:text-indigo-600 font-medium transition-colors">
                        How It Works
                    </a>
                    <a href="#pricing" class="text-gray-700 hover:text-indigo-600 font-medium transition-colors">
                        Pricing
                    </a>
                    <a href="#faq" class="text-gray-700 hover:text-indigo-600 font-medium transition-colors">
                        FAQ
                    </a>
                </div>

                <!-- Desktop Auth Links -->
                <div v-if="canLogin" class="hidden lg:flex items-center space-x-4">
                    <Link
                        v-if="$page.props.auth.user"
                        :href="route('dashboard')"
                        class="text-gray-700 hover:text-indigo-600 font-medium transition-colors"
                    >
                        Dashboard
                    </Link>
                    <template v-else>
                        <Link
                            :href="route('login')"
                            class="text-gray-700 hover:text-indigo-600 font-medium transition-colors"
                        >
                            Log in
                        </Link>
                        <Link
                            v-if="canRegister"
                            :href="route('register')"
                            class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-6 py-2 rounded-lg font-medium hover:from-indigo-700 hover:to-purple-700 transition-all shadow-sm hover:shadow-md"
                        >
                            Get Started Free
                        </Link>
                    </template>
                </div>

                <!-- Mobile Menu Button -->
                <button
                    v-if="canLogin"
                    @click="emit('toggleMobileMenu')"
                    class="lg:hidden p-2 rounded-lg text-gray-700 hover:bg-gray-100 transition-colors"
                    :aria-label="isMobileMenuOpen ? 'Close menu' : 'Open menu'"
                >
                    <!-- Hamburger Icon -->
                    <svg
                        v-if="!isMobileMenuOpen"
                        class="w-6 h-6"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <!-- Close Icon -->
                    <svg
                        v-else
                        class="w-6 h-6"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Mobile Menu -->
            <transition
                enter-active-class="transition-all duration-300 ease-out"
                enter-from-class="opacity-0 -translate-y-2"
                enter-to-class="opacity-100 translate-y-0"
                leave-active-class="transition-all duration-200 ease-in"
                leave-from-class="opacity-100 translate-y-0"
                leave-to-class="opacity-0 -translate-y-2"
            >
                <div v-if="isMobileMenuOpen" class="lg:hidden border-t border-gray-200 py-4 bg-white">
                    <!-- Mobile Navigation Links -->
                    <div class="flex flex-col space-y-4 mb-4">
                        <a
                            href="#features"
                            @click="emit('closeMobileMenu')"
                            class="text-gray-700 hover:text-indigo-600 font-medium transition-colors px-2 py-1"
                        >
                            Features
                        </a>
                        <a
                            href="#screenshots"
                            @click="emit('closeMobileMenu')"
                            class="text-gray-700 hover:text-indigo-600 font-medium transition-colors px-2 py-1"
                        >
                            Screenshots
                        </a>
                        <a
                            href="#how-it-works"
                            @click="emit('closeMobileMenu')"
                            class="text-gray-700 hover:text-indigo-600 font-medium transition-colors px-2 py-1"
                        >
                            How It Works
                        </a>
                        <a
                            href="#pricing"
                            @click="emit('closeMobileMenu')"
                            class="text-gray-700 hover:text-indigo-600 font-medium transition-colors px-2 py-1"
                        >
                            Pricing
                        </a>
                        <a
                            href="#faq"
                            @click="emit('closeMobileMenu')"
                            class="text-gray-700 hover:text-indigo-600 font-medium transition-colors px-2 py-1"
                        >
                            FAQ
                        </a>
                    </div>

                    <!-- Mobile Auth Links -->
                    <div class="flex flex-col space-y-3 border-t border-gray-200 pt-4">
                        <Link
                            v-if="$page.props.auth.user"
                            :href="route('dashboard')"
                            class="text-center py-2 px-4 text-gray-700 hover:text-indigo-600 font-medium transition-colors"
                        >
                            Dashboard
                        </Link>
                        <template v-else>
                            <Link
                                :href="route('login')"
                                class="text-center py-2 px-4 text-gray-700 hover:text-indigo-600 font-medium transition-colors border border-gray-300 rounded-lg"
                            >
                                Log in
                            </Link>
                            <Link
                                v-if="canRegister"
                                :href="route('register')"
                                class="text-center bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-6 py-3 rounded-lg font-medium hover:from-indigo-700 hover:to-purple-700 transition-all shadow-sm hover:shadow-md"
                            >
                                Get Started Free
                            </Link>
                        </template>
                    </div>
                </div>
            </transition>
        </nav>
    </header>
</template>
