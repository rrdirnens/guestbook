<template>
    <div class="min-h-screen flex items-center justify-center bg-gray-50">
      <div class="w-full max-w-md">
        <h1 class="text-2xl font-semibold text-center mb-6">Reset Password</h1>
        <form @submit.prevent="handleSubmit" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
          <div class="mb-4">
            <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email:</label>
            <input type="email" id="email" v-model="form.email" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-nonefocus:shadow-outline" />
        </div>
        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full">Send Password Reset Link</button>
      </form>
    </div>
  </div>
</template>

<script>
import { useForm } from '@inertiajs/vue3';

export default {
  setup() {
    const form = useForm({
      email: '',
    });

    const handleSubmit = () => {
      form.post(route('password.email'));
    };

    return { form, handleSubmit };
  },
};
</script>
  