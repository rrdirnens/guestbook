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
                    <img v-if="message.image_path" :src="message.image_path" @click="openImage(message.image_path)"
                        class="preview-image cursor-pointer w-32" />
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
    methods: {
        sortBy(field) {
            this.$emit("sort", field);
        },
        openImage(imagePath) {
            window.open(imagePath, "_blank");
        },
    },
};
</script>
  