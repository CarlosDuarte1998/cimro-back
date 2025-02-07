<script setup>
import FileManager from '@/Components/FileManager.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import SectionTitle from '@/Components/SectionTitle.vue';
import TextInput from '@/Components/TextInput.vue';
import Dialog from 'primevue/dialog';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';  
import { useInsurerStore } from '@/stores/insurers';
import { onMounted, ref } from 'vue';


const insurerStore = useInsurerStore();


const showForm = ref(false);



const formData = ref({
    name: '',
    image: null,
});

const getInsurers = async () => {
    await insurerStore.fetchAllInsurers();

};

const insurers = ref([]);


onMounted(async() => {
    await getInsurers();
    insurers.value = insurerStore.AllInsurers;
});

const postInsurer = async () => {
  const cleanFormData = {
    name: formData.value.name,
    //Pasar el archivo seleccionado
    image: formData.value.image[0],
  };
  await insurerStore.postInsurer(cleanFormData);
  await getInsurers();
  formData.value = {
    name: '',
    image: null,
  };
  showForm.value = false;
};

</script>
<template>
  <div>
    <SectionTitle>
      <template #title>
        <p class=" text-2xl">Aseguradoras</p>
      </template>
      <template #description>

        Listado de aseguradoras
      </template>
    </SectionTitle>

    <div class="my-10 flex flex-col gap-4">
      <div class="flex justify-end">
        <PrimaryButton @click="showForm = !showForm">
          <label for="" v-if="!showForm" class="flex justify-between gap-1">
            <li class="pi pi-plus"></li> Nueva aseguradora
          </label>
          <label for="" v-else>Ocultar formulario</label>
        </PrimaryButton>

      </div>

      <pre>
        {{ formData.image }}
      </pre>


      <template v-if="showForm">
        <Dialog v-model:visible="showForm" modal header="Agregar nueva aseguradora" :style="{ width: '25rem' }">
          <span class="text-surface-500 dark:text-surface-400 block mb-8">Completa la información requerida</span>
          <div class="flex flex-col items-start mb-4">
            <label for="username" class="font-semibold ">Nombre de la asegurada:</label>
            <TextInput placeholder="Escriba el nombre" v-model="formData.name" class="w-full" />
          </div>
          <div class="flex flex-col items-start mb-4">
            <label for="email" class="font-semibold ">Logo de la asegurada</label>
            <FileManager class="w-full" v-model="formData.image" />
          </div>
          <div class="flex justify-end gap-2">
            <SecondaryButton type="button" label="Cancel" severity="secondary" @click="showForm = false">
              Cancelar
            </SecondaryButton>
            <PrimaryButton type="button" label="Save" @click="postInsurer">
              Guardar
            </PrimaryButton>
          </div>
        </Dialog>
      </template>

      <div class="card">
        <DataTable :value="insurers" tableStyle="min-width: 50rem">
          <Column field="id" header="ID"></Column>
          <Column field="name" header="Nombre "></Column>
          <Column field="user.name" header="Usuario"></Column>
          <Column field="image" header="Imagen">
            <template #body="slotProps">
              <img :src="`${slotProps.data.image}`" class="w-24 rounded" />
            </template>
          </Column>
          
        </DataTable>
      </div>


    </div>

  </div>
</template>
<style scoped>



</style>