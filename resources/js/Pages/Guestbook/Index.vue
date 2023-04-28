<template>
    <div class="space-y-6">
        <flash-messages></flash-messages>
        <guestbook-form></guestbook-form>
        <pagination v-if="pagination" :links="pagination_links" @change-page="fetchMessages"
            class="flex justify-center items-center"></pagination>
        <message-table v-if="messages.length" :messages="messages" :sortDirection="direction" @sort="sortMessages" @delete-message="deleteMessage"
            class="bg-white shadow-md rounded-lg overflow-hidden">
        </message-table>

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
            messages: props.messages.data,
            pagination: props.messages.total > props.messages.per_page,
            pagination_links: props.messages.links,
            errors: props.errors,
            sort: sort,
            direction: direction,
        });

        watchEffect(() => {
            console.log(props);
            console.log(props.messages.data);
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
