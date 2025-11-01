<script setup>
import { computed } from 'vue';

const props = defineProps({
    label: {
        type: String,
        required: true,
    },
    consumed: {
        type: Number,
        required: true,
    },
    goal: {
        type: Number,
        required: true,
    },
    unit: {
        type: String,
        default: '',
    },
    color: {
        type: String,
        required: true,
        validator: (value) => ['orange', 'blue', 'emerald', 'purple'].includes(value),
    },
    icon: {
        type: String,
        required: true,
    },
});

const percentage = computed(() => {
    if (!props.goal || props.goal === 0) return 0;
    const value = Math.min((props.consumed / props.goal) * 100, 100);
    return parseFloat(value.toFixed(2));
});

const remaining = computed(() => {
    if (!props.goal || props.goal === 0) return 0;
    const value = Math.max(props.goal - props.consumed, 0);
    return parseFloat(value.toFixed(2));
});

const progressColor = computed(() => {
    if (!props.goal || props.goal === 0) return 'bg-gray-400';
    const p = (props.consumed / props.goal) * 100;
    if (p < 90) return 'bg-emerald-500';
    if (p <= 100) return 'bg-yellow-500';
    if (p <= 110) return 'bg-orange-500';
    return 'bg-red-500';
});

const colorClasses = computed(() => {
    const colors = {
        orange: {
            bg: 'from-orange-50 to-orange-100/50',
            border: 'border-orange-200/50',
            iconBg: 'bg-orange-500',
            text: 'text-orange-900',
            subtext: 'text-orange-700',
        },
        blue: {
            bg: 'from-blue-50 to-blue-100/50',
            border: 'border-blue-200/50',
            iconBg: 'bg-blue-500',
            text: 'text-blue-900',
            subtext: 'text-blue-700',
        },
        emerald: {
            bg: 'from-emerald-50 to-emerald-100/50',
            border: 'border-emerald-200/50',
            iconBg: 'bg-emerald-500',
            text: 'text-emerald-900',
            subtext: 'text-emerald-700',
        },
        purple: {
            bg: 'from-purple-50 to-purple-100/50',
            border: 'border-purple-200/50',
            iconBg: 'bg-purple-500',
            text: 'text-purple-900',
            subtext: 'text-purple-700',
        },
    };
    return colors[props.color];
});
</script>

<template>
    <div :class="['bg-gradient-to-br rounded-xl p-4 border', colorClasses.bg, colorClasses.border]">
        <!-- Header with Icon and Label -->
        <div class="flex items-center gap-2 mb-3">
            <div :class="['w-8 h-8 rounded-lg flex items-center justify-center', colorClasses.iconBg]">
                <div v-html="icon" class="w-4 h-4 text-white"></div>
            </div>
            <span :class="['text-xs font-semibold', colorClasses.text]">{{ label }}</span>
        </div>

        <!-- Progress Value -->
        <div :class="['text-2xl font-bold mb-1', colorClasses.text]">
            {{ consumed }}
            <span class="text-lg font-normal">/{{ goal }}</span>
            <span class="text-base font-medium ml-1">{{ unit }}</span>
        </div>

        <!-- Progress Bar -->
        <div class="relative w-full h-2 bg-gray-200 rounded-full overflow-hidden mb-2">
            <div
                :class="['h-full rounded-full transition-all duration-500', progressColor]"
                :style="{ width: `${percentage}%` }"
            ></div>
        </div>

        <!-- Stats -->
        <div class="flex items-center justify-between">
            <span :class="['text-xs', colorClasses.subtext]">
                {{ percentage }}%
            </span>
            <span :class="['text-xs', colorClasses.subtext]">
                {{ remaining }}{{ unit }} left
            </span>
        </div>
    </div>
</template>
