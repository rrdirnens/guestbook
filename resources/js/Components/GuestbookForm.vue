<template>
  <form class="space-y-4 mb-8" @submit.prevent="submitForm($event)">
    <!-- Name -->
    <div>
      <label for="name" class="block text-sm font-medium text-gray-700">Name:</label>
      <input type="text" id="name" v-model="formData.name" :readonly="initialMessage"
        class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md" />
      <div v-if="formData.errors.name">
        <p class="text-red-500">{{ formData.errors.name }}</p>
      </div>
    </div>

    <!-- Email -->
    <div>
      <label for="email" class="block text-sm font-medium text-gray-700">Email:</label>
      <input type="email" id="email" v-model="formData.email" :readonly="initialMessage"
        class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md" />
      <div v-if="formData.errors.email">
        <p class="text-red-500">{{ formData.errors.email }}</p>
      </div>
    </div>

    <!-- Message -->
    <div>
      <label for="message" class="block text-sm font-medium text-gray-700">Message:</label>
      <textarea id="message" v-model="formData.message"
        class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md"></textarea>
      <div v-if="formData.errors.message">
        <p class="text-red-500">{{ formData.errors.message }}</p>
      </div>
    </div>
    <!-- Image -->
    <div>
      <label for="image" class="block text-sm font-medium text-gray-700">Image:</label>
      <input type="file" id="image" @change="onFileChange" class="mt-1 block w-full" />
      <div v-if="initialMessage && initialMessage.image_path">
        <p>Original Image:</p>
        <img :src="initialMessage.image_path" alt="Original Image" class="w-64 h-auto" />
      </div>
      <div v-if="formData.errors.image">
        <p class="text-red-500">{{ formData.errors.image }}</p>
      </div>
    </div>
    <!-- Submit button -->
    <button type="submit"
      class="w-full mt-4 px-4 py-2 text-white bg-blue-600 hover:bg-blue-700 rounded-md">Submit</button>
  </form>
</template>
  
<script>
import { useForm, router } from "@inertiajs/vue3";

export default {
  emits: ['message-created'],
  props: {
    initialMessage: Object,
    errors: Object,
    input: Object,
  },
  setup(props, context) {

    const initialMessage = props.initialMessage;

    const formData = useForm({
      name: initialMessage ? initialMessage.name : "",
      email: initialMessage ? initialMessage.email : "",
      message: initialMessage ? initialMessage.message : "",
      image: initialMessage ? initialMessage.image_path : null
    });


    function onFileChange(event) {
      formData.image = event.target.files[0];
    }

    function submitForm(event) {
      event.preventDefault();
      
      if (typeof formData.image === 'string') {
        formData.image = null;
      } else if (formData.image === null) {
        formData.image = null;
      } else {
        formData.image = formData.image;
      }

      if (initialMessage) {
        formData.post(`/guestbook/${initialMessage.id}`, {
          _method: 'put',
          onSuccess: () => {
            formData.reset();
          }
        });
      } else {
        formData.post('/guestbook', {
          onSuccess: () => {
            formData.reset();
            router.visit('/guestbook', {
              only: ['messages'],
            })
          }
        });
      }
    }

    return {
      formData,
      onFileChange,
      submitForm,
      initialMessage,
    };
  },
};
</script>
  