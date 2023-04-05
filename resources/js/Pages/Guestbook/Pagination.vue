<template>
    <div class="pagination">
        <button @click="previousPage" :disabled="!pagination.prev_page_url">
            Previous
        </button>
        <span>Page {{ pagination.current_page }} of {{ pagination.last_page }}</span>
        <button @click="nextPage" :disabled="!pagination.next_page_url">
            Next
        </button>
    </div>
</template>
  
<script>
export default {
    props: {
        pagination: Object,
    },
    methods: {
        previousPage() {
            this.$emit("change-page", this.pagination.prev_page_url);
        },
        nextPage() {
            this.$emit("change-page", this.pagination.next_page_url);
        },
    },
};
</script>
  