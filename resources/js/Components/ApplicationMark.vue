<script setup>
import logoUrl from '/public/icons/application.png';
</script>

<template>
    <div class="flex items-center space-x-2">
        <img
            :src="logoUrl"
            alt="MacroLog"
            class="w-9 h-9 transform transition-transform hover:scale-110 duration-300"
        />
        <span class="text-lg font-bold bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 bg-clip-text text-transparent">
            MacroLog
        </span>
    </div>
</template>
