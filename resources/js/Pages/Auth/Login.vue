<template>
  <div class="min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md">
      <h1 class="text-2xl font-semibold text-center mb-6">Login</h1>
      <form @submit.prevent="handleSubmit" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <div class="mb-4">
          <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email:</label>
          <input type="email" id="email" v-model="form.email" required
            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" />
        </div>
        <div class="mb-6">
          <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password:</label>
          <input type="password" id="password" v-model="form.password" required
            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" />
        </div>
        <div class="flex items-center justify-between mb-4">
          <input type="checkbox" id="remember" v-model="form.remember" class="mr-2" />
          <label for="remember" class="text-sm text-gray-700">Remember me</label>
        </div>
        <button type="submit"
          class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full">Login</button>
      </form>
      <p class="text-center">
        <Link class="text-blue-500 hover:text-blue-700" href="/password/reset">Forgot your password?</Link>
      </p>
    </div>
  </div>
</template>
  
<script>
import { useForm, Link } from '@inertiajs/vue3';

export default {
  components: { Link },
  setup() {
    const form = useForm({
      email: '',
      password: '',
      remember: false,
    });

    const handleSubmit = () => {
      form.post(route('login'));
    };

    return { form, handleSubmit };
  },
};
</script>
  