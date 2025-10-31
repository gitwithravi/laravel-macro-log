<script setup>
defineProps({
    onClick: {
        type: Function,
        required: true,
    },
});
</script>

<template>
    <button
        @click="onClick"
        class="fixed bottom-20 right-4 sm:bottom-8 sm:right-8 z-50 w-14 h-14 bg-gradient-to-br from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white rounded-full shadow-lg hover:shadow-xl transform hover:scale-110 active:scale-95 transition-all duration-200 flex items-center justify-center group"
        aria-label="Add meal"
    >
        <svg
            class="w-7 h-7 transition-transform group-hover:rotate-90"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
        >
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2.5"
                d="M12 4v16m8-8H4"
            />
        </svg>
    </button>
</template>
