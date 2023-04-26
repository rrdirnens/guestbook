
<template>
    <nav class="bg-gray-800 p-4">
        <ul class="flex items-center justify-between">
            <li class="space-x-4 flex items-center">
                <Link href="/" class="text-white hover:text-gray-300">
                Home
                </Link>
                <Link href="/guestbook" class="text-white hover:text-gray-300">
                Guestbook
                </Link>
            </li>
            <li class="space-x-4 flex items-center">
                <Link v-if="!isLoggedIn" href="/admin" class="text-white hover:text-gray-300">
                Admin Login
                </Link>
                <button v-if="isLoggedIn" @click="logout" class="text-white hover:text-gray-300">
                    Logout
                </button>
            </li>
        </ul>
    </nav>
</template>

<script>
import { ref } from 'vue'
import { Link } from '@inertiajs/vue3'

export default {

    components: {
        Link
    },

    setup() {
        const isLoggedIn = ref(false);
        function logout() {
            console.log('logout');
        }

        return { isLoggedIn, logout };
    },


}
</script>