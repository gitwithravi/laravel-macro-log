<script setup>
defineProps({
    size: {
        type: String,
        default: 'md',
    },
});

const sizeClasses = {
    sm: 'w-6 h-6',
    md: 'w-10 h-10',
    lg: 'w-16 h-16',
    xl: 'w-20 h-20',
};
</script>

<template>
    <img
        :src="'/icons/application.png'"
        alt="MacroLog"
        :class="[sizeClasses[size], 'transform transition-transform hover:scale-110 duration-300']"
    />
</template>
