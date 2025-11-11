<script setup>
import SectionHeader from '../Shared/SectionHeader.vue';
import BenefitCard from '../Shared/BenefitCard.vue';
</script>

<template>
    <!-- Benefits/Social Proof Section -->
    <section id="pricing" class="py-20 px-4 sm:px-6 lg:px-8 bg-white scroll-mt-16">
        <div class="max-w-7xl mx-auto">
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
                <BenefitCard
                    title="No Subscription Fees"
                    description="Bring your own OpenAI API key and pay only for what you use. No hidden costs, no monthly fees. Complete transparency."
                    icon-bg-gradient="from-green-100 to-emerald-100"
                    icon-color="text-green-600"
                    hover-text-color="group-hover:text-green-600"
                >
                    <template #icon-path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </template>
                </BenefitCard>

                <BenefitCard
                    title="Your Data, Your Control"
                    description="Your API keys are encrypted and stored securely. Your nutrition data belongs to you, always private and protected."
                    icon-bg-gradient="from-blue-100 to-indigo-100"
                    icon-color="text-blue-600"
                    hover-text-color="group-hover:text-blue-600"
                    animation-delay="0.2s"
                >
                    <template #icon-path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </template>
                </BenefitCard>

                <BenefitCard
                    title="AI-Powered Intelligence"
                    description="Leveraging GPT-4o-mini for accurate nutritional analysis and personalized insights that help you reach your goals."
                    icon-bg-gradient="from-purple-100 to-pink-100"
                    icon-color="text-purple-600"
                    hover-text-color="group-hover:text-purple-600"
                    animation-delay="0.4s"
                >
                    <template #icon-path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </template>
                </BenefitCard>
            </div>
        </div>
    </section>
</template>
