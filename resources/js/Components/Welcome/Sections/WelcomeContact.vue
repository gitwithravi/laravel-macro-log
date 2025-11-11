<script setup>
import { Link } from '@inertiajs/vue3';
import SectionHeader from '../Shared/SectionHeader.vue';
</script>

<template>
    <!-- Contact Section -->
    <section id="contact" class="py-20 px-4 sm:px-6 lg:px-8 bg-white scroll-mt-16">
        <div class="max-w-4xl mx-auto">
            <SectionHeader
                title="Get in Touch"
                subtitle="Have questions? We're here to help!"
            />

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
                    <span class="text-sm font-medium">Available Monday - Friday, 9 AM - 5 PM EST</span>
                </div>
            </div>
        </div>
    </section>
</template>
