<template>
    <table class="w-full border-collapse">
        <thead class="bg-gray-200">
            <tr>
                <th @click="sortBy('name')" class="cursor-pointer p-2">Name</th>
                <th @click="sortBy('created_at')" class="cursor-pointer p-2">Date</th>
                <th class="p-2">Message</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="message in messages" :key="message.id" class="border-t hover:bg-gray-100">
                <td class="p-2">
                    <a :href="'mailto:' + message.email">{{ message.name }}</a>
                </td>
                <td class="p-2">{{ message.created_at }}</td>
                <td class="p-2">
                    {{ message.message }}
                    <br v-if="message.image_path" />
                    <a v-if="message.image_path" :href="message.image_path" target="_blank">
                        <img :src="message.image_path" alt="Image preview" width="100" />
                    </a>
                    <div v-if="isMessageUpdated(message)">
                        Edited: {{ formatDate(message.updated_at) }}
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</template>

<script>
export default {
    props: {
        messages: {
            type: Array,
            default: () => [],
        },
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
    },
};
</script>
  