<template>
  <form class="space-y-4 mb-8" @submit.prevent="submitForm">
    <!-- Name -->
    <div>
      <label for="name" class="block text-sm font-medium text-gray-700">Name:</label>
      <input type="text" id="name" v-model="formData.name"
        class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md" />
    </div>
    <!-- Email -->
    <div>
      <label for="email" class="block text-sm font-medium text-gray-700">Email:</label>
      <input type="email" id="email" v-model="formData.email"
        class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md" />
    </div>
    <!-- Message -->
    <div>
      <label for="message" class="block text-sm font-medium text-gray-700">Message:</label>
      <textarea id="message" v-model="formData.message"
        class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md"></textarea>
    </div>
    <!-- Image -->
    <div>
      <label for="image" class="block text-sm font-medium text-gray-700">Image:</label>
      <input type="file" id="image" @change="onFileChange" class="mt-1 block w-full" />
    </div>
    <!-- Submit button -->
    <button type="submit"
      class="w-full mt-4 px-4 py-2 text-white bg-blue-600 hover:bg-blue-700 rounded-md">Submit</button>
  </form>
</template>
  
<script>
import { ref } from "vue";
import { router, useForm } from "@inertiajs/vue3";
import axios from "axios";

export default {
  emits: ['message-created'],
  setup(props, context) {   
    const formData = useForm({
      name: "",
      email: "",
      message: "",
      image: null
    });

    function onFileChange(event) {
      formData.value.image = event.target.files[0];
    }

    async function submitForm() {
      try {
        const response = await axios.post('/guestbook', formData);
        if (response.status === 200) {
          console.log('Form submitted successfully!');
          // emit an event to the parent component
          context.emit('message-created');
        }
      } catch (error) {
        console.error('Error submitting form: ', error);
      }
    }

    return {
      formData,
      onFileChange,
      submitForm
    };
  },
};
</script>
  