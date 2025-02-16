<template>
    <div id="app">
      <editor 
        api-key="cpy42h9s56dcnnh9fr5klmzd548bzqptl2dynmd7gxtnomq3" 
        :init="editorConfig" 
      />
    </div>
  </template>
  
  <script setup>
  import Editor from '@tinymce/tinymce-vue';
  import { defineProps, defineEmits, ref, watch, onMounted } from 'vue';
  
  const props = defineProps({
    modelValue: {
      type: String,
      default: ''
    }
  });
  
  const emit = defineEmits();
  
  const editorConfig = {
    menubar: false,
    plugins: [
       'advlist',  'autolink',  
      'lists', 'link', 'image', 'charmap', 'preview', 'anchor', 'searchreplace', 'visualblocks',
       'fullscreen',  'insertdatetime', 'media', 'table', 'help', 'wordcount',
    ],
    toolbar:
      'undo redo | image | preview | casechange blocks | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist checklist outdent indent | removeformat | a11ycheck code table help', 
  
    setup: (editor) => {
      editor.on('init', () => {
        editor.setContent(props.modelValue);
      });
  
      editor.on('change', () => {
        // Emitir el cambio de contenido al componente padre
        emit('update:modelValue', editor.getContent());
      });
    }
  };
  </script>
  
  <style scoped>
  </style>
  