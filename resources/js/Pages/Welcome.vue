<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
});
</script>

<template>
    <Head title="MacroLog - Smart Nutrition Tracking" />

    <div class="min-h-screen bg-white">
        <!-- Navigation Header -->
        <header class="fixed top-0 left-0 right-0 bg-white/80 backdrop-blur-md border-b border-gray-100 z-50">
            <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <!-- Logo -->
                    <div class="flex items-center space-x-2">
                        <div class="w-8 h-8 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-lg"></div>
                        <span class="text-xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                            MacroLog
                        </span>
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
        <section class="pt-24 pb-20 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-indigo-50 via-white to-purple-50">
            <div class="max-w-7xl mx-auto">
                <div class="grid lg:grid-cols-2 gap-12 items-center">
                    <!-- Hero Content -->
                    <div class="text-center lg:text-left">
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
                                class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-8 py-4 rounded-lg font-semibold hover:from-indigo-700 hover:to-purple-700 transition-all shadow-lg hover:shadow-xl text-center"
                            >
                                Get Started Free
                            </Link>
                            <Link
                                v-if="canLogin && !$page.props.auth.user"
                                :href="route('login')"
                                class="bg-white text-gray-700 px-8 py-4 rounded-lg font-semibold border-2 border-gray-200 hover:border-indigo-300 hover:text-indigo-600 transition-all text-center"
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
                                No subscription required
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                Your data, your control
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                AI-powered insights
                            </div>
                        </div>
                    </div>

                    <!-- Hero Image/Mockup -->
                    <div class="relative">
                        <div class="aspect-[4/3] bg-gradient-to-br from-gray-100 to-gray-200 rounded-2xl shadow-2xl overflow-hidden">
                            <!-- Placeholder for dashboard screenshot -->
                            <div class="w-full h-full flex items-center justify-center p-8">
                                <div class="space-y-4 w-full">
                                    <!-- Mock meal card -->
                                    <div class="bg-white rounded-xl p-4 shadow-lg">
                                        <div class="flex items-center justify-between mb-3">
                                            <h3 class="font-semibold text-gray-900">Breakfast</h3>
                                            <span class="text-xs text-gray-500">8:30 AM</span>
                                        </div>
                                        <p class="text-sm text-gray-600 mb-3">Oatmeal with banana and almonds</p>
                                        <div class="grid grid-cols-4 gap-2">
                                            <div class="bg-orange-50 border border-orange-100 rounded-lg p-2 text-center">
                                                <div class="text-xs text-orange-700 font-semibold">350</div>
                                                <div class="text-xs text-orange-600">kcal</div>
                                            </div>
                                            <div class="bg-blue-50 border border-blue-100 rounded-lg p-2 text-center">
                                                <div class="text-xs text-blue-700 font-semibold">12g</div>
                                                <div class="text-xs text-blue-600">protein</div>
                                            </div>
                                            <div class="bg-emerald-50 border border-emerald-100 rounded-lg p-2 text-center">
                                                <div class="text-xs text-emerald-700 font-semibold">58g</div>
                                                <div class="text-xs text-emerald-600">carbs</div>
                                            </div>
                                            <div class="bg-purple-50 border border-purple-100 rounded-lg p-2 text-center">
                                                <div class="text-xs text-purple-700 font-semibold">8g</div>
                                                <div class="text-xs text-purple-600">fat</div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Mock progress widget -->
                                    <div class="bg-white rounded-xl p-4 shadow-lg">
                                        <h3 class="font-semibold text-gray-900 mb-3">Today's Progress</h3>
                                        <div class="space-y-2">
                                            <div>
                                                <div class="flex justify-between text-xs mb-1">
                                                    <span class="text-orange-700">Calories</span>
                                                    <span class="text-gray-600">350 / 2000</span>
                                                </div>
                                                <div class="w-full bg-gray-200 rounded-full h-2">
                                                    <div class="bg-orange-500 h-2 rounded-full" style="width: 17.5%"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section class="py-20 px-4 sm:px-6 lg:px-8 bg-white">
            <div class="max-w-7xl mx-auto">
                <!-- Section Header -->
                <div class="text-center mb-16">
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
                    <div class="bg-white border border-gray-100 rounded-2xl p-6 hover:shadow-lg hover:-translate-y-1 transition-all duration-200">
                        <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Natural Language Input</h3>
                        <p class="text-gray-600">
                            Simply describe your meal in plain text. AI automatically extracts calories and macros from any dish.
                        </p>
                    </div>

                    <!-- Feature 2: Macro Tracking -->
                    <div class="bg-white border border-gray-100 rounded-2xl p-6 hover:shadow-lg hover:-translate-y-1 transition-all duration-200">
                        <div class="w-12 h-12 bg-gradient-to-br from-orange-100 via-blue-100 to-purple-100 rounded-xl flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Color-Coded Macros</h3>
                        <p class="text-gray-600">
                            Track protein, carbs, and fats with beautiful, intuitive color-coding. See your nutrition at a glance.
                        </p>
                    </div>

                    <!-- Feature 3: Goal Management -->
                    <div class="bg-white border border-gray-100 rounded-2xl p-6 hover:shadow-lg hover:-translate-y-1 transition-all duration-200">
                        <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Personalized Goals</h3>
                        <p class="text-gray-600">
                            Set custom nutrition targets based on your weight goals, timeline, and activity level.
                        </p>
                    </div>

                    <!-- Feature 4: Smart Insights -->
                    <div class="bg-white border border-gray-100 rounded-2xl p-6 hover:shadow-lg hover:-translate-y-1 transition-all duration-200">
                        <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">AI-Powered Insights</h3>
                        <p class="text-gray-600">
                            Get intelligent feedback on your meals with context-aware recommendations and practical suggestions.
                        </p>
                    </div>

                    <!-- Feature 5: History Tracking -->
                    <div class="bg-white border border-gray-100 rounded-2xl p-6 hover:shadow-lg hover:-translate-y-1 transition-all duration-200">
                        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">7-Day History</h3>
                        <p class="text-gray-600">
                            Review your meal history for the past week with daily totals and progress tracking.
                        </p>
                    </div>

                    <!-- Feature 6: PWA Support -->
                    <div class="bg-white border border-gray-100 rounded-2xl p-6 hover:shadow-lg hover:-translate-y-1 transition-all duration-200">
                        <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Works Offline</h3>
                        <p class="text-gray-600">
                            Progressive Web App that works offline and can be installed on any device.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- How It Works Section -->
        <section class="py-20 px-4 sm:px-6 lg:px-8 bg-gray-50">
            <div class="max-w-7xl mx-auto">
                <!-- Section Header -->
                <div class="text-center mb-16">
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
                    <div class="relative">
                        <div class="bg-white rounded-2xl p-8 shadow-sm hover:shadow-md transition-shadow">
                            <div class="w-12 h-12 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-xl flex items-center justify-center text-white font-bold text-xl mb-6">
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
                        <div class="hidden md:block absolute top-1/2 -right-4 transform -translate-y-1/2">
                            <svg class="w-8 h-8 text-indigo-300" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>

                    <!-- Step 2 -->
                    <div class="relative">
                        <div class="bg-white rounded-2xl p-8 shadow-sm hover:shadow-md transition-shadow">
                            <div class="w-12 h-12 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-xl flex items-center justify-center text-white font-bold text-xl mb-6">
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
                        <div class="hidden md:block absolute top-1/2 -right-4 transform -translate-y-1/2">
                            <svg class="w-8 h-8 text-indigo-300" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>

                    <!-- Step 3 -->
                    <div class="relative">
                        <div class="bg-white rounded-2xl p-8 shadow-sm hover:shadow-md transition-shadow">
                            <div class="w-12 h-12 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-xl flex items-center justify-center text-white font-bold text-xl mb-6">
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
        <section class="py-20 px-4 sm:px-6 lg:px-8 bg-white">
            <div class="max-w-7xl mx-auto">
                <!-- Section Header -->
                <div class="text-center mb-16">
                    <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">
                        Why Choose MacroLog?
                    </h2>
                </div>

                <!-- Benefits Grid -->
                <div class="grid md:grid-cols-3 gap-8">
                    <!-- Benefit 1 -->
                    <div class="text-center">
                        <div class="w-16 h-16 bg-gradient-to-br from-green-100 to-emerald-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">No Subscription Fees</h3>
                        <p class="text-gray-600">
                            Bring your own OpenAI API key and pay only for what you use. No hidden costs, no monthly fees. Complete transparency.
                        </p>
                    </div>

                    <!-- Benefit 2 -->
                    <div class="text-center">
                        <div class="w-16 h-16 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">Your Data, Your Control</h3>
                        <p class="text-gray-600">
                            Your API keys are encrypted and stored securely. Your nutrition data belongs to you, always private and protected.
                        </p>
                    </div>

                    <!-- Benefit 3 -->
                    <div class="text-center">
                        <div class="w-16 h-16 bg-gradient-to-br from-purple-100 to-pink-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
                            <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">AI-Powered Intelligence</h3>
                        <p class="text-gray-600">
                            Leveraging GPT-4o-mini for accurate nutritional analysis and personalized insights that help you reach your goals.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- FAQ Section -->
        <section class="py-20 px-4 sm:px-6 lg:px-8 bg-gray-50">
            <div class="max-w-4xl mx-auto">
                <!-- Section Header -->
                <div class="text-center mb-16">
                    <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">
                        Frequently Asked Questions
                    </h2>
                </div>

                <!-- FAQ List -->
                <div class="space-y-6">
                    <!-- FAQ 1 -->
                    <div class="bg-white rounded-2xl p-6 shadow-sm">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">
                            Why do I need to bring my own OpenAI API key?
                        </h3>
                        <p class="text-gray-600">
                            This approach gives you complete control and transparency. You pay OpenAI directly for only what you use (typically just pennies per meal), avoid monthly subscription fees, and maintain full ownership of your data. It's more cost-effective and private.
                        </p>
                    </div>

                    <!-- FAQ 2 -->
                    <div class="bg-white rounded-2xl p-6 shadow-sm">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">
                            How much does the OpenAI API cost?
                        </h3>
                        <p class="text-gray-600">
                            MacroLog uses GPT-4o-mini, which costs about $0.15 per million input tokens. For most users, this translates to just a few cents per month—far less than traditional nutrition app subscriptions. You can monitor your usage directly in your OpenAI dashboard.
                        </p>
                    </div>

                    <!-- FAQ 3 -->
                    <div class="bg-white rounded-2xl p-6 shadow-sm">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">
                            Is my nutrition data private and secure?
                        </h3>
                        <p class="text-gray-600">
                            Absolutely. Your API keys are encrypted and stored securely in our database. All API calls to OpenAI are made server-side with proper security measures. Your nutrition data is never shared with third parties and remains under your control.
                        </p>
                    </div>

                    <!-- FAQ 4 -->
                    <div class="bg-white rounded-2xl p-6 shadow-sm">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">
                            What features are included?
                        </h3>
                        <p class="text-gray-600">
                            All of them! MacroLog includes natural language meal logging, comprehensive macro tracking, personalized goal management, AI-powered meal insights, 7-day history, nutrition calculator, and PWA support for offline access. No premium tiers or paywalls.
                        </p>
                    </div>

                    <!-- FAQ 5 -->
                    <div class="bg-white rounded-2xl p-6 shadow-sm">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">
                            Can I use MacroLog offline?
                        </h3>
                        <p class="text-gray-600">
                            Yes! MacroLog is a Progressive Web App (PWA) that can be installed on any device and works offline. You can view your history and previously logged meals without an internet connection. New meal logging and AI insights require an internet connection.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Final CTA Section -->
        <section class="py-20 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-indigo-600 to-purple-600">
            <div class="max-w-4xl mx-auto text-center">
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
                        class="bg-white text-indigo-600 px-8 py-4 rounded-lg font-semibold hover:bg-gray-50 transition-all shadow-lg hover:shadow-xl text-center"
                    >
                        Get Started Free
                    </Link>
                    <Link
                        v-if="canLogin && !$page.props.auth.user"
                        :href="route('login')"
                        class="bg-indigo-700 text-white px-8 py-4 rounded-lg font-semibold hover:bg-indigo-800 transition-all border-2 border-white/20 text-center"
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
        <footer class="bg-gray-900 text-gray-400 py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto">
                <div class="grid md:grid-cols-2 gap-8 items-center">
                    <div class="flex items-center space-x-2">
                        <div class="w-8 h-8 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-lg"></div>
                        <span class="text-lg font-bold text-white">MacroLog</span>
                    </div>
                    <div class="text-center md:text-right text-sm">
                        <p>&copy; 2025 MacroLog. Built with Laravel & Vue.js.</p>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>
