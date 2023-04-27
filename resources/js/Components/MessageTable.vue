<template>
    <div class="overflow-x-scroll lg:overflow-x-auto">
        <table class="lg:w-full border-collapse table-fixed mr-0">
            <thead class="bg-gray-200">
                <tr>
                    <th @click="sortBy('name')"
                        class="min-w-28 lg:w-1/5 cursor-pointer p-2 border-b border-gray-300 text-left text-gray-600">Name
                    </th>
                    <th @click="sortBy('created_at')"
                        class="min-w-28 lg:w-1/5 cursor-pointer p-2 border-b border-gray-300 text-left text-gray-600">Created at:</th>
                    <th class="min-w-40 lg:w-2/5 p-2 border-b border-gray-300 text-left text-gray-600">Message</th>
                    <th class="min-w-28 lg:w-1/5 p-2 border-b border-gray-300 text-left text-gray-600">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="message in messages" :key="message.id" class="border-t border-gray-200 hover:bg-gray-100">
                    <td class="p-2 break-words">
                        <a :href="'mailto:' + message.email">{{ message.name }}</a>
                    </td>
                    <td class="p-2 break-words">
                        {{ formatDate(message.created_at) }}
                        <template v-if="isMessageUpdated(message)">
                            <br><strong>Edited:</strong> {{ formatDate(message.updated_at) }}
                        </template>
                    </td>
                    <td class="p-2 break-words">
                        {{ message.message }}
                        <br>
                        <template v-if="message.image_path">
                            <a :href="message.image_path" target="_blank">
                                <img :src="message.image_path" alt="Image preview" width="100">
                            </a>
                            <br>
                        </template>
                    </td>
                    <td class="p-2 break-words">
                        <div class="flex flex-col space-y-6 items-start font-bold">
                            <Link :href="'/guestbook/' + message.id + '/edit'" class="hover:text-blue-700">EDIT</Link>
                            <button class="hover:text-rose-700" @click="$emit('delete-message', message.id)">REMOVE</button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
import { Link } from '@inertiajs/vue3'

export default {
    props: {
        messages: {
            type: Array,
            default: () => [],
        },
        sortDirection: {
            type: String,
            default: 'desc',
        },
    },
    components: {
        Link,
    },
    computed: {

    },
    methods: {
        sortBy(field) {
            const newDirection = this.toggleSortDirection();
            this.$emit("sort", field, newDirection);
        },
        toggleSortDirection() {
            const newDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
            this.$emit('update:sortDirection', newDirection);
            return newDirection;
        },
        openImage(imagePath) {
            window.open(imagePath, "_blank");
        },
        isMessageUpdated(msg) {
            return msg.created_at !== msg.updated_at;
        },
        formatDate(date) {
            return new Date(date).toLocaleString();
        },
    },
};
</script>
  