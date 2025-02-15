<script setup>
import FileManager from '@/Components/FileManager.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import SectionTitle from '@/Components/SectionTitle.vue';
import TextInput from '@/Components/TextInput.vue';
import Dialog from 'primevue/dialog';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';  
import { useBlogStore } from '@/stores/blogs';
import { onMounted, ref } from 'vue';


const BlogStore = useBlogStore();


const showForm = ref(false);



const formData = ref({
  title: '',
    image: null,
});

const getBlogs = async () => {
    await BlogStore.fetchAllBlogs();
};

const blogs = ref([]);


onMounted(async() => {
    await getBlogs();
    blogs.value = BlogStore.AllBlogs;
});

const postBlog = async () => {
  const cleanFormData = {
    title: formData.value.title,
    description: formData.value.description,
    image: formData.value.image[0],
    category: formData.value.category
  };
  await BlogStore.postBlog(cleanFormData);
  await getBlogs();
  formData.value = {
    title: '',
    image: null,
  };
  showForm.value = false;
};

</script>
<template>
  <div>
    <SectionTitle>
      <template #title>
        <p class=" text-2xl">Blogs</p>
      </template>
      <template #description1>

        Listado de blogs
      </template>
    </SectionTitle>

    <div class="my-10 flex flex-col gap-4">
      <div class="flex justify-end">
        <PrimaryButton @click="showForm = !showForm">
          <label for="" v-if="!showForm" class="flex justify-between gap-1">
            <li class="pi pi-plus"></li> Nuevo blog
          </label>
          <label for="" v-else>Ocultar formulario</label>
        </PrimaryButton>

      </div>

      <pre>
        {{ formData.image }}
      </pre>


      <template v-if="showForm">
        <Dialog v-model:visible="showForm" modal header="Agregar nuevo blog" :style="{ width: '25rem' }">
          <div class="flex flex-col items-start mb-4">
            <label for="title" class="font-semibold ">Titulo del blog:</label>
            <TextInput placeholder="Escriba el nombre" v-model="formData.title" class="w-full" />
          </div>
          <div class="flex flex-col items-start mb-4">
            <label for="description" class="font-semibold ">Description:</label>
            <TextInput placeholder="Escriba la descripción" v-model="formData.description" class="w-full"/>
          </div>
          <div class="flex flex-col items-start mb-4">
            <label for="image" class="font-semibold ">Imagen del blog</label>
            <FileManager class="w-full" v-model="formData.image" />
          </div>
          <div class="flex flex-col items-start mb-4">
            <label for="category" class="font-semibold ">Categoria:</label>
            <TextInput placeholder="Por el momento integer" v-model="formData.category" class="w-full" />
              <!-- <select v-model="formData.category" class="w-full" name="category" id="category">
                <option value="">Select a category</option>
                <option v-for="category in categories" :key="category.id" :value="category.id">
                  {{ category.name }}
                </option>
              </select> -->
          </div>
          <div class="flex justify-end gap-2">
            <SecondaryButton type="button" label="Cancel" severity="secondary" @click="showForm = false">
              Cancelar
            </SecondaryButton>
            <PrimaryButton type="button" label="Save" @click="postBlog">
              Guardar
            </PrimaryButton>
          </div>
        </Dialog>
      </template>

      <div class="card">
        <DataTable :value="blogs" tableStyle="min-width: 50rem">
          <Column field="id" header="ID"></Column>
          <Column field="title" header="Nombre "></Column>
          <Column field="description" header="Descripción "></Column>
          <Column field="user.name" header="Usuario"></Column>
          <Column field="category" header="Categoria "></Column>
          <Column field="image" header="Imagen">
            <template #body="slotProps">
              <img :src="`${slotProps.data.image}`" class="w-24 rounded" />
            </template>
          </Column>
          <Column header="Editar "></Column>
          <Column header="Eliminar "></Column>

        </DataTable>
      </div>


    </div>

  </div>
</template>
<style scoped>



</style>