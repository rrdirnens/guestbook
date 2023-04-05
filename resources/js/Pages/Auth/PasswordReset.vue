<template>
    <div class="min-h-screen flex items-center justify-center bg-gray-100">
        <div class="bg-white p-6 rounded shadow-md w-full sm:w-96">
            <form @submit.prevent="submit">
                <h2 class="text-2xl font-semibold mb-5">Reset Password</h2>

                <input type="hidden" name="token" v-model="form.token" />

                <div class="mb-4">
                    <label for="email" class="block mb-2">Email</label>
                    <input type="email" id="email" v-model="form.email"
                        class="w-full px-3 py-2 border border-gray-300 rounded" />
                </div>

                <div class="mb-4">
                    <label for="password" class="block mb-2">New Password</label>
                    <input type="password" id="password" v-model="form.password"
                        class="w-full px-3 py-2 border border-gray-300 rounded" />
                </div>

                <div class="mb-4">
                    <label for="password_confirmation" class="block mb-2">Confirm Password</label>
                    <input type="password" id="password_confirmation" v-model="form.password_confirmation"
                        class="w-full px-3 py-2 border border-gray-300 rounded" />
                </div>

                <button type="submit" class="w-full bg-blue-500 text-white font-bold py-2 rounded"
                    :class="{ 'opacity-50': form.processing }" :disabled="form.processing">
                    Reset Password
                </button>
            </form>
        </div>

    </div>
</template>
<script>
import { useForm } from '@inertiajs/vue3'

export default {
    setup() {
        const form = useForm({
            token: '',
            email: '',
            password: '',
            password_confirmation: '',
        })

        function submit() {
            form.post(route('password.update'))
        }

        return { form, submit }
    },
}
</script>
  