<script setup>
import { computed, onMounted, ref } from 'vue';
import { useForm, configure } from 'vee-validate';
import * as yup from 'yup';
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
import Select from 'primevue/select';
import Editor from 'primevue/editor';
const toast = useToast();
const BlogStore = useBlogStore();
const confirm = useConfirm();

//const showForm = ref(false);
const isRequesting = ref(false);
let showDialog = ref([
    { newItem: false },
    { updateItem: false },
    { deleteItem: false },
    { showItem: false },
    { showDetails: [] },
]);


//Configurations for vee-validate
configure({
    validateOnChange: true,
    validateOnBlur: true,
    validateOnInput: true,
    validateOnModelUpdate: false,
});

//Rules for form
const { errors, defineField, handleSubmit } = useForm({
  validationSchema: yup.object({
    titleBlog: yup.string().required('El titulo es requerido'),
    descriptionBlog: yup.string(),
    // categoryBlog: yup.object().required('La categoría es requerida'),
    imageBlog: yup.mixed(),
  }),
});

const [titleBlog, titleBlogMeta] = defineField('titleBlog');
const [descriptionBlog, descriptionBlogMeta] = defineField('descriptionBlog');
// const [categoryBlog, categoryBlogMeta] = defineField('categoryBlog');
const [imageBlog, imageBlogMeta] = defineField('imageBlog');
const imageExternal = ref([
    {idItem: ''},
    {image: '' },
    {showImage: true}
]);




const blogs = computed(() => BlogStore.AllBlogs);

const getBlogs = async () => {
  const cleanFormData = {
        category: "4",
    };
    await BlogStore.fetchAllBlogs(cleanFormData);
};

onMounted(async() => {
    await getBlogs();
    //blogs.value = BlogStore.AllBlogs;
});


const postBlog = handleSubmit(
    async () => {
      isRequesting.value = true;
    let cleanFormData = {
        title: titleBlog.value,
        image: imageBlog.value[0],
        // category: categoryBlog.value.id,
        category: "4",
        description: "N/A",
    };
   const response = await BlogStore.postBlog(cleanFormData);

   console.log(response);
  
  if (response.status === 201) {

        toast.add({ severity: 'success', summary: 'Realizado', detail: '¡Instalación agregada correctamente!', life: 3000 });
    } else {
        toast.add({ severity: 'error', summary: 'Error', detail: 'Error al agregar instalación. Intente mas tarde.', life: 3000 });
    }

    cleanForm();

    showDialog.value.newItem = false;
    isRequesting.value = false;
    });




//Edit blog

const updateBlog = handleSubmit(
   async () => {
    isRequesting.value = true;
    let cleanFormData = {
        _method: 'PUT',
        id: imageExternal.value.idItem,
        title: titleBlog.value,
        // category: categoryBlog.value.id,
        category: "4",
        description: "N/A",
    };

    if(!imageExternal.value.showImage){
        cleanFormData.image = imageBlog.value[0];
    }

    console.log(cleanFormData);

    const response = await BlogStore.updateBlog(cleanFormData);

    if (response.status === 200) {

        toast.add({ severity: 'success', summary: 'Realizado', detail: '¡Instalación actualizada correctamente!', life: 3000 });
    } else {
        toast.add({ severity: 'error', summary: 'Error', detail: 'Error al actualizar Instalación. Intente mas tarde.', life: 3000 });
    }

    cleanForm();
    showDialog.value.updateItem = false;
    isRequesting.value = false;
});

const deleteItem = async (data) => {
  confirm.require({
        group: 'dialog-crud',
        message: ' ¿Estás seguro de eliminar esta instalación?',
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
           const response =  BlogStore.deleteBlog({id: data.id,category: data.category});
            toast.add({ severity: 'warn', summary: 'Realizado', detail: '¡Instalación borrada correctamente!', life: 3000 });
        },
        reject: () => {
            toast.add({ severity: 'error', summary: 'Cancelado', detail: 'Se cancelo la acción', life: 3000 });
        }
    });
};



//Set data to edit

const editItem = (data) => {
    titleBlog.value = data.title;
    descriptionBlog.value = data.description;
    // categoryBlog.value = typeCategory.find((category) => category.id === data.category);
    imageExternal.value.idItem = data.id;
    if(data.image!=null){
        imageExternal.value.image = data.image;
        imageExternal.value.showImage = true;
    }

};

const cleanForm = () => {
    titleBlog.value = '';
    descriptionBlog.value = '';
    // categoryBlog.value = '';
    imageBlog.value = '';
    imageExternal.value.showImage = true;
    imageExternal.value.image = '';
    imageExternal.value.idItem = '';
};


</script>
<template>
  <div>
    <SectionTitle>
      <template #title>
        <p class=" text-2xl">Instalaciones</p>
      </template>
      <template #description1>

        Listado de Instalaciones
      </template>
    </SectionTitle>

    <div class="my-10 flex flex-col gap-4">
      <div class="flex justify-end">
        <PrimaryButton @click="showDialog.newItem = !showDialog.newItem, cleanForm()">
          <label for="" v-if="!showDialog.newItem" class="flex justify-between gap-1">
            <li class="pi pi-plus"></li> Nuevo Instalación
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
            
          </template>
          <Column sortable field="title" header="Titulo" style="width: 25%">
            <template #body="slotProps">
              <span class=" font-bold">{{ slotProps.data.title }}</span>
            </template>
          </Column>
          <!-- <Column field="description" header="Descripción" style="width: 25%">
            <template #body="slotProps">
              <div v-html="slotProps.data.description" class="truncate">
              </div>
            </template>
          </Column> -->
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
                  class="p-button-rounded p-button-success p-mr-2 btn-update" @click="editItem(slotProps.data), showDialog.updateItem = !showDialog.updateItem" />
                <Button type="button" v-tooltip.top="'Eliminar elemento'" icon="pi pi-trash"
                  class="p-button-rounded p-button-danger btn-cancel" @click="deleteItem(slotProps.data)" />
              </div>
            </template>
          </Column>

        </DataTable>
      </div>

      <template v-if="showDialog.newItem">
        <Dialog v-model:visible="showDialog.newItem" maximizable modal header="Agregar nueva Instalación" :style="{ width: '40rem' }" :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
          <div class="flex flex-col items-start mb-4">
            <label for="title" class="font-semibold ">Titulo de la Instalación:</label>
            <TextInput placeholder="Escriba el nombre" v-model="titleBlog" v-bind="titleBlogMeta" class="w-full" />
          </div>
          <!-- <div class="flex flex-col items-start mb-4">
            <label for="description" class="font-semibold ">Description:</label>
            <Editor v-model="descriptionBlog" v-bind="descriptionBlogMeta" editorStyle="height: 320px" class="w-full"/>
          </div> -->
          <div class="flex flex-col items-start mb-4">
            <label for="image" class="font-semibold ">Imagen de la instalación</label>
            <FileManager class="w-full" v-model="imageBlog" v-bind="imageBlogMeta" />
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

      <template v-if="showDialog.updateItem">
        <Dialog v-model:visible="showDialog.updateItem" maximizable modal header="Actualizar Instalación" :style="{ width: '40rem' }" :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
          <div class="flex flex-col items-start mb-4">
            <label for="title" class="font-semibold ">Titulo de la instalación:</label>
            <TextInput placeholder="Escriba el nombre" v-model="titleBlog" v-bind="titleBlogMeta" class="w-full" />
            <p class="text-red-500">
              {{ errors.titleBlog }}
            </p>
          </div>
          <!-- <div class="flex flex-col items-start mb-4">
            <label for="description" class="font-semibold ">Description:</label>
            <Editor v-model="descriptionBlog" v-bind="descriptionBlogMeta" editorStyle="height: 320px" class="w-full"/>
          </div> -->
          <div class="flex flex-col items-start mb-4">
            <label for="image" class="font-semibold ">Imagen de la instalación</label>
            <div v-if="imageExternal.showImage">
              <img :src="`storage/${imageExternal.image}`" width="200" />
              <PrimaryButton type="button" class="mt-2" label="Cambiar imagen" @click="imageExternal.showImage = !imageExternal.showImage">
                Cambiar imagen
              </PrimaryButton>
            </div>
            <div v-if="!imageExternal.showImage" class="w-full">
              <FileManager class="w-full" v-model="imageBlog" v-bind="imageBlogMeta" />
            </div>
          </div>
          <!-- <div class="flex flex-col items-start mb-4">
            <label for="category" class="font-semibold ">Categoria:</label>
            <Select v-model="categoryBlog" v-bind="categoryBlogMeta" :options="typeCategory" optionLabel="name" placeholder="Select a City" class="w-full md:w-56" />
            <p class="text-red-500">
              {{ errors.categoryBlog }}
            </p>
          </div> -->
          <div class="flex justify-end gap-2">
            <SecondaryButton type="button" label="Cancel" severity="secondary" @click="showDialog.updateItem = false">
              Cancelar
            </SecondaryButton>
            <PrimaryButton type="button" label="Save" @click="updateBlog">
              Guardar
            </PrimaryButton>
          </div>
        </Dialog>
      </template>


      <template v-if="showDialog.showItem">
        <Dialog v-model:visible="showDialog.showItem" modal header="Agregar nueva Instalación" :style="{ width: '25rem' }">
          <div class="flex flex-col items-start mb-4">
            <label for="title" class="font-semibold ">Titulo de la instalación:</label>
            <h3>
              {{ showDialog.showDetails.title }}
            </h3>
          </div>
          <!-- <div class="flex flex-col items-start mb-4">
            <label for="description" class="font-semibold ">Description:</label>
            <div v-html="showDialog.showDetails.description"></div>
          </div> -->
          <div class="flex flex-col items-start mb-4">
            <label for="image" class="font-semibold ">Imagen de la instalación</label>
            <img :src="`storage/${showDialog.showDetails.image}`" width="200" />
          </div>
          <!-- <div class="flex flex-col items-start mb-4">
            <label for="category" class="font-semibold ">Categoría:</label>
            <h3>
              {{typeCategory.find((category) => category.id === showDialog.showDetails.category).name}}
            </h3>
          </div> -->
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


.truncate {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}


</style>