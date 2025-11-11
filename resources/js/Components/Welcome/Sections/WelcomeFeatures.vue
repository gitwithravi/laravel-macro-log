<script setup>
import SectionHeader from '../Shared/SectionHeader.vue';
import FeatureCard from '../Shared/FeatureCard.vue';
</script>

<template>
    <!-- Features Section -->
    <section id="features" class="py-20 px-4 sm:px-6 lg:px-8 bg-white scroll-mt-16">
        <div class="max-w-7xl mx-auto">
            <SectionHeader
                title="Everything You Need to Achieve Your Goals"
                subtitle="Powerful features designed to make nutrition tracking effortless and effective"
            />

            <!-- Features Grid -->
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1: AI Meal Logging -->
                <FeatureCard
                    title="Natural Language Input"
                    description="Simply describe your meal in plain text. AI automatically extracts calories and macros from any dish."
                    icon-bg-class="bg-indigo-100"
                    hover-icon-bg-class="group-hover:bg-indigo-600"
                    icon-color-class="text-indigo-600"
                    hover-icon-color-class="group-hover:text-white"
                    hover-border-color="hover:border-indigo-200"
                    hover-text-color="group-hover:text-indigo-600"
                >
                    <template #icon-path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                    </template>
                </FeatureCard>

                <!-- Feature 2: Macro Tracking -->
                <FeatureCard
                    title="Color-Coded Macros"
                    description="Track protein, carbs, and fats with beautiful, intuitive color-coding. See your nutrition at a glance."
                    icon-bg-class="bg-gradient-to-br from-orange-100 via-blue-100 to-purple-100"
                    icon-color-class="text-indigo-600"
                    hover-border-color="hover:border-orange-200"
                    hover-text-color="group-hover:text-orange-600"
                    animation-delay="0.1s"
                >
                    <template #icon-path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </template>
                </FeatureCard>

                <!-- Feature 3: Goal Management -->
                <FeatureCard
                    title="Personalized Goals"
                    description="Set custom nutrition targets based on your weight goals, timeline, and activity level."
                    icon-bg-class="bg-indigo-100"
                    hover-icon-bg-class="group-hover:bg-indigo-600"
                    icon-color-class="text-indigo-600"
                    hover-icon-color-class="group-hover:text-white"
                    hover-border-color="hover:border-indigo-200"
                    hover-text-color="group-hover:text-indigo-600"
                    animation-delay="0.2s"
                >
                    <template #icon-path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </template>
                </FeatureCard>

                <!-- Feature 4: Smart Insights -->
                <FeatureCard
                    title="AI-Powered Insights"
                    description="Get intelligent feedback on your meals with context-aware recommendations and practical suggestions."
                    icon-bg-class="bg-purple-100"
                    hover-icon-bg-class="group-hover:bg-purple-600"
                    icon-color-class="text-purple-600"
                    hover-icon-color-class="group-hover:text-white"
                    hover-border-color="hover:border-purple-200"
                    hover-text-color="group-hover:text-purple-600"
                    animation-delay="0.3s"
                >
                    <template #icon-path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                    </template>
                </FeatureCard>

                <!-- Feature 5: History Tracking -->
                <FeatureCard
                    title="7-Day History"
                    description="Review your meal history for the past week with daily totals and progress tracking."
                    icon-bg-class="bg-blue-100"
                    hover-icon-bg-class="group-hover:bg-blue-600"
                    icon-color-class="text-blue-600"
                    hover-icon-color-class="group-hover:text-white"
                    hover-border-color="hover:border-blue-200"
                    hover-text-color="group-hover:text-blue-600"
                    animation-delay="0.4s"
                >
                    <template #icon-path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </template>
                </FeatureCard>

                <!-- Feature 6: PWA Support -->
                <FeatureCard
                    title="No App Download Required"
                    description="Skip the app stores entirely—simply add to your home screen for instant access. Works like a native app without taking up storage space."
                    icon-bg-class="bg-emerald-100"
                    hover-icon-bg-class="group-hover:bg-emerald-600"
                    icon-color-class="text-emerald-600"
                    hover-icon-color-class="group-hover:text-white"
                    hover-border-color="hover:border-emerald-200"
                    hover-text-color="group-hover:text-emerald-600"
                    animation-delay="0.5s"
                >
                    <template #icon-path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </template>
                </FeatureCard>
            </div>
        </div>
    </section>
</template>
