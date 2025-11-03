<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
});

const isHeaderScrolled = ref(false);

const scrollToTop = () => {
    window.scrollTo({ top: 0, behavior: 'smooth' });
};

onMounted(() => {
    // Scroll animations
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-fade-in-up');
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    });

    document.querySelectorAll('.scroll-animate').forEach(el => {
        observer.observe(el);
    });

    // Header scroll effect
    window.addEventListener('scroll', () => {
        isHeaderScrolled.value = window.scrollY > 20;
    });

    // Smooth scroll for anchor links
    document.documentElement.style.scrollBehavior = 'smooth';
});
</script>

<style scoped>
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes float {
    0%, 100% {
        transform: translateY(0px);
    }
    50% {
        transform: translateY(-20px);
    }
}

@keyframes gradientShift {
    0%, 100% {
        background-position: 0% 50%;
    }
    50% {
        background-position: 100% 50%;
    }
}

@keyframes pulse-slow {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.5;
    }
}

@keyframes slideInLeft {
    from {
        opacity: 0;
        transform: translateX(-30px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes slideInRight {
    from {
        opacity: 0;
        transform: translateX(30px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.animate-fade-in-up {
    animation: fadeInUp 0.8s ease-out forwards;
}

.animate-float {
    animation: float 6s ease-in-out infinite;
}

.animate-gradient {
    background-size: 200% 200%;
    animation: gradientShift 8s ease infinite;
}

.animate-pulse-slow {
    animation: pulse-slow 3s ease-in-out infinite;
}

.scroll-animate {
    opacity: 0;
}

.group:hover .group-hover-scale {
    transform: scale(1.05);
}

.group:hover .group-hover-rotate {
    transform: rotate(5deg);
}
</style>

<template>
    <Head title="MacroLog - Smart Nutrition Tracking Powered by AI">
        <!-- Primary Meta Tags -->
        <meta name="title" content="MacroLog - Smart Nutrition Tracking Powered by AI" />
        <meta name="description" content="Track your meals effortlessly with AI-powered natural language input. No subscription fees—bring your own OpenAI API key. No app download needed, works offline as a PWA." />
        <meta name="keywords" content="nutrition tracker, calorie tracker, macro tracking, AI nutrition, meal tracker, diet app, no subscription, PWA, progressive web app, OpenAI, natural language, food tracker" />
        <meta name="author" content="MacroLog" />
        <meta name="robots" content="index, follow" />

        <!-- Open Graph / Facebook -->
        <meta property="og:type" content="website" />
        <meta property="og:url" content="https://macrolog.online/" />
        <meta property="og:title" content="MacroLog - Smart Nutrition Tracking Powered by AI" />
        <meta property="og:description" content="Track your meals effortlessly with AI-powered natural language input. No subscription fees—bring your own OpenAI API key. No app download needed." />
        <meta property="og:image" content="https://macrolog.online/screens/dashboard.png" />
        <meta property="og:site_name" content="MacroLog" />

        <!-- Twitter -->
        <meta property="twitter:card" content="summary_large_image" />
        <meta property="twitter:url" content="https://macrolog.online/" />
        <meta property="twitter:title" content="MacroLog - Smart Nutrition Tracking Powered by AI" />
        <meta property="twitter:description" content="Track your meals effortlessly with AI-powered natural language input. No subscription fees—bring your own OpenAI API key." />
        <meta property="twitter:image" content="https://macrolog.online/screens/dashboard.png" />

        <!-- Canonical URL -->
        <link rel="canonical" href="https://macrolog.online/" />

        <!-- Additional Meta Tags -->
        <meta name="theme-color" content="#4F46E5" />
        <meta name="apple-mobile-web-app-capable" content="yes" />
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
        <meta name="apple-mobile-web-app-title" content="MacroLog" />
    </Head>

    <div class="min-h-screen bg-white overflow-x-hidden">
        <!-- Navigation Header -->
        <header
            :class="[
                'fixed top-0 left-0 right-0 z-50 transition-all duration-300',
                isHeaderScrolled ? 'bg-white shadow-lg' : 'bg-white/80 backdrop-blur-md border-b border-gray-100'
            ]"
        >
            <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <!-- Logo -->
                    <div class="flex items-center space-x-2 group cursor-pointer" @click="scrollToTop">
                        <div class="w-8 h-8 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-lg transform transition-transform group-hover:rotate-12 group-hover:scale-110 duration-300"></div>
                        <span class="text-xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
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

                    <!-- Auth Links -->
                    <div v-if="canLogin" class="flex items-center space-x-4">
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
                </div>
            </nav>
        </header>

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

        <!-- Features Section -->
        <section id="features" class="py-20 px-4 sm:px-6 lg:px-8 bg-white scroll-mt-16">
            <div class="max-w-7xl mx-auto">
                <!-- Section Header -->
                <div class="text-center mb-16 scroll-animate">
                    <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">
                        Everything You Need to Achieve Your Goals
                    </h2>
                    <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                        Powerful features designed to make nutrition tracking effortless and effective
                    </p>
                </div>

                <!-- Features Grid -->
                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
                    <!-- Feature 1: AI Meal Logging -->
                    <div class="group scroll-animate bg-white border border-gray-100 rounded-2xl p-6 hover:shadow-2xl hover:-translate-y-2 hover:border-indigo-200 transition-all duration-300 cursor-pointer">
                        <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center mb-4 group-hover:bg-indigo-600 group-hover:scale-110 transition-all duration-300">
                            <svg class="w-6 h-6 text-indigo-600 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2 group-hover:text-indigo-600 transition-colors">Natural Language Input</h3>
                        <p class="text-gray-600">
                            Simply describe your meal in plain text. AI automatically extracts calories and macros from any dish.
                        </p>
                    </div>

                    <!-- Feature 2: Macro Tracking -->
                    <div class="group scroll-animate bg-white border border-gray-100 rounded-2xl p-6 hover:shadow-2xl hover:-translate-y-2 hover:border-orange-200 transition-all duration-300 cursor-pointer" style="animation-delay: 0.1s;">
                        <div class="w-12 h-12 bg-gradient-to-br from-orange-100 via-blue-100 to-purple-100 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 group-hover:shadow-lg transition-all duration-300">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2 group-hover:text-orange-600 transition-colors">Color-Coded Macros</h3>
                        <p class="text-gray-600">
                            Track protein, carbs, and fats with beautiful, intuitive color-coding. See your nutrition at a glance.
                        </p>
                    </div>

                    <!-- Feature 3: Goal Management -->
                    <div class="group scroll-animate bg-white border border-gray-100 rounded-2xl p-6 hover:shadow-2xl hover:-translate-y-2 hover:border-indigo-200 transition-all duration-300 cursor-pointer" style="animation-delay: 0.2s;">
                        <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center mb-4 group-hover:bg-indigo-600 group-hover:scale-110 transition-all duration-300">
                            <svg class="w-6 h-6 text-indigo-600 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2 group-hover:text-indigo-600 transition-colors">Personalized Goals</h3>
                        <p class="text-gray-600">
                            Set custom nutrition targets based on your weight goals, timeline, and activity level.
                        </p>
                    </div>

                    <!-- Feature 4: Smart Insights -->
                    <div class="group scroll-animate bg-white border border-gray-100 rounded-2xl p-6 hover:shadow-2xl hover:-translate-y-2 hover:border-purple-200 transition-all duration-300 cursor-pointer" style="animation-delay: 0.3s;">
                        <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center mb-4 group-hover:bg-purple-600 group-hover:scale-110 transition-all duration-300">
                            <svg class="w-6 h-6 text-purple-600 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2 group-hover:text-purple-600 transition-colors">AI-Powered Insights</h3>
                        <p class="text-gray-600">
                            Get intelligent feedback on your meals with context-aware recommendations and practical suggestions.
                        </p>
                    </div>

                    <!-- Feature 5: History Tracking -->
                    <div class="group scroll-animate bg-white border border-gray-100 rounded-2xl p-6 hover:shadow-2xl hover:-translate-y-2 hover:border-blue-200 transition-all duration-300 cursor-pointer" style="animation-delay: 0.4s;">
                        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mb-4 group-hover:bg-blue-600 group-hover:scale-110 transition-all duration-300">
                            <svg class="w-6 h-6 text-blue-600 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2 group-hover:text-blue-600 transition-colors">7-Day History</h3>
                        <p class="text-gray-600">
                            Review your meal history for the past week with daily totals and progress tracking.
                        </p>
                    </div>

                    <!-- Feature 6: PWA Support -->
                    <div class="group scroll-animate bg-white border border-gray-100 rounded-2xl p-6 hover:shadow-2xl hover:-translate-y-2 hover:border-emerald-200 transition-all duration-300 cursor-pointer" style="animation-delay: 0.5s;">
                        <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center mb-4 group-hover:bg-emerald-600 group-hover:scale-110 transition-all duration-300">
                            <svg class="w-6 h-6 text-emerald-600 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2 group-hover:text-emerald-600 transition-colors">No App Download Required</h3>
                        <p class="text-gray-600">
                            Skip the app stores entirely—simply add to your home screen for instant access. Works like a native app without taking up storage space.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Screenshots Section -->
        <section id="screenshots" class="py-20 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-gray-50 via-indigo-50 to-purple-50 overflow-hidden scroll-mt-16">
            <div class="max-w-7xl mx-auto">
                <!-- Section Header -->
                <div class="text-center mb-16 scroll-animate">
                    <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">
                        See MacroLog in Action
                    </h2>
                    <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                        Experience the sleek, intuitive interface designed for effortless nutrition tracking on any device
                    </p>
                </div>

                <!-- Screenshots Grid -->
                <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-8 items-start">
                    <!-- Screenshot 1: Dashboard -->
                    <div class="scroll-animate group">
                        <div class="relative">
                            <div class="relative mx-auto" style="max-width: 280px;">
                                <img
                                    src="/screens/dashboard.png"
                                    alt="MacroLog Dashboard - Track your daily meals and nutrition goals"
                                    class="w-full h-auto rounded-2xl transform transition-all duration-500 group-hover:scale-105 group-hover:-rotate-1"
                                    loading="lazy"
                                />

                                <!-- Floating badge -->
                                <div class="absolute -top-4 -right-4 bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-4 py-2 rounded-full text-sm font-semibold shadow-lg transform rotate-12 group-hover:rotate-0 transition-transform duration-300">
                                    Dashboard
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="mt-8 text-center">
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">Smart Dashboard</h3>
                            <p class="text-gray-600 text-sm">
                                Track your daily progress with beautiful, color-coded macro displays
                            </p>
                        </div>
                    </div>

                    <!-- Screenshot 2: History -->
                    <div class="scroll-animate group" style="animation-delay: 0.15s;">
                        <div class="relative">
                            <div class="relative mx-auto" style="max-width: 280px;">
                                <img
                                    src="/screens/history.png"
                                    alt="MacroLog History - View your 7-day meal history and trends"
                                    class="w-full h-auto rounded-2xl transform transition-all duration-500 group-hover:scale-105 group-hover:rotate-1"
                                    loading="lazy"
                                />

                                <div class="absolute -top-4 -right-4 bg-gradient-to-r from-orange-500 to-red-500 text-white px-4 py-2 rounded-full text-sm font-semibold shadow-lg transform rotate-12 group-hover:rotate-0 transition-transform duration-300">
                                    History
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 text-center">
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">7-Day History</h3>
                            <p class="text-gray-600 text-sm">
                                Review your meal history and nutrition trends over the past week
                            </p>
                        </div>
                    </div>

                    <!-- Screenshot 3: Goal Settings -->
                    <div class="scroll-animate group" style="animation-delay: 0.3s;">
                        <div class="relative">
                            <div class="relative mx-auto" style="max-width: 280px;">
                                <img
                                    src="/screens/goal-settings.png"
                                    alt="MacroLog Goal Settings - Set personalized nutrition targets"
                                    class="w-full h-auto rounded-2xl transform transition-all duration-500 group-hover:scale-105 group-hover:-rotate-1"
                                    loading="lazy"
                                />

                                <div class="absolute -top-4 -right-4 bg-gradient-to-r from-emerald-500 to-teal-500 text-white px-4 py-2 rounded-full text-sm font-semibold shadow-lg transform rotate-12 group-hover:rotate-0 transition-transform duration-300">
                                    Goals
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 text-center">
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">Custom Goals</h3>
                            <p class="text-gray-600 text-sm">
                                Set personalized targets based on your weight goals and activity
                            </p>
                        </div>
                    </div>

                    <!-- Screenshot 4: Goal Dashboard -->
                    <div class="scroll-animate group" style="animation-delay: 0.45s;">
                        <div class="relative">
                            <div class="relative mx-auto" style="max-width: 280px;">
                                <img
                                    src="/screens/goal-dashboard.png"
                                    alt="MacroLog Goal Dashboard - Monitor your progress towards nutrition goals"
                                    class="w-full h-auto rounded-2xl transform transition-all duration-500 group-hover:scale-105 group-hover:rotate-1"
                                    loading="lazy"
                                />

                                <div class="absolute -top-4 -right-4 bg-gradient-to-r from-blue-500 to-cyan-500 text-white px-4 py-2 rounded-full text-sm font-semibold shadow-lg transform rotate-12 group-hover:rotate-0 transition-transform duration-300">
                                    Progress
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 text-center">
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">Goal Progress</h3>
                            <p class="text-gray-600 text-sm">
                                Monitor your progress and stay on track with visual goal tracking
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Call to Action under screenshots -->
                <div class="mt-16 text-center scroll-animate">
                    <p class="text-lg text-gray-700 mb-6">
                        Ready to transform your nutrition tracking experience?
                    </p>
                    <Link
                        v-if="canRegister"
                        :href="route('register')"
                        class="inline-block bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-8 py-4 rounded-lg font-semibold hover:from-indigo-700 hover:to-purple-700 transition-all shadow-lg hover:shadow-2xl hover:scale-105"
                    >
                        Get Started Free
                    </Link>
                </div>
            </div>
        </section>

        <!-- Comparison Section -->
        <section id="comparison" class="py-20 px-4 sm:px-6 lg:px-8 bg-white scroll-mt-16">
            <div class="max-w-7xl mx-auto">
                <!-- Section Header -->
                <div class="text-center mb-16 scroll-animate">
                    <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">
                        Why Choose MacroLog Over Traditional Apps?
                    </h2>
                    <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                        Break free from expensive subscriptions and hidden costs. MacroLog puts you in control.
                    </p>
                </div>

                <!-- Comparison Table -->
                <div class="max-w-5xl mx-auto scroll-animate">
                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse bg-white rounded-2xl overflow-hidden shadow-lg">
                            <thead>
                                <tr class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white">
                                    <th class="py-4 px-6 text-left font-semibold">Features</th>
                                    <th class="py-4 px-6 text-center font-semibold">
                                        <div class="flex items-center justify-center gap-2">
                                            <div class="w-6 h-6 bg-white rounded flex items-center justify-center">
                                                <div class="w-4 h-4 bg-gradient-to-br from-indigo-600 to-purple-600 rounded"></div>
                                            </div>
                                            MacroLog
                                        </div>
                                    </th>
                                    <th class="py-4 px-6 text-center font-semibold text-indigo-100">MyFitnessPal</th>
                                    <th class="py-4 px-6 text-center font-semibold text-indigo-100">Cronometer</th>
                                    <th class="py-4 px-6 text-center font-semibold text-indigo-100">Lose It!</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <!-- Row 1: Monthly Cost -->
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="py-4 px-6 font-medium text-gray-900">Monthly Cost</td>
                                    <td class="py-4 px-6 text-center">
                                        <span class="inline-flex items-center gap-1 text-emerald-600 font-bold">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            $0
                                        </span>
                                        <div class="text-xs text-gray-500 mt-1">Pay only API costs</div>
                                    </td>
                                    <td class="py-4 px-6 text-center text-gray-600">$19.99</td>
                                    <td class="py-4 px-6 text-center text-gray-600">$9.99</td>
                                    <td class="py-4 px-6 text-center text-gray-600">$39.99</td>
                                </tr>

                                <!-- Row 2: AI Meal Analysis -->
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="py-4 px-6 font-medium text-gray-900">AI-Powered Meal Analysis</td>
                                    <td class="py-4 px-6 text-center">
                                        <svg class="w-6 h-6 text-emerald-500 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        <svg class="w-6 h-6 text-gray-300 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                        </svg>
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        <svg class="w-6 h-6 text-gray-300 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                        </svg>
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        <svg class="w-6 h-6 text-gray-300 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                        </svg>
                                    </td>
                                </tr>

                                <!-- Row 3: Natural Language Input -->
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="py-4 px-6 font-medium text-gray-900">Natural Language Input</td>
                                    <td class="py-4 px-6 text-center">
                                        <svg class="w-6 h-6 text-emerald-500 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        <span class="text-yellow-600 text-sm">Limited</span>
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        <svg class="w-6 h-6 text-gray-300 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                        </svg>
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        <svg class="w-6 h-6 text-gray-300 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                        </svg>
                                    </td>
                                </tr>

                                <!-- Row 4: Your Data Control -->
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="py-4 px-6 font-medium text-gray-900">Your Data, Your Control</td>
                                    <td class="py-4 px-6 text-center">
                                        <svg class="w-6 h-6 text-emerald-500 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        <svg class="w-6 h-6 text-gray-300 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                        </svg>
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        <svg class="w-6 h-6 text-gray-300 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                        </svg>
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        <svg class="w-6 h-6 text-gray-300 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                        </svg>
                                    </td>
                                </tr>

                                <!-- Row 5: No App Download -->
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="py-4 px-6 font-medium text-gray-900">No App Store Download</td>
                                    <td class="py-4 px-6 text-center">
                                        <svg class="w-6 h-6 text-emerald-500 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <div class="text-xs text-gray-500 mt-1">PWA</div>
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        <svg class="w-6 h-6 text-gray-300 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                        </svg>
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        <svg class="w-6 h-6 text-gray-300 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                        </svg>
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        <svg class="w-6 h-6 text-gray-300 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                        </svg>
                                    </td>
                                </tr>

                                <!-- Row 6: Ads -->
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="py-4 px-6 font-medium text-gray-900">Ad-Free Experience</td>
                                    <td class="py-4 px-6 text-center">
                                        <svg class="w-6 h-6 text-emerald-500 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        <span class="text-red-600 text-sm">Has Ads</span>
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        <svg class="w-6 h-6 text-emerald-500 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        <svg class="w-6 h-6 text-emerald-500 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Bottom note -->
                    <div class="mt-8 text-center scroll-animate">
                        <p class="text-sm text-gray-600">
                            * Prices and features based on premium tier plans as of 2025. Actual costs may vary.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- How It Works Section -->
        <section id="how-it-works" class="py-20 px-4 sm:px-6 lg:px-8 bg-gray-50 scroll-mt-16">
            <div class="max-w-7xl mx-auto">
                <!-- Section Header -->
                <div class="text-center mb-16 scroll-animate">
                    <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">
                        Get Started in 3 Simple Steps
                    </h2>
                    <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                        Start tracking your nutrition in minutes with no credit card required
                    </p>
                </div>

                <!-- Steps -->
                <div class="grid md:grid-cols-3 gap-8">
                    <!-- Step 1 -->
                    <div class="relative scroll-animate">
                        <div class="bg-white rounded-2xl p-8 shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                            <div class="w-12 h-12 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-xl flex items-center justify-center text-white font-bold text-xl mb-6 animate-gradient">
                                1
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-3">Create Your Account</h3>
                            <p class="text-gray-600 mb-6">
                                Sign up for free—no credit card required. Complete your profile with basic health information.
                            </p>
                            <!-- Mockup -->
                            <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-lg p-4 aspect-[4/3] flex items-center justify-center">
                                <div class="text-center text-gray-400 text-sm">
                                    <svg class="w-12 h-12 mx-auto mb-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                    </svg>
                                    Sign Up Form
                                </div>
                            </div>
                        </div>
                        <!-- Connector Arrow (hidden on mobile) -->
                        <div class="hidden md:block absolute top-1/2 -right-4 transform -translate-y-1/2 z-10">
                            <svg class="w-8 h-8 text-indigo-400 animate-pulse" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>

                    <!-- Step 2 -->
                    <div class="relative scroll-animate" style="animation-delay: 0.2s;">
                        <div class="bg-white rounded-2xl p-8 shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                            <div class="w-12 h-12 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-xl flex items-center justify-center text-white font-bold text-xl mb-6 animate-gradient">
                                2
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-3">Add Your API Key</h3>
                            <p class="text-gray-600 mb-6">
                                Bring your own OpenAI API key. Keep control and pay only for what you use (typically pennies per meal).
                            </p>
                            <!-- Mockup -->
                            <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-lg p-4 aspect-[4/3] flex items-center justify-center">
                                <div class="text-center text-gray-400 text-sm">
                                    <svg class="w-12 h-12 mx-auto mb-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 8a6 6 0 01-7.743 5.743L10 14l-1 1-1 1H6v2H2v-4l4.257-4.257A6 6 0 1118 8zm-6-4a1 1 0 100 2 2 2 0 012 2 1 1 0 102 0 4 4 0 00-4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    API Settings
                                </div>
                            </div>
                        </div>
                        <!-- Connector Arrow (hidden on mobile) -->
                        <div class="hidden md:block absolute top-1/2 -right-4 transform -translate-y-1/2 z-10">
                            <svg class="w-8 h-8 text-indigo-400 animate-pulse" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>

                    <!-- Step 3 -->
                    <div class="relative scroll-animate" style="animation-delay: 0.4s;">
                        <div class="bg-white rounded-2xl p-8 shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                            <div class="w-12 h-12 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-xl flex items-center justify-center text-white font-bold text-xl mb-6 animate-gradient">
                                3
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-3">Start Logging Meals</h3>
                            <p class="text-gray-600 mb-6">
                                Describe your meals naturally: "2 chapatis with dal and rice"—AI does the rest!
                            </p>
                            <!-- Mockup -->
                            <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-lg p-4 aspect-[4/3] flex items-center justify-center">
                                <div class="text-center text-gray-400 text-sm">
                                    <svg class="w-12 h-12 mx-auto mb-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                                    </svg>
                                    Meal Dashboard
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Benefits/Social Proof Section -->
        <section id="pricing" class="py-20 px-4 sm:px-6 lg:px-8 bg-white scroll-mt-16">
            <div class="max-w-7xl mx-auto">
                <!-- Section Header -->
                <div class="text-center mb-16 scroll-animate">
                    <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">
                        Why Choose MacroLog?
                    </h2>
                </div>

                <!-- Detailed Pricing Breakdown -->
                <div class="max-w-4xl mx-auto mb-16">
                    <div class="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-3xl p-8 md:p-12 scroll-animate border border-indigo-100">
                        <div class="text-center mb-8">
                            <h3 class="text-2xl md:text-3xl font-bold text-gray-900 mb-3">
                                Simple, Transparent Pricing
                            </h3>
                            <p class="text-lg text-gray-600">
                                MacroLog is free. You only pay for OpenAI API usage.
                            </p>
                        </div>

                        <!-- Cost Breakdown -->
                        <div class="grid md:grid-cols-2 gap-6 mb-8">
                            <!-- MacroLog Cost -->
                            <div class="bg-white rounded-2xl p-6 shadow-sm">
                                <div class="flex items-center gap-3 mb-4">
                                    <div class="w-12 h-12 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-xl flex items-center justify-center">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-900">MacroLog Platform</h4>
                                        <p class="text-sm text-gray-600">Monthly Fee</p>
                                    </div>
                                </div>
                                <div class="text-4xl font-bold text-emerald-600 mb-2">$0</div>
                                <p class="text-sm text-gray-600">Completely free forever. No trials, no hidden fees.</p>
                            </div>

                            <!-- API Cost -->
                            <div class="bg-white rounded-2xl p-6 shadow-sm">
                                <div class="flex items-center gap-3 mb-4">
                                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-900">OpenAI API</h4>
                                        <p class="text-sm text-gray-600">Usage-Based</p>
                                    </div>
                                </div>
                                <div class="text-4xl font-bold text-indigo-600 mb-2">~$0.50</div>
                                <p class="text-sm text-gray-600">Estimated cost per month for typical usage</p>
                            </div>
                        </div>

                        <!-- Usage Examples -->
                        <div class="bg-white rounded-2xl p-6">
                            <h4 class="font-semibold text-gray-900 mb-4">What does $0.50/month get you?</h4>
                            <div class="space-y-3">
                                <div class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-emerald-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <div>
                                        <p class="text-gray-900 font-medium">~300-500 meal logs per month</p>
                                        <p class="text-sm text-gray-600">Based on GPT-4o-mini pricing (~$0.15 per million input tokens)</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-emerald-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <div>
                                        <p class="text-gray-900 font-medium">Unlimited meal insights and AI recommendations</p>
                                        <p class="text-sm text-gray-600">Get personalized feedback on every meal</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-emerald-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <div>
                                        <p class="text-gray-900 font-medium">Full access to all features</p>
                                        <p class="text-sm text-gray-600">No premium tiers or paywalls</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Comparison note -->
                        <div class="mt-6 p-4 bg-indigo-100 rounded-xl">
                            <p class="text-sm text-indigo-900 text-center">
                                <strong>Compare:</strong> Traditional nutrition apps charge $10-$40/month for similar features. With MacroLog, you pay less than $1/month for AI-powered tracking.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Benefits Grid -->
                <div class="grid md:grid-cols-3 gap-8">
                    <!-- Benefit 1 -->
                    <div class="text-center scroll-animate group">
                        <div class="w-16 h-16 bg-gradient-to-br from-green-100 to-emerald-100 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 group-hover:rotate-6 transition-all duration-300 group-hover:shadow-lg">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-3 group-hover:text-green-600 transition-colors">No Subscription Fees</h3>
                        <p class="text-gray-600">
                            Bring your own OpenAI API key and pay only for what you use. No hidden costs, no monthly fees. Complete transparency.
                        </p>
                    </div>

                    <!-- Benefit 2 -->
                    <div class="text-center scroll-animate group" style="animation-delay: 0.2s;">
                        <div class="w-16 h-16 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 group-hover:rotate-6 transition-all duration-300 group-hover:shadow-lg">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-3 group-hover:text-blue-600 transition-colors">Your Data, Your Control</h3>
                        <p class="text-gray-600">
                            Your API keys are encrypted and stored securely. Your nutrition data belongs to you, always private and protected.
                        </p>
                    </div>

                    <!-- Benefit 3 -->
                    <div class="text-center scroll-animate group" style="animation-delay: 0.4s;">
                        <div class="w-16 h-16 bg-gradient-to-br from-purple-100 to-pink-100 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 group-hover:rotate-6 transition-all duration-300 group-hover:shadow-lg">
                            <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-3 group-hover:text-purple-600 transition-colors">AI-Powered Intelligence</h3>
                        <p class="text-gray-600">
                            Leveraging GPT-4o-mini for accurate nutritional analysis and personalized insights that help you reach your goals.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- FAQ Section -->
        <section id="faq" class="py-20 px-4 sm:px-6 lg:px-8 bg-gray-50 scroll-mt-16">
            <div class="max-w-4xl mx-auto">
                <!-- Section Header -->
                <div class="text-center mb-16 scroll-animate">
                    <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">
                        Frequently Asked Questions
                    </h2>
                </div>

                <!-- FAQ List -->
                <div class="space-y-6">
                    <!-- FAQ 1 -->
                    <div class="scroll-animate bg-white rounded-2xl p-6 shadow-sm hover:shadow-lg transition-all duration-300 cursor-pointer border border-transparent hover:border-indigo-200">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">
                            Why do I need to bring my own OpenAI API key?
                        </h3>
                        <p class="text-gray-600">
                            This approach gives you complete control and transparency. You pay OpenAI directly for only what you use (typically just pennies per meal), avoid monthly subscription fees, and maintain full ownership of your data. It's more cost-effective and private.
                        </p>
                    </div>

                    <!-- FAQ 2 -->
                    <div class="scroll-animate bg-white rounded-2xl p-6 shadow-sm hover:shadow-lg transition-all duration-300 cursor-pointer border border-transparent hover:border-indigo-200" style="animation-delay: 0.1s;">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">
                            How much does the OpenAI API cost?
                        </h3>
                        <p class="text-gray-600">
                            MacroLog uses GPT-4o-mini, which costs about $0.15 per million input tokens. For most users, this translates to just a few cents per month—far less than traditional nutrition app subscriptions. You can monitor your usage directly in your OpenAI dashboard.
                        </p>
                    </div>

                    <!-- FAQ 3 -->
                    <div class="scroll-animate bg-white rounded-2xl p-6 shadow-sm hover:shadow-lg transition-all duration-300 cursor-pointer border border-transparent hover:border-indigo-200" style="animation-delay: 0.2s;">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">
                            Is my nutrition data private and secure?
                        </h3>
                        <p class="text-gray-600">
                            Absolutely. Your API keys are encrypted and stored securely in our database. All API calls to OpenAI are made server-side with proper security measures. Your nutrition data is never shared with third parties and remains under your control.
                        </p>
                    </div>

                    <!-- FAQ 4 -->
                    <div class="scroll-animate bg-white rounded-2xl p-6 shadow-sm hover:shadow-lg transition-all duration-300 cursor-pointer border border-transparent hover:border-indigo-200" style="animation-delay: 0.3s;">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">
                            What features are included?
                        </h3>
                        <p class="text-gray-600">
                            All of them! MacroLog includes natural language meal logging, comprehensive macro tracking, personalized goal management, AI-powered meal insights, 7-day history, nutrition calculator, and PWA support for offline access. No premium tiers or paywalls.
                        </p>
                    </div>

                    <!-- FAQ 5 -->
                    <div class="scroll-animate bg-white rounded-2xl p-6 shadow-sm hover:shadow-lg transition-all duration-300 cursor-pointer border border-transparent hover:border-indigo-200" style="animation-delay: 0.4s;">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">
                            Do I need to download an app from the App Store or Play Store?
                        </h3>
                        <p class="text-gray-600">
                            No! MacroLog is a Progressive Web App (PWA)—simply visit the website and add it to your home screen for instant access. No app store downloads, no installation hassles, and no extra storage space required. It works just like a native app on any device, and you can even use it offline to view your history and previously logged meals.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Contact Section -->
        <section id="contact" class="py-20 px-4 sm:px-6 lg:px-8 bg-white scroll-mt-16">
            <div class="max-w-4xl mx-auto">
                <div class="text-center mb-12 scroll-animate">
                    <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">
                        Get in Touch
                    </h2>
                    <p class="text-lg text-gray-600">
                        Have questions? We're here to help!
                    </p>
                </div>

                <div class="grid md:grid-cols-2 gap-8 scroll-animate">
                    <!-- Contact Card -->
                    <div class="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-2xl p-8 border border-indigo-100">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900 text-lg">Email Support</h3>
                                <p class="text-sm text-gray-600">We typically respond within 24 hours</p>
                            </div>
                        </div>
                        <a
                            href="mailto:care@macrolog.online"
                            class="inline-flex items-center gap-2 text-indigo-600 hover:text-indigo-700 font-medium transition-colors"
                        >
                            care@macrolog.online
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                            </svg>
                        </a>
                    </div>

                    <!-- Quick Links Card -->
                    <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl p-8 border border-gray-200">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 bg-gray-700 rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900 text-lg">Help & Resources</h3>
                                <p class="text-sm text-gray-600">Find answers to common questions</p>
                            </div>
                        </div>
                        <div class="space-y-3">
                            <a
                                href="#faq"
                                class="flex items-center gap-2 text-gray-700 hover:text-indigo-600 transition-colors"
                            >
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                </svg>
                                Frequently Asked Questions
                            </a>
                            <Link
                                :href="route('terms.show')"
                                class="flex items-center gap-2 text-gray-700 hover:text-indigo-600 transition-colors"
                            >
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                </svg>
                                Terms of Service
                            </Link>
                            <Link
                                :href="route('policy.show')"
                                class="flex items-center gap-2 text-gray-700 hover:text-indigo-600 transition-colors"
                            >
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                </svg>
                                Privacy Policy
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- Additional Info -->
                <div class="mt-12 text-center scroll-animate">
                    <div class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-50 rounded-full text-indigo-700">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-sm font-medium">Support available Monday-Friday, 9AM-5PM EST</span>
                    </div>
                </div>
            </div>
        </section>

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

        <!-- Footer -->
        <footer class="bg-gray-900 text-gray-400 py-16 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto">
                <!-- Main Footer Content -->
                <div class="grid md:grid-cols-4 gap-8 mb-12">
                    <!-- Brand Column -->
                    <div class="md:col-span-1">
                        <div class="flex items-center space-x-2 mb-4">
                            <div class="w-8 h-8 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-lg"></div>
                            <span class="text-lg font-bold text-white">MacroLog</span>
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
    </div>
</template>
