<script setup>
import { computed } from 'vue';

const props = defineProps({
    label: {
        type: String,
        required: true,
    },
    consumed: {
        type: Number,
        default: 0,
    },
    goal: {
        type: Number,
        default: 0,
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

const consumedRounded = computed(() => {
    const value = Number(props.consumed) || 0;
    return parseFloat(value.toFixed(2));
});

const goalRounded = computed(() => {
    const value = Number(props.goal) || 0;
    return parseFloat(value.toFixed(2));
});

const percentage = computed(() => {
    const consumed = Number(props.consumed) || 0;
    const goal = Number(props.goal) || 0;
    if (!goal || goal === 0) return 0;
    const value = Math.min((consumed / goal) * 100, 100);
    return parseFloat(value.toFixed(2));
});

const remaining = computed(() => {
    const consumed = Number(props.consumed) || 0;
    const goal = Number(props.goal) || 0;
    if (!goal || goal === 0) return 0;
    const value = Math.max(goal - consumed, 0);
    return parseFloat(value.toFixed(2));
});

const progressColor = computed(() => {
    const consumed = Number(props.consumed) || 0;
    const goal = Number(props.goal) || 0;
    if (!goal || goal === 0) return 'bg-gray-400';
    const p = (consumed / goal) * 100;
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
    <div :class="['bg-gradient-to-br rounded-xl p-4 border shadow-sm hover:shadow-md transition-all duration-200', colorClasses.bg, colorClasses.border]">
        <!-- Mobile & Desktop: Optimized Layout -->
        <div class="flex flex-col lg:flex-col gap-3">
            <!-- Top Section: Icon, Label, and Value -->
            <div class="flex items-center gap-2.5">
                <!-- Icon -->
                <div :class="['w-7 h-7 lg:w-8 lg:h-8 rounded-lg flex items-center justify-center shadow-sm flex-shrink-0', colorClasses.iconBg]">
                    <div v-html="icon" class="w-3.5 h-3.5 lg:w-4 lg:h-4 text-white"></div>
                </div>

                <!-- Label and Value -->
                <div class="flex flex-col gap-0.5 flex-1 min-w-0">
                    <span :class="['text-xs font-semibold', colorClasses.text]">{{ label }}</span>
                    <div class="flex items-baseline gap-1">
                        <span :class="['text-lg lg:text-lg font-bold leading-none', colorClasses.text]">{{ consumedRounded }}</span>
                        <span :class="['text-sm font-normal', colorClasses.text]">/{{ goalRounded }}</span>
                        <span :class="['text-xs font-medium', colorClasses.subtext]">{{ unit }}</span>
                    </div>
                </div>

                <!-- Percentage Badge -->
                <div :class="['text-sm font-bold px-2 py-1 rounded-md bg-white/40', colorClasses.text]">
                    {{ percentage }}%
                </div>
            </div>

            <!-- Bottom Section: Progress Bar -->
            <div class="flex flex-col gap-1.5">
                <!-- Progress Bar -->
                <div class="relative w-full h-2 bg-white/40 rounded-full overflow-hidden">
                    <div
                        :class="['h-full rounded-full transition-all duration-500', progressColor]"
                        :style="{ width: `${percentage}%` }"
                    ></div>
                </div>

                <!-- Remaining Text -->
                <div class="flex justify-end">
                    <span :class="['text-xs font-medium', colorClasses.subtext]">
                        {{ remaining }}{{ unit }} remaining
                    </span>
                </div>
            </div>
        </div>
    </div>
</template>
