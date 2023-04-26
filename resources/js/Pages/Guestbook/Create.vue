<template>
    <div class="bg-white shadow-md rounded-lg p-6 mx-auto my-auto max-w-lg">
      <flash-messages></flash-messages>
      <h2 class="text-2xl font-semibold mb-6">Create a new message</h2>
      <GuestbookForm/>
    </div>
  </template>
  
  <script>
  import GuestbookForm from './GuestbookForm.vue';
  import FlashMessages from '@/Components/FlashMessages.vue';

  
  export default {
    components: {
      GuestbookForm,
      FlashMessages
    },
  };
  </script>
  