<script setup>
import { router } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';

const props = defineProps({
    meal: {
        type: Object,
        required: true,
    },
});

const emit = defineEmits(['edit']);

const showDeleteConfirm = ref(false);
const expanded = ref(false);
const editing = ref(false);
const saving = ref(false);
const draft = ref([]);

const hasComponents = computed(() => Array.isArray(props.meal.components) && props.meal.components.length > 0);

const buildDraft = () => {
    draft.value = (props.meal.components || []).map((c) => ({
        name: c.name,
        grams: Number(c.grams) || 0,
        originalGrams: Number(c.grams) || 0,
        originalCalories: Number(c.calories) || 0,
        originalProtein: Number(c.protein) || 0,
        originalCarbs: Number(c.carbs) || 0,
        originalFat: Number(c.fat) || 0,
    }));
};

watch(
    () => props.meal.components,
    () => {
        if (!editing.value) buildDraft();
    },
    { immediate: true, deep: true },
);

const scaleRow = (row) => {
    const ratio = row.originalGrams > 0 ? row.grams / row.originalGrams : 0;
    return {
        calories: Math.round(row.originalCalories * ratio),
        protein: Math.round(row.originalProtein * ratio * 100) / 100,
        carbs: Math.round(row.originalCarbs * ratio * 100) / 100,
        fat: Math.round(row.originalFat * ratio * 100) / 100,
    };
};

const liveTotals = computed(() => {
    const totals = { calories: 0, protein: 0, carbs: 0, fat: 0 };
    for (const row of draft.value) {
        const s = scaleRow(row);
        totals.calories += s.calories;
        totals.protein += s.protein;
        totals.carbs += s.carbs;
        totals.fat += s.fat;
    }
    return {
        calories: Math.round(totals.calories),
        protein: Math.round(totals.protein * 100) / 100,
        carbs: Math.round(totals.carbs * 100) / 100,
        fat: Math.round(totals.fat * 100) / 100,
    };
});

const startEditing = () => {
    buildDraft();
    editing.value = true;
    expanded.value = true;
};

const cancelEditing = () => {
    editing.value = false;
    buildDraft();
};

const removeRow = (index) => {
    draft.value.splice(index, 1);
};

const saveBreakdown = () => {
    if (draft.value.length === 0) return;
    saving.value = true;
    const payload = draft.value.map((row) => {
        const scaled = scaleRow(row);
        return {
            name: row.name,
            grams: Math.round(row.grams * 10) / 10,
            ...scaled,
        };
    });
    router.put(
        route('frequent-meals.components.update', props.meal.id),
        { components: payload },
        {
            preserveScroll: true,
            onSuccess: () => {
                editing.value = false;
            },
            onFinish: () => {
                saving.value = false;
            },
        },
    );
};

const deleteMeal = () => {
    router.delete(route('frequent-meals.destroy', props.meal.id), {
        preserveScroll: true,
        onSuccess: () => {
            showDeleteConfirm.value = false;
        },
    });
};

const editMeal = () => {
    emit('edit', props.meal);
};
</script>

<template>
    <div class="bg-white rounded-2xl border border-gray-200 p-4 hover:shadow-md transition-shadow">
        <!-- Header -->
        <div class="flex items-start justify-between mb-3">
            <div class="flex-1">
                <h3 class="font-semibold text-gray-900 text-base">{{ meal.meal_name }}</h3>
                <p class="text-xs text-gray-500 mt-1">
                    Saved meal
                </p>
            </div>
            <div class="flex items-center gap-1">
                <!-- Edit Button -->
                <button
                    @click="editMeal"
                    class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                    aria-label="Edit meal"
                    title="Edit meal"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </button>

                <!-- Delete Button -->
                <button
                    @click="showDeleteConfirm = true"
                    class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors"
                    aria-label="Delete meal"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Nutrition Grid -->
        <div class="grid grid-cols-4 gap-2">
            <!-- Calories -->
            <div class="bg-orange-50 rounded-lg p-2 text-center border border-orange-100">
                <div class="text-xs text-orange-700 font-medium mb-1">Cal</div>
                <div class="text-sm font-bold text-orange-900">{{ editing ? liveTotals.calories : meal.calories }}</div>
            </div>

            <!-- Protein -->
            <div class="bg-blue-50 rounded-lg p-2 text-center border border-blue-100">
                <div class="text-xs text-blue-700 font-medium mb-1">Pro</div>
                <div class="text-sm font-bold text-blue-900">{{ editing ? liveTotals.protein : meal.protein }}g</div>
            </div>

            <!-- Carbs -->
            <div class="bg-emerald-50 rounded-lg p-2 text-center border border-emerald-100">
                <div class="text-xs text-emerald-700 font-medium mb-1">Carb</div>
                <div class="text-sm font-bold text-emerald-900">{{ editing ? liveTotals.carbs : meal.carbs }}g</div>
            </div>

            <!-- Fat -->
            <div class="bg-purple-50 rounded-lg p-2 text-center border border-purple-100">
                <div class="text-xs text-purple-700 font-medium mb-1">Fat</div>
                <div class="text-sm font-bold text-purple-900">{{ editing ? liveTotals.fat : meal.fat }}g</div>
            </div>
        </div>

        <!-- Breakdown toggle -->
        <div v-if="hasComponents" class="mt-3">
            <button
                v-if="!editing"
                type="button"
                @click="expanded = !expanded"
                class="inline-flex items-center gap-1 text-xs font-medium text-indigo-600 hover:text-indigo-800"
            >
                <svg class="w-3.5 h-3.5 transition-transform" :class="{ 'rotate-90': expanded }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                {{ expanded ? 'Hide' : 'Show' }} breakdown ({{ meal.components.length }} item{{ meal.components.length === 1 ? '' : 's' }})
            </button>
        </div>

        <!-- Breakdown list -->
        <div v-if="hasComponents && (expanded || editing)" class="mt-3 space-y-1.5 border-t border-gray-100 pt-3">
            <div
                v-for="(row, index) in draft"
                :key="index"
                class="flex items-center gap-2 text-xs"
            >
                <div class="flex-1 min-w-0">
                    <div class="text-gray-800 font-medium truncate">{{ row.name }}</div>
                    <div class="text-gray-500 text-[11px] tabular-nums">
                        <template v-if="editing">
                            {{ scaleRow(row).calories }} cal · {{ scaleRow(row).protein }}p · {{ scaleRow(row).carbs }}c · {{ scaleRow(row).fat }}f
                        </template>
                        <template v-else>
                            {{ row.originalCalories }} cal · {{ row.originalProtein }}p · {{ row.originalCarbs }}c · {{ row.originalFat }}f
                        </template>
                    </div>
                </div>
                <div class="flex items-center gap-1 shrink-0">
                    <input
                        v-if="editing"
                        type="number"
                        step="1"
                        min="0"
                        max="5000"
                        v-model.number="row.grams"
                        class="w-16 px-1.5 py-1 text-xs text-right tabular-nums border border-gray-300 rounded focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"
                        :disabled="row.originalGrams <= 0"
                        :title="row.originalGrams <= 0 ? 'Cannot scale — original weight missing' : ''"
                    />
                    <span v-else class="tabular-nums text-gray-700 w-16 text-right">{{ row.grams }}g</span>
                    <span v-if="editing" class="text-gray-400">g</span>
                    <button
                        v-if="editing"
                        type="button"
                        @click="removeRow(index)"
                        class="p-1 text-gray-400 hover:text-red-500 rounded"
                        aria-label="Remove item"
                    >
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Edit controls -->
            <div class="flex gap-2 pt-2">
                <template v-if="!editing">
                    <button
                        type="button"
                        @click="startEditing"
                        class="text-xs font-medium text-indigo-600 hover:text-indigo-800"
                    >
                        Edit portions
                    </button>
                </template>
                <template v-else>
                    <button
                        type="button"
                        @click="saveBreakdown"
                        :disabled="saving || draft.length === 0"
                        class="px-3 py-1 text-xs font-medium bg-indigo-600 hover:bg-indigo-700 disabled:bg-gray-300 text-white rounded transition-colors"
                    >
                        {{ saving ? 'Saving…' : 'Save' }}
                    </button>
                    <button
                        type="button"
                        @click="cancelEditing"
                        :disabled="saving"
                        class="px-3 py-1 text-xs font-medium bg-gray-100 hover:bg-gray-200 text-gray-700 rounded transition-colors"
                    >
                        Cancel
                    </button>
                </template>
            </div>
        </div>

        <!-- Delete Confirmation -->
        <teleport to="body">
            <div
                v-if="showDeleteConfirm"
                class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50"
                @click.self="showDeleteConfirm = false"
            >
                <div class="bg-white rounded-2xl p-6 max-w-sm w-full shadow-xl">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Delete Frequent Meal?</h3>
                    <p class="text-sm text-gray-600 mb-6">
                        Are you sure you want to delete "{{ meal.meal_name }}"? This action cannot be undone.
                    </p>
                    <div class="flex gap-3">
                        <button
                            @click="showDeleteConfirm = false"
                            class="flex-1 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors text-sm font-medium"
                        >
                            Cancel
                        </button>
                        <button
                            @click="deleteMeal"
                            class="flex-1 px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors text-sm font-medium"
                        >
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        </teleport>
    </div>
</template>
