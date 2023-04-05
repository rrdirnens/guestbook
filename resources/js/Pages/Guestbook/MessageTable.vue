<template>
    <table>
        <thead>
            <tr>
                <th @click="sortBy('name')">Name</th>
                <th @click="sortBy('created_at')">Date</th>
                <th>Message</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="message in messages" :key="message.id">
                <td>
                    <a :href="'mailto:' + message.email">{{ message.name }}</a>
                </td>
                <td>{{ message.created_at }}</td>
                <td>
                    {{ message.message }}
                    <img v-if="message.image_path" :src="message.image_path" @click="openImage(message.image_path)"
                        class="preview-image" />
                </td>
            </tr>
        </tbody>
    </table>
</template>

<script>
export default {
    props: {
        messages: Array,
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
  