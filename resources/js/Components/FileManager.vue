<script setup>
import vueFilePond from "vue-filepond";
import 'filepond/dist/filepond.min.css';
import FilePondPluginImagePreview from "filepond-plugin-image-preview";
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css';
import FilePondPluginFileValidateType from "filepond-plugin-file-validate-type";

// Crear instancia de FilePond con plugins
const FilePond = vueFilePond(
  FilePondPluginImagePreview,
  FilePondPluginFileValidateType
);

// Definir props
const props = defineProps({
  modelValue: {
    type: Array, // Los archivos seleccionados serán un array
    default: () => [],
  },
  textFile: {
    type: String,
    default: "Arrastre y suelte el archivo aquí para cargarlo.",
  },
  acceptedFileTypes: {
    type: String,
    default: "image/png, image/jpeg",
  },
});

// Definir emits
const emit = defineEmits(['update:modelValue']);

// Manejar cambios en los archivos
const handleFileChange = (files) => {
  // Emitir el primer archivo seleccionado al padre (solo uno)
  if (files.length > 0) {
    emit('update:modelValue', [files[0].file]); // Solo emitir el primer archivo
  } else {
    emit('update:modelValue', []); // Limpiar si no hay archivos
  }
};
</script>

<template>
  <file-pond
    class-name="my-pond"
    :label-idle="props.textFile"
    :allow-multiple="false" 
    class="cursor-pointer text-xs w-1/2"
    :accepted-file-types="props.acceptedFileTypes"
    :files="props.modelValue" 
    @updatefiles="handleFileChange" 
  />
</template>

<style>
/* Estilos personalizados si es necesario */
</style>