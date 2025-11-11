<script setup>
import { Head } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';

// Import all section components
import WelcomeNavigation from '@/Components/Welcome/Sections/WelcomeNavigation.vue';
import WelcomeHero from '@/Components/Welcome/Sections/WelcomeHero.vue';
import WelcomeFeatures from '@/Components/Welcome/Sections/WelcomeFeatures.vue';
import WelcomeScreenshots from '@/Components/Welcome/Sections/WelcomeScreenshots.vue';
import WelcomeComparison from '@/Components/Welcome/Sections/WelcomeComparison.vue';
import WelcomeHowItWorks from '@/Components/Welcome/Sections/WelcomeHowItWorks.vue';
import WelcomePricing from '@/Components/Welcome/Sections/WelcomePricing.vue';
import WelcomeFaq from '@/Components/Welcome/Sections/WelcomeFaq.vue';
import WelcomeContact from '@/Components/Welcome/Sections/WelcomeContact.vue';
import WelcomeCta from '@/Components/Welcome/Sections/WelcomeCta.vue';
import WelcomeFooter from '@/Components/Welcome/Sections/WelcomeFooter.vue';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
});

const isHeaderScrolled = ref(false);
const isMobileMenuOpen = ref(false);

const scrollToTop = () => {
    window.scrollTo({ top: 0, behavior: 'smooth' });
};

const toggleMobileMenu = () => {
    isMobileMenuOpen.value = !isMobileMenuOpen.value;
};

const closeMobileMenu = () => {
    isMobileMenuOpen.value = false;
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
        <meta name="description" content="Track your meals effortlessly with AI-powered natural language input. No subscription fees—bring your own OpenAI API key and take control of your nutrition journey. Free forever." />

        <!-- Open Graph / Facebook -->
        <meta property="og:type" content="website" />
        <meta property="og:url" content="https://macrolog.online/" />
        <meta property="og:title" content="MacroLog - Smart Nutrition Tracking Powered by AI" />
        <meta property="og:description" content="Track your meals effortlessly with AI-powered natural language input. No subscription fees, complete control over your data." />
        <meta property="og:image" content="https://macrolog.online/og-image.png" />

        <!-- Twitter -->
        <meta property="twitter:card" content="summary_large_image" />
        <meta property="twitter:url" content="https://macrolog.online/" />
        <meta property="twitter:title" content="MacroLog - Smart Nutrition Tracking Powered by AI" />
        <meta property="twitter:description" content="Track your meals effortlessly with AI-powered natural language input. No subscription fees, complete control over your data." />
        <meta property="twitter:image" content="https://macrolog.online/og-image.png" />

        <!-- Canonical -->
        <link rel="canonical" href="https://macrolog.online/" />

        <!-- Theme Color -->
        <meta name="theme-color" content="#6366f1" />
    </Head>

    <div class="min-h-screen bg-white overflow-x-hidden">
        <!-- Navigation Header -->
        <WelcomeNavigation
            :can-login="canLogin"
            :can-register="canRegister"
            :is-scrolled="isHeaderScrolled"
            :is-mobile-menu-open="isMobileMenuOpen"
            @scroll-to-top="scrollToTop"
            @toggle-mobile-menu="toggleMobileMenu"
            @close-mobile-menu="closeMobileMenu"
        />

        <!-- Hero Section -->
        <WelcomeHero
            :can-login="canLogin"
            :can-register="canRegister"
        />

        <!-- Features Section -->
        <WelcomeFeatures />

        <!-- Screenshots Section -->
        <WelcomeScreenshots :can-register="canRegister" />

        <!-- Comparison Section -->
        <WelcomeComparison />

        <!-- How It Works Section -->
        <WelcomeHowItWorks />

        <!-- Benefits/Pricing Section -->
        <WelcomePricing />

        <!-- FAQ Section -->
        <WelcomeFaq />

        <!-- Contact Section -->
        <WelcomeContact />

        <!-- Final CTA Section -->
        <WelcomeCta
            :can-login="canLogin"
            :can-register="canRegister"
        />

        <!-- Footer -->
        <WelcomeFooter
            :can-login="canLogin"
            :can-register="canRegister"
        />
    </div>
</template>
