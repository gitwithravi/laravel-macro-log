<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { onMounted } from 'vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import GoogleSignIn from '@/Components/GoogleSignIn.vue';
import Turnstile from '@/Components/Turnstile.vue';

const form = useForm({
    name: '',
    email: '',
    date_of_birth: '',
    gender: '',
    password: '',
    password_confirmation: '',
    terms: false,
    timezone: 'UTC', // Default to UTC
    'cf-turnstile-response': '', // Turnstile token
});

// Detect user's timezone on mount
onMounted(() => {
    try {
        const timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
        if (timezone) {
            form.timezone = timezone;
        }
    } catch (e) {
        console.warn('Could not detect timezone, using UTC as default');
    }
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <Head title="Register" />

    <div class="min-h-screen bg-gradient-to-br from-indigo-50 via-white to-purple-50 flex flex-col">
        <!-- Mobile-First Container -->
        <div class="flex-1 flex flex-col justify-center px-4 py-12 sm:px-6 lg:px-8">
            <div class="w-full max-w-md mx-auto">
                <!-- Logo/Header -->
                <div class="text-center mb-8">
                    <h1 class="text-4xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                        MacroLog
                    </h1>
                    <p class="mt-2 text-gray-600">Create your account to get started</p>
                </div>

                <!-- Register Form Card -->
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                    <div class="p-6 sm:p-8">
                        <!-- Google Sign-In -->
                        <div class="mb-6">
                            <GoogleSignIn :show-one-tap="false" mode="signup" />
                        </div>

                        <!-- Divider -->
                        <div class="relative mb-6">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-gray-200"></div>
                            </div>
                            <div class="relative flex justify-center text-sm">
                                <span class="px-4 bg-white text-gray-500 font-medium">Or register with email</span>
                            </div>
                        </div>
                    </div>

                    <form @submit.prevent="submit" class="px-6 pb-6 sm:px-8 sm:pb-8 space-y-5">
                        <!-- Name -->
                        <div>
                            <InputLabel for="name" value="Full Name" class="text-sm font-semibold" />
                            <TextInput
                                id="name"
                                v-model="form.name"
                                type="text"
                                class="mt-2 block w-full text-base"
                                required
                                autofocus
                                autocomplete="name"
                                placeholder="John Doe"
                            />
                            <InputError class="mt-2" :message="form.errors.name" />
                        </div>

                        <!-- Email -->
                        <div>
                            <InputLabel for="email" value="Email Address" class="text-sm font-semibold" />
                            <TextInput
                                id="email"
                                v-model="form.email"
                                type="email"
                                class="mt-2 block w-full text-base"
                                required
                                autocomplete="username"
                                placeholder="you@example.com"
                            />
                            <InputError class="mt-2" :message="form.errors.email" />
                        </div>

                        <!-- Date of Birth & Gender in Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Date of Birth -->
                            <div>
                                <InputLabel for="date_of_birth" value="Date of Birth" class="text-sm font-semibold" />
                                <TextInput
                                    id="date_of_birth"
                                    v-model="form.date_of_birth"
                                    type="date"
                                    class="mt-2 block w-full text-base"
                                    required
                                    autocomplete="bday"
                                />
                                <InputError class="mt-2" :message="form.errors.date_of_birth" />
                            </div>

                            <!-- Gender -->
                            <div>
                                <InputLabel for="gender" value="Gender" class="text-sm font-semibold" />
                                <select
                                    id="gender"
                                    v-model="form.gender"
                                    class="mt-2 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-base"
                                    required
                                >
                                    <option value="">Select</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                    <option value="other">Other</option>
                                    <option value="prefer_not_to_say">Prefer not to say</option>
                                </select>
                                <InputError class="mt-2" :message="form.errors.gender" />
                            </div>
                        </div>

                        <!-- Password -->
                        <div>
                            <InputLabel for="password" value="Password" class="text-sm font-semibold" />
                            <TextInput
                                id="password"
                                v-model="form.password"
                                type="password"
                                class="mt-2 block w-full text-base"
                                required
                                autocomplete="new-password"
                                placeholder="••••••••"
                            />
                            <InputError class="mt-2" :message="form.errors.password" />
                            <p class="mt-1.5 text-xs text-gray-500">Must be at least 8 characters</p>
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <InputLabel for="password_confirmation" value="Confirm Password" class="text-sm font-semibold" />
                            <TextInput
                                id="password_confirmation"
                                v-model="form.password_confirmation"
                                type="password"
                                class="mt-2 block w-full text-base"
                                required
                                autocomplete="new-password"
                                placeholder="••••••••"
                            />
                            <InputError class="mt-2" :message="form.errors.password_confirmation" />
                        </div>

                        <!-- Terms and Conditions -->
                        <div v-if="$page.props.jetstream.hasTermsAndPrivacyPolicyFeature" class="pt-2">
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input
                                        id="terms"
                                        v-model="form.terms"
                                        type="checkbox"
                                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 h-4 w-4"
                                        required
                                    />
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="terms" class="text-gray-700">
                                        I agree to the
                                        <a target="_blank" :href="route('terms.show')" class="font-medium text-indigo-600 hover:text-indigo-500">Terms of Service</a>
                                        and
                                        <a target="_blank" :href="route('policy.show')" class="font-medium text-indigo-600 hover:text-indigo-500">Privacy Policy</a>
                                    </label>
                                </div>
                            </div>
                            <InputError class="mt-2" :message="form.errors.terms" />
                        </div>

                        <!-- Cloudflare Turnstile -->
                        <div class="pt-2">
                            <Turnstile
                                v-model="form['cf-turnstile-response']"
                                :site-key="$page.props.turnstileSiteKey"
                                theme="light"
                                size="normal"
                            />
                            <InputError class="mt-2" :message="form.errors['cf-turnstile-response']" />
                        </div>

                        <!-- Submit Button -->
                        <PrimaryButton
                            class="w-full justify-center py-3 text-base font-semibold mt-6"
                            :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing"
                        >
                            <span v-if="!form.processing">Create Account</span>
                            <span v-else>Creating account...</span>
                        </PrimaryButton>

                        <!-- Login Link -->
                        <div class="text-center pt-4 border-t border-gray-100">
                            <p class="text-sm text-gray-600">
                                Already have an account?
                                <Link :href="route('login')" class="font-medium text-indigo-600 hover:text-indigo-500 transition">
                                    Sign in →
                                </Link>
                            </p>
                        </div>
                    </form>
                </div>

                <!-- Footer -->
                <p class="mt-8 text-center text-sm text-gray-500">
                    Track your macros, achieve your goals
                </p>
            </div>
        </div>
    </div>
</template>
