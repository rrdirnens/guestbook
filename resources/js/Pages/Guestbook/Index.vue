<template>
    <div>
        <guestbook-form></guestbook-form>
        <message-table :messages="messages" @sort="sortMessages"></message-table>
        <pagination :pagination="pagination" @change-page="fetchMessages"></pagination>
    </div>
</template>
  
<script>
import GuestbookForm from "./GuestbookForm.vue";
import MessageTable from "./MessageTable.vue";
import Pagination from "./Pagination.vue";
import { router } from "@inertiajs/vue3";

export default {
    components: {
        GuestbookForm,
        MessageTable,
        Pagination,
    },
    props: {
        messages: {
            type: Object,
            default: () => ({}),
        },
        pagination: Object,
    },
    methods: {
        sortMessages(field) {
            router.get("/guestbook", { sort: field });
        },
        fetchMessages(url) {
            router.visit(url);
        },
    },
};
</script>
  