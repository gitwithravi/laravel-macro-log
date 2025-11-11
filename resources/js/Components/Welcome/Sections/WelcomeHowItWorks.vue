<script setup>
import SectionHeader from '../Shared/SectionHeader.vue';
import StepCard from '../Shared/StepCard.vue';
</script>

<template>
    <!-- How It Works Section -->
    <section id="how-it-works" class="py-20 px-4 sm:px-6 lg:px-8 bg-gray-50 scroll-mt-16">
        <div class="max-w-7xl mx-auto">
            <SectionHeader
                title="Get Started in 3 Simple Steps"
                subtitle="Start tracking your nutrition in minutes with no credit card required"
            />

            <!-- Steps -->
            <div class="grid md:grid-cols-3 gap-8">
                <!-- Step 1 -->
                <StepCard
                    :step-number="1"
                    title="Create Your Account"
                    description="Sign up for free—no credit card required. Complete your profile with basic health information."
                    :show-connector="true"
                >
                    <template #icon-path>
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                    </template>
                    <template #mockup-label>Sign Up Form</template>
                </StepCard>

                <!-- Step 2 -->
                <StepCard
                    :step-number="2"
                    title="Add Your API Key"
                    description="Bring your own OpenAI API key. Keep control and pay only for what you use (typically pennies per meal)."
                    :show-connector="true"
                    animation-delay="0.2s"
                >
                    <template #icon-path>
                        <path fill-rule="evenodd" d="M18 8a6 6 0 01-7.743 5.743L10 14l-1 1-1 1H6v2H2v-4l4.257-4.257A6 6 0 1118 8zm-6-4a1 1 0 100 2 2 2 0 012 2 1 1 0 102 0 4 4 0 00-4-4z" clip-rule="evenodd"/>
                    </template>
                    <template #mockup-label>API Settings</template>
                </StepCard>

                <!-- Step 3 -->
                <StepCard
                    :step-number="3"
                    title="Start Logging Meals"
                    description="Describe your meals naturally: &quot;2 chapatis with dal and rice&quot;—AI does the rest!"
                    animation-delay="0.4s"
                >
                    <template #icon-path>
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                    </template>
                    <template #mockup-label>Meal Dashboard</template>
                </StepCard>
            </div>
        </div>
    </section>
</template>
