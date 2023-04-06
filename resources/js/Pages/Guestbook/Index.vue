<template>
    <div class="bg-white shadow-md rounded-lg p-6 mx-auto my-auto max-w-lg">
        <guestbook-form></guestbook-form>
        <message-table :messages="messages" @sort="sortMessages"></message-table>
        <pagination v-if="pagination" :pagination="pagination" @change-page="fetchMessages"></pagination>
    </div>
</template>

<script>
import GuestbookForm from "./GuestbookForm.vue";
import MessageTable from "./MessageTable.vue";
import Pagination from "./Pagination.vue";
import { reactive, toRefs, watchEffect } from "vue";
import { usePage } from "@inertiajs/vue3";
import { router } from "@inertiajs/vue3";

export default {
    components: {
        GuestbookForm,
        MessageTable,
        Pagination,
    },
    setup() {
        const { props } = usePage();
        const state = reactive({
            messages: props.messages,
            pagination: props.pagination,
        });

        watchEffect(() => {
            console.log("messages: "+state.messages);
            console.log("pagination: "+state.pagination); // This is undefined
        });

        async function fetchMessagesHandler(url) {
            const { data } = await router.get(url);
            state.messages = data.messages;
            console.log(data.messages);
            console.log(data.pagination);
            state.pagination = data.pagination;
        }

        function sortMessages(field) {
            fetchMessagesHandler(`/guestbook?sort=${field}`);
        }

        return { ...toRefs(state), fetchMessagesHandler, sortMessages, fetchMessages: fetchMessagesHandler };
    },
};
</script>
