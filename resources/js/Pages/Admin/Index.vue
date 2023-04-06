<template>
    <div class="min-h-screen bg-gray-100 py-6 flex flex-col justify-center sm:py-12">
        <div class="relative py-3 sm:max-w-xl sm:mx-auto">
            <h1 class="text-2xl font-semibold text-center mb-6">Administrators</h1>
            <div class="relative px-4 py-10 bg-white mx-8 md:mx-0 shadow rounded-3xl sm:p-10">
                <ul>
                    <li v-for="administrator in administrators" :key="administrator.id" class="mb-4">
                        <span class="font-bold">{{ administrator.name }}</span> ({{ administrator.email }})
                        <router-link :to="'/admin/' + administrator.id + '/edit'" class="text-blue-500">Edit</router-link>
                    </li>
                </ul>
                <router-link to="/admin/create" class="bg-blue-500 text-white px-4 py-2 rounded-md">Add
                    Administrator</router-link>
            </div>
        </div>
    </div>
</template>
  
<script>
export default {
    props: {
        administrators: Array,
    },
};
</script>
  