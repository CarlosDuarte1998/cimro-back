<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { onMounted } from 'vue';
import { useInsurerStore } from '@/stores/insurers';

const insurerStore = useInsurerStore();

onMounted(async() => {
    await insurerStore.fetchAllInsurers();
});



</script>

<template>
    <div>
        <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
         <div class="w-full flex justify-center">
            <ApplicationLogo class="block w-auto h-24" />
         </div>

            <h1 class="mt-8 text-2xl text-center  text-gray-900">
                ¡Bienvenido, <label for="" class=" font-bold">{{ $page.props.auth.user.name }}</label>!
            </h1>

          
        </div>

     
    </div>
</template>
