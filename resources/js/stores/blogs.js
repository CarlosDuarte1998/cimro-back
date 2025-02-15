import { defineStore } from 'pinia';
import axios from 'axios';


export const useBlogStore = defineStore('blog', {
    state: () => ({
        AllBlogs: [],
    }),
    actions: {
        async fetchAllBlogs() {
         try {
            const response = await axios.get('/api/blogs');
            this.AllBlogs = response.data;
            }
            catch (error) {
                console.log(error);
            }
        },

        async postBlog(data) {
            try {
               await axios.post('/api/blogs', data, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                    'Authorization': `Bearer ${localStorage.getItem('auth_token')}`
                }
            });
               this.fetchAllBlogs();
            }
            catch (error) {
                console.log(error);
            }
        }


    },

    getters: {
    },

});