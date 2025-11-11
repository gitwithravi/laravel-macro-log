<script setup>
import SectionHeader from '../Shared/SectionHeader.vue';
import FaqItem from '../Shared/FaqItem.vue';

const faqs = [
    {
        question: 'Why do I need to bring my own OpenAI API key?',
        answer: 'This approach gives you complete control and transparency. You pay OpenAI directly for only what you use (typically just pennies per meal), avoid monthly subscription fees, and maintain full ownership of your data. It\'s more cost-effective and private.',
        delay: '',
    },
    {
        question: 'How much does the OpenAI API cost?',
        answer: 'MacroLog uses GPT-4o-mini, which costs about $0.15 per million input tokens. For most users, this translates to just a few cents per month—far less than traditional nutrition app subscriptions. You can monitor your usage directly in your OpenAI dashboard.',
        delay: '0.1s',
    },
    {
        question: 'Is my nutrition data private and secure?',
        answer: 'Absolutely. Your API keys are encrypted and stored securely in our database. All API calls to OpenAI are made server-side with proper security measures. Your nutrition data is never shared with third parties and remains under your control.',
        delay: '0.2s',
    },
    {
        question: 'What features are included?',
        answer: 'All of them! MacroLog includes natural language meal logging, comprehensive macro tracking, personalized goal management, AI-powered meal insights, 7-day history, nutrition calculator, and PWA support for offline access. No premium tiers or paywalls.',
        delay: '0.3s',
    },
    {
        question: 'Do I need to download an app from the App Store or Play Store?',
        answer: 'No! MacroLog is a Progressive Web App (PWA)—simply visit the website and add it to your home screen for instant access. No app store downloads, no installation hassles, and no extra storage space required. It works just like a native app on any device, and you can even use it offline to view your history and previously logged meals.',
        delay: '0.4s',
    },
];
</script>

<template>
    <!-- FAQ Section -->
    <section id="faq" class="py-20 px-4 sm:px-6 lg:px-8 bg-gray-50 scroll-mt-16">
        <div class="max-w-4xl mx-auto">
            <SectionHeader title="Frequently Asked Questions" />

            <!-- FAQ List -->
            <div class="space-y-6">
                <FaqItem
                    v-for="(faq, index) in faqs"
                    :key="index"
                    :question="faq.question"
                    :answer="faq.answer"
                    :animation-delay="faq.delay"
                />
            </div>
        </div>
    </section>
</template>
