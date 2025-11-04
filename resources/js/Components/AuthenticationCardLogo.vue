<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <Link :href="'/'" class="flex flex-col items-center space-y-3">
        <img
            src="/icons/application.png"
            alt="MacroLog"
            class="w-16 h-16 transform transition-transform hover:scale-110 duration-300"
        />
        <span class="text-2xl font-bold bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 bg-clip-text text-transparent">
            MacroLog
        </span>
    </Link>
</template>
