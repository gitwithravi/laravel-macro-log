<script setup>
import { ref, computed } from 'vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';

const props = defineProps({
    goals: Array,
});

const showModal = ref(false);
const editingGoal = ref(null);
const showDeleteConfirm = ref(false);
const goalToDelete = ref(null);

const form = useForm({
    current_weight: '',
    target_weight: '',
    daily_goal_calories: '',
    daily_goal_protein: '',
    daily_goal_carb: '',
    daily_goal_fat: '',
    is_active: true,
});

const activeGoal = computed(() => {
    return props.goals.find(goal => goal.is_active);
});

const inactiveGoals = computed(() => {
    return props.goals.filter(goal => !goal.is_active);
});

const openCreateModal = () => {
    editingGoal.value = null;
    form.reset();
    form.clearErrors();
    form.is_active = !activeGoal.value; // Auto-set active if no active goal
    showModal.value = true;
};

const openEditModal = (goal) => {
    editingGoal.value = goal;
    form.current_weight = goal.current_weight;
    form.target_weight = goal.target_weight;
    form.daily_goal_calories = goal.daily_goal_calories;
    form.daily_goal_protein = goal.daily_goal_protein;
    form.daily_goal_carb = goal.daily_goal_carb;
    form.daily_goal_fat = goal.daily_goal_fat;
    form.is_active = goal.is_active;
    form.clearErrors();
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    editingGoal.value = null;
    form.reset();
};

const submitForm = () => {
    if (editingGoal.value) {
        form.put(route('goals.update', editingGoal.value.id), {
            preserveScroll: true,
            onSuccess: () => closeModal(),
        });
    } else {
        form.post(route('goals.store'), {
            preserveScroll: true,
            onSuccess: () => closeModal(),
        });
    }
};

const confirmDelete = (goal) => {
    goalToDelete.value = goal;
    showDeleteConfirm.value = true;
};

const deleteGoal = () => {
    if (goalToDelete.value) {
        router.delete(route('goals.destroy', goalToDelete.value.id), {
            preserveScroll: true,
            onSuccess: () => {
                showDeleteConfirm.value = false;
                goalToDelete.value = null;
            },
        });
    }
};

const toggleActive = (goal) => {
    router.post(route('goals.toggle-active', goal.id), {}, {
        preserveScroll: true,
    });
};

const weightDifference = (goal) => {
    const diff = Math.abs(goal.target_weight - goal.current_weight);
    const type = goal.target_weight < goal.current_weight ? 'lose' : 'gain';
    return { diff: diff.toFixed(1), type };
};
</script>

<template>
    <AppLayout title="Goals">
        <Head title="My Goals" />

        <div class="min-h-screen bg-gray-50 pb-20">
            <!-- Header -->
            <div class="sticky top-0 z-10 bg-white border-b border-gray-200 shadow-sm">
                <div class="max-w-2xl mx-auto px-4 py-4">
                    <div class="flex items-center justify-between">
                        <h1 class="text-2xl font-bold text-gray-900">My Goals</h1>
                        <PrimaryButton @click="openCreateModal" class="text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                            </svg>
                            New Goal
                        </PrimaryButton>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="max-w-2xl mx-auto px-4 py-6 space-y-6">
                <!-- Active Goal -->
                <div v-if="activeGoal" class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-2">
                            <div class="bg-white/20 rounded-full px-3 py-1 text-xs font-semibold">
                                Active Goal
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <button @click="openEditModal(activeGoal)" class="p-2 hover:bg-white/20 rounded-lg transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                </svg>
                            </button>
                            <button @click="confirmDelete(activeGoal)" class="p-2 hover:bg-white/20 rounded-lg transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Weight Progress -->
                    <div class="mb-6">
                        <div class="flex items-end justify-between mb-2">
                            <div>
                                <div class="text-sm opacity-90">Current Weight</div>
                                <div class="text-3xl font-bold">{{ activeGoal.current_weight }} <span class="text-xl">kg</span></div>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                            <div class="text-right">
                                <div class="text-sm opacity-90">Target Weight</div>
                                <div class="text-3xl font-bold">{{ activeGoal.target_weight }} <span class="text-xl">kg</span></div>
                            </div>
                        </div>
                        <div class="text-center text-sm opacity-90">
                            {{ weightDifference(activeGoal).type === 'lose' ? 'Lose' : 'Gain' }} {{ weightDifference(activeGoal).diff }} kg
                        </div>
                    </div>

                    <!-- Daily Goals Grid -->
                    <div class="grid grid-cols-2 gap-3">
                        <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4">
                            <div class="text-xs opacity-80 mb-1">Calories</div>
                            <div class="text-2xl font-bold">{{ activeGoal.daily_goal_calories }}</div>
                            <div class="text-xs opacity-70">kcal/day</div>
                        </div>
                        <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4">
                            <div class="text-xs opacity-80 mb-1">Protein</div>
                            <div class="text-2xl font-bold">{{ activeGoal.daily_goal_protein }}g</div>
                            <div class="text-xs opacity-70">per day</div>
                        </div>
                        <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4">
                            <div class="text-xs opacity-80 mb-1">Carbs</div>
                            <div class="text-2xl font-bold">{{ activeGoal.daily_goal_carb }}g</div>
                            <div class="text-xs opacity-70">per day</div>
                        </div>
                        <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4">
                            <div class="text-xs opacity-80 mb-1">Fat</div>
                            <div class="text-2xl font-bold">{{ activeGoal.daily_goal_fat }}g</div>
                            <div class="text-xs opacity-70">per day</div>
                        </div>
                    </div>
                </div>

                <!-- No Active Goal Message -->
                <div v-else class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto mb-4 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">No Active Goal</h3>
                    <p class="text-gray-600 mb-4">Create your first goal to start tracking your progress!</p>
                    <PrimaryButton @click="openCreateModal">Create Goal</PrimaryButton>
                </div>

                <!-- Inactive Goals -->
                <div v-if="inactiveGoals.length > 0">
                    <h2 class="text-lg font-semibold text-gray-900 mb-3 px-2">Previous Goals</h2>
                    <div class="space-y-3">
                        <div v-for="goal in inactiveGoals" :key="goal.id" class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="text-sm font-medium text-gray-900">{{ goal.current_weight }} kg → {{ goal.target_weight }} kg</span>
                                        <span class="text-xs text-gray-500">({{ weightDifference(goal).diff }} kg)</span>
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ goal.daily_goal_calories }} kcal • {{ goal.daily_goal_protein }}g protein
                                    </div>
                                </div>
                                <div class="flex gap-1">
                                    <button @click="toggleActive(goal)" class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition" title="Set as active">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                    <button @click="openEditModal(goal)" class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                        </svg>
                                    </button>
                                    <button @click="confirmDelete(goal)" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Create/Edit Modal -->
        <teleport to="body">
            <div v-if="showModal" class="fixed inset-0 z-50 overflow-y-auto" @click.self="closeModal">
                <div class="flex min-h-screen items-end justify-center sm:items-center sm:p-0">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

                    <div class="relative bg-white w-full sm:max-w-lg sm:rounded-2xl rounded-t-2xl shadow-xl transform transition-all">
                        <!-- Modal Header -->
                        <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 rounded-t-2xl">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-semibold text-gray-900">
                                    {{ editingGoal ? 'Edit Goal' : 'Create New Goal' }}
                                </h3>
                                <button @click="closeModal" class="text-gray-400 hover:text-gray-500">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Modal Body -->
                        <form @submit.prevent="submitForm" class="px-6 py-6 space-y-5 max-h-[70vh] overflow-y-auto">
                            <!-- Weight Section -->
                            <div class="bg-gray-50 rounded-xl p-4 space-y-4">
                                <h4 class="font-semibold text-gray-900 text-sm">Weight Goals</h4>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <InputLabel for="current_weight" value="Current Weight (kg)" class="text-xs" />
                                        <TextInput
                                            id="current_weight"
                                            v-model="form.current_weight"
                                            type="number"
                                            step="0.01"
                                            class="mt-1 block w-full"
                                            required
                                        />
                                        <InputError :message="form.errors.current_weight" class="mt-1" />
                                    </div>
                                    <div>
                                        <InputLabel for="target_weight" value="Target Weight (kg)" class="text-xs" />
                                        <TextInput
                                            id="target_weight"
                                            v-model="form.target_weight"
                                            type="number"
                                            step="0.01"
                                            class="mt-1 block w-full"
                                            required
                                        />
                                        <InputError :message="form.errors.target_weight" class="mt-1" />
                                    </div>
                                </div>
                            </div>

                            <!-- Daily Nutrition Goals -->
                            <div class="bg-gray-50 rounded-xl p-4 space-y-4">
                                <h4 class="font-semibold text-gray-900 text-sm">Daily Nutrition Goals</h4>

                                <div>
                                    <InputLabel for="daily_goal_calories" value="Calories (kcal)" class="text-xs" />
                                    <TextInput
                                        id="daily_goal_calories"
                                        v-model="form.daily_goal_calories"
                                        type="number"
                                        class="mt-1 block w-full"
                                        required
                                    />
                                    <InputError :message="form.errors.daily_goal_calories" class="mt-1" />
                                </div>

                                <div class="grid grid-cols-3 gap-3">
                                    <div>
                                        <InputLabel for="daily_goal_protein" value="Protein (g)" class="text-xs" />
                                        <TextInput
                                            id="daily_goal_protein"
                                            v-model="form.daily_goal_protein"
                                            type="number"
                                            step="0.01"
                                            class="mt-1 block w-full"
                                            required
                                        />
                                        <InputError :message="form.errors.daily_goal_protein" class="mt-1" />
                                    </div>
                                    <div>
                                        <InputLabel for="daily_goal_carb" value="Carbs (g)" class="text-xs" />
                                        <TextInput
                                            id="daily_goal_carb"
                                            v-model="form.daily_goal_carb"
                                            type="number"
                                            step="0.01"
                                            class="mt-1 block w-full"
                                            required
                                        />
                                        <InputError :message="form.errors.daily_goal_carb" class="mt-1" />
                                    </div>
                                    <div>
                                        <InputLabel for="daily_goal_fat" value="Fat (g)" class="text-xs" />
                                        <TextInput
                                            id="daily_goal_fat"
                                            v-model="form.daily_goal_fat"
                                            type="number"
                                            step="0.01"
                                            class="mt-1 block w-full"
                                            required
                                        />
                                        <InputError :message="form.errors.daily_goal_fat" class="mt-1" />
                                    </div>
                                </div>
                            </div>

                            <!-- Active Toggle -->
                            <div class="flex items-center">
                                <input
                                    id="is_active"
                                    v-model="form.is_active"
                                    type="checkbox"
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                />
                                <label for="is_active" class="ml-3 text-sm text-gray-700">
                                    Set as active goal
                                </label>
                            </div>

                            <!-- Modal Footer -->
                            <div class="flex gap-3 pt-4 border-t border-gray-200">
                                <SecondaryButton @click="closeModal" type="button" class="flex-1">
                                    Cancel
                                </SecondaryButton>
                                <PrimaryButton type="submit" class="flex-1" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                    {{ editingGoal ? 'Update Goal' : 'Create Goal' }}
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </teleport>

        <!-- Delete Confirmation Modal -->
        <teleport to="body">
            <div v-if="showDeleteConfirm" class="fixed inset-0 z-50 overflow-y-auto" @click.self="showDeleteConfirm = false">
                <div class="flex min-h-screen items-end justify-center sm:items-center sm:p-0">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

                    <div class="relative bg-white w-full sm:max-w-md sm:rounded-2xl rounded-t-2xl shadow-xl transform transition-all p-6">
                        <div class="text-center">
                            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                                <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Delete Goal</h3>
                            <p class="text-sm text-gray-600 mb-6">
                                Are you sure you want to delete this goal? This action cannot be undone.
                            </p>
                            <div class="flex gap-3">
                                <SecondaryButton @click="showDeleteConfirm = false" class="flex-1">
                                    Cancel
                                </SecondaryButton>
                                <DangerButton @click="deleteGoal" class="flex-1">
                                    Delete
                                </DangerButton>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </teleport>
    </AppLayout>
</template>
