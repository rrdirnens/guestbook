<template>
  <form class="space-y-4 mb-8" @submit.prevent="submitForm">
    <!-- Name -->
    <div>
      <label for="name" class="block text-sm font-medium text-gray-700">Name:</label>
      <input type="text" id="name" v-model="formData.name"
        :readonly="initialMessage" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md" />
    </div>

    <!-- Email -->
    <div>
      <label for="email" class="block text-sm font-medium text-gray-700">Email:</label>
      <input type="email" id="email" v-model="formData.email"
        :readonly="initialMessage" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md" />
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
      <div v-if="initialMessage && initialMessage.image_path">
        <p>Original Image:</p>
        <img :src="initialMessage.image_path" alt="Original Image" class="w-64 h-auto" />
      </div>
    </div>
    <!-- Submit button -->
    <button type="submit"
      class="w-full mt-4 px-4 py-2 text-white bg-blue-600 hover:bg-blue-700 rounded-md">Submit</button>

    <div v-if="errors">
      <div v-for="error in errors" :key="error">
        <p class="text-red-500">{{ error[0] }}</p>
      </div>
    </div>
  </form>
</template>
  
<script>
import { useForm } from "@inertiajs/vue3";
import axios from "axios";

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
      // name is empty string if initialMessage is undefined
      name: initialMessage ? initialMessage.name : "",
      email: initialMessage ? initialMessage.email : "",
      message: initialMessage ? initialMessage.message : "",
      image: initialMessage ? initialMessage.image : null
    });


    function onFileChange(event) {
      console.log('onFileChange event file: ');
      console.log(event.target.files[0])
      formData.image = event.target.files[0];
    }

    async function submitForm() {

      // Create a new FormData instance
      const form = new FormData();

      // Append the form data to the FormData instance
      for (const key in formData.data()) {
        if (key === "image" && formData[key] === null) {
          continue; // Skip appending the image if it's null (otherwise it will add null as a string, i.e. "null")
        }
        form.append(key, formData[key]);
      }

      let callMethod;
      let endpoint;
      if (initialMessage) {
        callMethod = 'put';
        endpoint = `/guestbook/${initialMessage.id}`;
      } else {
        callMethod = 'post';
        endpoint = '/guestbook';
      }

      try {
        const response = await axios[callMethod](endpoint, form, {
          headers: {
            'Content-Type': 'multipart/form-data',
          },
        });

        if (response.status === 200) {
          context.emit('message-created');
        }
      } catch (error) {
        console.error('Error submitting form: ', error);
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
  