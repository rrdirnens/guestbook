<template>
    <div class="bg-white shadow-md rounded-lg p-6 mx-auto my-auto max-w-lg">
        <flash-messages></flash-messages>
        <h2 class="text-2xl font-semibold mb-6">Edit message</h2>
        <GuestbookForm :initialMessage="message"/>
            

        {{ message }}
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
    props: {
        message: Object,
        errors: Object,
        input: Object,
    }
};
</script>
  