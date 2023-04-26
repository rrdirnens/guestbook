<template>
    <div class="space-y-6">
        <flash-messages></flash-messages>
        <guestbook-form></guestbook-form>
        <message-table :messages="messages" @sort="sortMessages" @delete-message="deleteMessage"
            class="bg-white shadow-md rounded-lg overflow-hidden"></message-table>
        <pagination v-if="pagination" :pagination="pagination" @change-page="fetchMessages"
            class="flex justify-center items-center"></pagination>
    </div>
</template>

<script>
import GuestbookForm from "./GuestbookForm.vue";
import MessageTable from "@/Components/MessageTable.vue";
import FlashMessages from "@/Components/FlashMessages.vue";
import Pagination from "./Pagination.vue";
import { reactive, toRefs, watchEffect } from "vue";
import { usePage, router } from "@inertiajs/vue3";

export default {
    components: {
        GuestbookForm,
        MessageTable,
        Pagination,
        FlashMessages,
    },
    setup() {
        const { props } = usePage();
        const state = reactive({
            messages: props.messages,
            pagination: props.pagination,
            errors: props.errors,
        });


        watchEffect(() => {


        });

        async function fetchMessagesHandler(url) {
            router.get(url);
        }

        async function deleteMessage(id) {
            router.visit(`/guestbook/${id}`, {
                method: 'delete',
                onSuccess: () => {
                    router.visit('/guestbook', {
                        only: ['messages'],
                    })
                },
                onError: (error) => {
                    console.log('onError');
                    console.log(error);
                },
                preserveState: true,
            });
        }

        function sortMessages(field) {
            fetchMessagesHandler(`/guestbook?sort=${field}`);
        }

        function updateMessages() {
            fetchMessagesHandler('/guestbook');
        }

        return { ...toRefs(state), fetchMessagesHandler, sortMessages, fetchMessages: fetchMessagesHandler, updateMessages, deleteMessage };
    },
};
</script>
