<script setup>
import { Link } from '@inertiajs/vue3';
import SectionHeader from '../Shared/SectionHeader.vue';
import ScreenshotCard from '../Shared/ScreenshotCard.vue';

defineProps({
    canRegister: {
        type: Boolean,
        default: false,
    },
});
</script>

<template>
    <!-- Screenshots Section -->
    <section id="screenshots" class="py-20 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-gray-50 via-indigo-50 to-purple-50 overflow-hidden scroll-mt-16">
        <div class="max-w-7xl mx-auto">
            <SectionHeader
                title="See MacroLog in Action"
                subtitle="Experience the sleek, intuitive interface designed for effortless nutrition tracking on any device"
            />

            <!-- Screenshots Grid -->
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-8 items-start">
                <ScreenshotCard
                    image-src="/screens/dashboard.png"
                    image-alt="MacroLog Dashboard - Track your daily meals and nutrition goals"
                    badge-text="Dashboard"
                    badge-gradient="from-indigo-600 to-purple-600"
                    title="Smart Dashboard"
                    description="Track your daily progress with beautiful, color-coded macro displays"
                    hover-rotation="-rotate-1"
                />

                <ScreenshotCard
                    image-src="/screens/history.png"
                    image-alt="MacroLog History - View your 7-day meal history and trends"
                    badge-text="History"
                    badge-gradient="from-orange-500 to-red-500"
                    title="7-Day History"
                    description="Review your meal history and nutrition trends over the past week"
                    animation-delay="0.15s"
                    hover-rotation="rotate-1"
                />

                <ScreenshotCard
                    image-src="/screens/goal-settings.png"
                    image-alt="MacroLog Goal Settings - Set personalized nutrition targets"
                    badge-text="Goals"
                    badge-gradient="from-emerald-500 to-teal-500"
                    title="Custom Goals"
                    description="Set personalized targets based on your weight goals and activity"
                    animation-delay="0.3s"
                    hover-rotation="-rotate-1"
                />

                <ScreenshotCard
                    image-src="/screens/goal-dashboard.png"
                    image-alt="MacroLog Goal Dashboard - Monitor your progress towards nutrition goals"
                    badge-text="Progress"
                    badge-gradient="from-blue-500 to-cyan-500"
                    title="Goal Progress"
                    description="Monitor your progress and stay on track with visual goal tracking"
                    animation-delay="0.45s"
                    hover-rotation="rotate-1"
                />
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
</template>
