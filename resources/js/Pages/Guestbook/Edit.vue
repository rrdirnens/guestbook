<template>
    <div class="bg-white shadow-md rounded-lg p-6 mx-auto my-auto max-w-lg">
        <h2 class="text-2xl font-semibold mb-6">Edit message</h2>
        <GuestbookForm 
            :formAction="`/guestbook/${message.id}`" 
            :formMethod="'put'" 
            :submitText="'Update'"
            :initialMessage="message" 
            :errors="errors" 
            :input="input" />
        {{ message }}
    </div>
</template>
  
<script>
import GuestbookForm from './GuestbookForm.vue';

export default {
    components: {
        GuestbookForm,
    },
    props: {
        message: Object,
        errors: Object,
        input: Object,
    }
};
</script>
  