<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import GoogleSignIn from '@/Components/GoogleSignIn.vue';

defineProps({
    canResetPassword: Boolean,
    canRegister: Boolean,
    status: String,
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.transform(data => ({
        ...data,
        remember: form.remember ? 'on' : '',
    })).post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Log in" />

    <div class="min-h-screen bg-gradient-to-br from-indigo-50 via-white to-purple-50 flex flex-col">
        <!-- Mobile-First Container -->
        <div class="flex-1 flex flex-col justify-center px-4 py-12 sm:px-6 lg:px-8">
            <div class="w-full max-w-md mx-auto">
                <!-- Logo/Header -->
                <div class="text-center mb-8">
                    <Link :href="route('dashboard')" class="inline-block">
                        <h1 class="text-4xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                            MacroLog
                        </h1>
                    </Link>
                    <p class="mt-2 text-gray-600">Welcome back! Sign in to your account</p>
                </div>

                <!-- Status Message -->
                <div v-if="status" class="mb-4 p-4 rounded-xl bg-green-50 border border-green-200">
                    <p class="text-sm font-medium text-green-800">{{ status }}</p>
                </div>

                <!-- Login Form Card -->
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                    <div class="p-6 sm:p-8">
                        <!-- Google Sign-In -->
                        <div class="mb-6">
                            <GoogleSignIn :show-one-tap="true" mode="signin" />
                        </div>

                        <!-- Divider -->
                        <div class="relative mb-6">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-gray-200"></div>
                            </div>
                            <div class="relative flex justify-center text-sm">
                                <span class="px-4 bg-white text-gray-500 font-medium">Or continue with email</span>
                            </div>
                        </div>
                    </div>

                    <form @submit.prevent="submit" class="px-6 pb-6 sm:px-8 sm:pb-8 space-y-6">
                        <!-- Email -->
                        <div>
                            <InputLabel for="email" value="Email Address" class="text-sm font-semibold" />
                            <TextInput
                                id="email"
                                v-model="form.email"
                                type="email"
                                class="mt-2 block w-full text-base"
                                required
                                autofocus
                                autocomplete="username"
                                placeholder="you@example.com"
                            />
                            <InputError class="mt-2" :message="form.errors.email" />
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
                                autocomplete="current-password"
                                placeholder="••••••••"
                            />
                            <InputError class="mt-2" :message="form.errors.password" />
                        </div>

                        <!-- Remember Me -->
                        <div class="flex items-center">
                            <input
                                id="remember"
                                v-model="form.remember"
                                type="checkbox"
                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 h-4 w-4"
                            />
                            <label for="remember" class="ml-3 text-sm text-gray-700">
                                Remember me for 30 days
                            </label>
                        </div>

                        <!-- Submit Button -->
                        <PrimaryButton
                            class="w-full justify-center py-3 text-base font-semibold"
                            :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing"
                        >
                            <span v-if="!form.processing">Sign In</span>
                            <span v-else>Signing in...</span>
                        </PrimaryButton>

                        <!-- Links -->
                        <div class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-4 border-t border-gray-100">
                            <Link
                                v-if="canResetPassword"
                                :href="route('password.request')"
                                class="text-sm font-medium text-indigo-600 hover:text-indigo-500 transition"
                            >
                                Forgot password?
                            </Link>

                            <Link
                                v-if="canRegister"
                                :href="route('register')"
                                class="text-sm font-medium text-indigo-600 hover:text-indigo-500 transition"
                            >
                                Create an account →
                            </Link>
                        </div>
                    </form>
                </div>

                <!-- Footer -->
                <p class="mt-8 text-center text-sm text-gray-500">
                    By signing in, you agree to our
                    <a href="#" class="font-medium text-gray-700 hover:text-gray-900">Terms</a>
                    and
                    <a href="#" class="font-medium text-gray-700 hover:text-gray-900">Privacy Policy</a>
                </p>
            </div>
        </div>
    </div>
</template>
