<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Welcome from '@/Components/Welcome.vue';
import InsurersView from './Admin/InsurersView.vue';
import ConfirmDialog from 'primevue/confirmdialog';
import Toast from 'primevue/toast';
import { ref } from 'vue';

import Breadcrumb from 'primevue/breadcrumb';

const homeBreadcrumb = ref({
    icon: 'pi pi-home',
    route: '/dashboard'
});



const itemsBreadcrumb = ref([
    { label: 'Aseguradora', route: '/dashboard' },
]);



</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <template v-if="route().current('dashboard')">
                    Dashboard
                </template>
                <template v-if="route().current('insurers')">
                    
                    <Breadcrumb :home="homeBreadcrumb" :model="itemsBreadcrumb"> 

                    </Breadcrumb>   
                </template>
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-5 py-10">

                    <Welcome v-if="route().current('dashboard')" />
                    <InsurersView v-if="route().current('insurers')" />

                </div>
            </div>
        </div>
        <Toast />
    
    </AppLayout>

  
  
</template>

<style scoped>

</style>
