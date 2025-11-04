<script setup>
defineProps({
    size: {
        type: String,
        default: 'md', // sm, md, lg, xl
    },
    showText: {
        type: Boolean,
        default: true,
    },
});

const sizeClasses = {
    sm: { icon: 'w-6 h-6', text: 'text-base' },
    md: { icon: 'w-8 h-8', text: 'text-xl' },
    lg: { icon: 'w-12 h-12', text: 'text-2xl' },
    xl: { icon: 'w-16 h-16', text: 'text-3xl' },
};
</script>

<template>
    <div class="flex items-center space-x-2 group">
        <!-- Logo Icon -->
        <img
            :src="'/icons/application.png'"
            alt="MacroLog"
            :class="[
                sizeClasses[size].icon,
                'transform transition-transform group-hover:scale-110 duration-300'
            ]"
        />
        <!-- Logo Text -->
        <span
            v-if="showText"
            :class="[
                sizeClasses[size].text,
                'font-bold bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 bg-clip-text text-transparent',
                'bg-[length:200%_auto] animate-gradient'
            ]"
        >
            MacroLog
        </span>
    </div>
</template>

<style scoped>
@keyframes gradient {
    0%, 100% {
        background-position: 0% center;
    }
    50% {
        background-position: 100% center;
    }
}

.animate-gradient {
    animation: gradient 3s ease infinite;
}
</style>
