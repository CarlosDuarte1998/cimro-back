<script setup>
import { computed, onMounted, ref } from 'vue';
import FileManager from '@/Components/FileManager.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import SectionTitle from '@/Components/SectionTitle.vue';
import TextInput from '@/Components/TextInput.vue';
import Dialog from 'primevue/dialog';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';    
import Button from 'primevue/button';
import ConfirmDialog from 'primevue/confirmdialog';
import { useBlogStore } from '@/stores/blogs';
import timeManager from '@/utils/timeManager';
import { useToast } from 'primevue/usetoast';
import { useConfirm } from "primevue/useconfirm";
const toast = useToast();
const BlogStore = useBlogStore();
const confirm = useConfirm();

//const showForm = ref(false);
const isRequesting = ref(false);
let showDialog = ref([
    { newItem: false },
    { editItem: false },
    { deleteItem: false },
    { showItem: false },
    { showDetails: [] },
]);


const formData = ref({
  title: '',
    image: null,
});
const blogs = computed(() => BlogStore.AllBlogs);

const getBlogs = async () => {
    await BlogStore.fetchAllBlogs();
};
onMounted(async() => {
    await getBlogs();
    //blogs.value = BlogStore.AllBlogs;
});

// const postBlog = async () => {
//   const cleanFormData = {
//     title: formData.value.title,
//     description: formData.value.description,
//     image: formData.value.image[0],
//     category: formData.value.category
//   };
//   await BlogStore.postBlog(cleanFormData);
//   await getBlogs();
//   formData.value = {
//     title: '',
//     image: null,
//   };
//   showForm.value = false;
// };
const postBlog = async () => {
    isRequesting.value = true;
    const cleanFormData = {
        name: formData.value.name,
        image: formData.value.image[0],
    };

   const response = await BlogStore.postBlog(cleanFormData);

   console.log(response);
  
  if (response.status === 201) {

        toast.add({ severity: 'success', summary: 'Success', detail: 'Blog agregado correctamente', life: 3000 });
    } else {
        toast.add({ severity: 'error', summary: 'Error', detail: 'Error al agregar Blog. Intente mas tarde.', life: 3000 });
    }

    formData.value = {
        name: '',
        image: null,
    };
    showDialog.value.newItem = false;
    isRequesting.value = false;
};
const editItem = (data) => {
    console.log(data.id);
};

const deleteItem = async (data) => {
  confirm.require({
        group: 'dialog-crud',
        message: ' ¿Estás seguro de eliminar este blog?',
        header: 'Confirmar',
        icon: 'pi pi-info-circle',
        rejectLabel: 'Cancel',
        rejectProps: {
            label: 'Cancelar',
            severity: 'secondary',
            outlined: true,
        },
        acceptProps: {
            label: 'Borrar',
            severity: 'danger'
        },
        accept: () => {
           const response =  BlogStore.deleteBlog(data.id);
            toast.add({ severity: 'info', summary: 'Realizado', detail: 'Registro borrado', life: 3000 });
        },
        reject: () => {
            toast.add({ severity: 'error', summary: 'Cancelado', detail: 'Se cancelo la acción', life: 3000 });
        }
    });
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
        <PrimaryButton @click="showDialog.newItem = !showDialog.newItem">
          <label for="" v-if="!showDialog.newItem" class="flex justify-between gap-1">
            <li class="pi pi-plus"></li> Nuevo blog
          </label>
          <label for="" v-else>Ocultar formulario</label>
        </PrimaryButton>

      </div>

      <!-- <pre>
        {{ formData.image }}
      </pre>
 -->

      

      <div class="card">
        <DataTable :value="blogs" :sortOrder="-1"  stripedRows sortMode="multiple" paginator :rows="5"
          :rowsPerPageOptions="[5, 10, 20, 50]" tableStyle="min-width: 50rem"
          paginatorTemplate="RowsPerPageDropdown FirstPageLink PrevPageLink CurrentPageReport NextPageLink LastPageLink"
          currentPageReportTemplate="{first} de {last} a {totalRecords}">
          <template #paginatorstart>
          </template>
          <template #paginatorend>
            <Button type="button" icon="pi pi-download" text />
          </template>
          <Column sortable field="title" header="Titulo" style="width: 25%">
            <template #body="slotProps">
              <span class=" font-bold">{{ slotProps.data.title }}</span>
            </template>
          </Column>
          <Column field="description" header="Descripción" style="width: 25%">
            <template #body="slotProps">
              <span class=" font-bold">{{ slotProps.data.description }}</span>
            </template>
          </Column>
          <Column field="image" header="Imagen" style="width: 25%">
            <template #body="slotProps">
              <img :src="`storage/${slotProps.data.image}`" width="70" />
            </template>
          </Column>
          <Column field="created_at" header="Creado" style="width: 15%">
            <template #body="slotProps">
              <span>{{ timeManager(slotProps.data.created_at) }}</span>
            </template>
          </Column>
          <Column field="updated_at" header="Actualizado" style="width: 15%">
            <template #body="slotProps">
              <span>{{ timeManager(slotProps.data.updated_at) }}</span>
            </template>
          </Column>
          <Column field="user.name" header="Usuario" style="width: 10%"></Column>

          <!-- Acciones -->
          <Column header="Actions" class="" style="width: 10%">
            <template #body="slotProps">
              <div class="flex justify-center gap-1">
                <Button type="button" v-tooltip.right="'Ver detalles'" icon="pi pi-eye"
                  class="p-button-rounded p-button-info p-mr-2" @click="showDialog.showItem = !showDialog.showItem, showDialog.showDetails = 
                  slotProps.data" />
                <Button type="button" v-tooltip.left="'Actualizar datos'" icon="pi pi-pencil"
                  class="p-button-rounded p-button-success p-mr-2 btn-update" @click="editItem(slotProps.data)" />
                <Button type="button" v-tooltip.top="'Eliminar elemento'" icon="pi pi-trash"
                  class="p-button-rounded p-button-danger btn-cancel" @click="deleteItem(slotProps.data)" />
              </div>
            </template>
          </Column>

        </DataTable>
      </div>

      <template v-if="showDialog.newItem">
        <Dialog v-model:visible="showDialog.newItem" modal header="Agregar nuevo blog" :style="{ width: '25rem' }">
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
          </div>
          <div class="flex justify-end gap-2">
            <SecondaryButton type="button" label="Cancel" severity="secondary" @click="showDialog.newItem = false">
              Cancelar
            </SecondaryButton>
            <PrimaryButton type="button" label="Save" @click="postBlog">
              Guardar
            </PrimaryButton>
          </div>
        </Dialog>
      </template>
      <template v-if="showDialog.showItem">
        <Dialog v-model:visible="showDialog.showItem" modal header="Agregar nuevo blog" :style="{ width: '25rem' }">
          <div class="flex flex-col items-start mb-4">
            <label for="title" class="font-semibold ">Titulo del blog:</label>
            <h3>
              {{ showDialog.showDetails.title }}
            </h3>
          </div>
          <div class="flex flex-col items-start mb-4">
            <label for="description" class="font-semibold ">Description:</label>
            <h3>
              {{ showDialog.showDetails.description }}
            </h3>
          </div>
          <div class="flex flex-col items-start mb-4">
            <label for="image" class="font-semibold ">Imagen del blog</label>
            <img :src="`storage/${showDialog.showDetails.image}`" width="200" />
          </div>
          <div class="flex flex-col items-start mb-4">
            <label for="category" class="font-semibold ">Categoria:</label>
            <h3>
              {{ showDialog.showDetails.category }}
            </h3>
          </div>
          <div class="flex justify-end gap-2">
            <SecondaryButton type="button" label="Cancel" severity="secondary" @click="showDialog.showItem = false">
              Cancelar
            </SecondaryButton>
          </div>
        </Dialog>
      </template>
    </div>
  </div>
  <ConfirmDialog group="dialog-crud">
    <template #message="slotProps">
            <div class="flex items-center w-full gap-4 ">
                <i :class="slotProps.message.icon" class="!text-3xl text-black"></i>
                <div>
                  <h2 class=" font-medium text-lg ">{{ slotProps.message.message }}</h2>
                  <p class=" text-sm">
                    Esta acción no se puede deshacer
                  </p>
                </div>

            </div>
        </template>
  </ConfirmDialog>
</template>
<style scoped>

.btn-update {
    background-color: #0000008a;
    color: white;
    border: none;
}

.p-button-success:not(:disabled):hover {
    background-color: #000000 !important;
    color: white;
    border: none;
}

.btn-cancel::hover {
    background-color: #ff0000;
    color: white;
    border: none;
}

.btn-cancel {
    background-color: #ff00007c;
    color: white;
    border: none;
}

.p-button-info {
    opacity: 0.8;
}



</style>