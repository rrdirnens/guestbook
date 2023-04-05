<template>
    <div class="bg-white shadow-md rounded-lg p-6 mx-auto my-auto max-w-lg">
      <h2 class="text-2xl font-semibold mb-6">Create a new message</h2>
      <GuestbookForm
        :formAction="'/guestbook'"
        :formMethod="'post'"
        :submitText="'Submit'"
        @submit="handleSubmit"
      />
    </div>
  </template>
  
  <script>
  import GuestbookForm from './GuestbookForm.vue';
  
  export default {
    components: {
      GuestbookForm,
    },
    methods: {
      handleSubmit() {
        this.$inertia.post('/guestbook', this.form);
      },
    },
  };
  </script>
  