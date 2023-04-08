<script setup>
import { Link } from '@inertiajs/vue3'
</script>

<template>
    <div class="min-h-screen flex items-center justify-center">
        <div class="text-center">
            <h1 class="text-4xl font-bold text-vue-green mb-8">GUESTBOOK</h1>
            <Link class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-4" href="/guestbook">
            Guestbook</Link>
            <Link class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" href="/login">Login</Link>
        </div>
    </div>
</template>

<style lang="scss"></style>