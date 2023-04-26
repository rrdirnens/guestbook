<!-- a base layout used accress the entire app -->
<template>
   <div class="min-h-screen flex flex-col">
    <Navigation />
    <div class="flex-grow bg-gray-100 py-8 px-4 md:px-8">
      <div class="max-w-7xl mx-auto">
        <slot />
      </div>
    </div>
  </div>
</template>

<script>
import Navigation from '@/Components/Navigation.vue';

export  default {
    
    components: {
        Navigation
    }
};
</script>