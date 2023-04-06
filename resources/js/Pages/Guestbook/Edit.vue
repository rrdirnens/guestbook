<template>
    <div class="bg-white shadow-md rounded-lg p-6">
        <h2 class="text-2xl font-semibold mb-6">Edit message</h2>
        <GuestbookForm :formAction="`/guestbook/${message.id}`" :formMethod="'put'" :submitText="'Update'"
            :initialMessage="message" @submit="handleSubmit" />
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
    },
    methods: {
        handleSubmit() {
            this.$inertia.put(`/guestbook/${this.message.id}`, this.formData);
        },
    },
};
</script>
  