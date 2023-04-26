<template>
    <table class="w-full border-collapse table-fixed">
        <thead class="bg-gray-200">
            <tr>
                <th @click="sortBy('name')"
                    class="w-[20%] cursor-pointer p-2 border-b border-gray-300 text-left text-gray-600">Name
                </th>
                <th @click="sortBy('created_at')"
                    class="w-[20%] cursor-pointer p-2 border-b border-gray-300 text-left text-gray-600">Created at:</th>
                <th class="w-[40%] p-2 border-b border-gray-300 text-left text-gray-600">Message</th>
                <th class="w-[20%] p-2 border-b border-gray-300 text-left text-gray-600">Actions</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="message in messages" :key="message.id" class="border-t border-gray-200 hover:bg-gray-100">
                <td class="p-2">
                    <a :href="'mailto:' + message.email">{{ message.name }}</a>
                </td>
                <td class="p-2">
                    {{ formatDate(message.created_at) }}
                    <template v-if="isMessageUpdated(message)">
                        <br><strong>Edited:</strong> {{ formatDate(message.updated_at) }}
                    </template>
                </td>
                <td class="p-2">
                    {{ message.message }}
                    <br>
                    <template v-if="message.image_path">
                        <a :href="message.image_path" target="_blank">
                            <img :src="message.image_path" alt="Image preview" width="100">
                        </a>
                        <br>
                    </template>
                </td>
                <td class="p-2">
                    <Link :href="'/guestbook/' + message.id + '/edit'">EDIT</Link>
                    <br>
                    <button @click="$emit('delete-message', message.id)">REMOVE</button>
                </td>
            </tr>
        </tbody>
    </table>
</template>

<script>
import { Link } from '@inertiajs/vue3'

export default {
    props: {
        messages: {
            type: Array,
            default: () => [],
        },
    },
    components: {
        Link,
    },
    computed: {

    },
    methods: {
        sortBy(field) {
            this.$emit("sort", field);
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
  