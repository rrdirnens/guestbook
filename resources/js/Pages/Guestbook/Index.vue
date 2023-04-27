<template>
    <div class="space-y-6">
        <flash-messages></flash-messages>
        <guestbook-form></guestbook-form>
        <message-table :messages="messages" :sortDirection="direction" @sort="sortMessages" @delete-message="deleteMessage"
            class="bg-white shadow-md rounded-lg overflow-hidden">
        </message-table>

        <pagination v-if="pagination" :pagination="pagination" @change-page="fetchMessages"
            class="flex justify-center items-center"></pagination>
    </div>
</template>

<script>
import GuestbookForm from "@/Components/GuestbookForm.vue";
import MessageTable from "@/Components/MessageTable.vue";
import FlashMessages from "@/Components/FlashMessages.vue";
import Pagination from "@/Components/Pagination.vue";
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
        const urlParams = new URLSearchParams(window.location.search);
        const sort = urlParams.get('sort') || 'created_at';
        const direction = urlParams.get('direction') || 'desc';
        const state = reactive({
            messages: props.messages,
            pagination: props.pagination,
            errors: props.errors,
            sort: sort,
            direction: direction,
        });

        watchEffect(() => {
            console.log(props);
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

        function sortMessages(field, direction) {
            state.sort = field;
            state.direction = direction;
            fetchMessagesHandler(`/guestbook?sort=${field}&direction=${direction}`);
        }

        function updateMessages() {
            fetchMessagesHandler('/guestbook');
        }

        return { ...toRefs(state), fetchMessagesHandler, sortMessages, fetchMessages: fetchMessagesHandler, updateMessages, deleteMessage };
    },
};
</script>
