import { defineStore } from 'pinia';
import axios from 'axios';

export const useBlogStore = defineStore('blog', {
    state: () => ({
        category: null,
        AllBlogs: [],
    }),
    actions: {
        async fetchAllBlogs(data) {
            // try {
            //    if(this.AllBlogs.length == 0) {
                // console.log(data);   
                // const response = await axios.get("/api/blogs", data,{
                    const response = await axios.get(`/api/blogs/${data.category}`, {
                       headers: {
                           'Content-Type': 'multipart/form-data',
                           'Authorization': `Bearer ${localStorage.getItem('auth_token')}`
                       }
                   });
                   this.AllBlogs = response.data;
                   
               //}
            //    }
            //    catch (error) {
            //        console.log(error);
            //    }
           },

        async postBlog(data) {
            try {
                const response =  await axios.post('/api/blogs', data, {
                  headers: {
                      'Content-Type': 'multipart/form-data',
                      'Authorization': `Bearer ${localStorage.getItem('auth_token')}`
                  }
              });
  
            this.AllBlogs = [];
            this.fetchAllBlogs({category: data.category});
              console.log({category: data.category});
            return response;
            }
            catch (error) {
                console.log(error);
            }
        },

        async updateBlog(data) {
            try {
                console.log(data);
                const response = await axios.post(`/api/blogs/${data.id}`, data, {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                        'Authorization': `Bearer ${localStorage.getItem('auth_token')}`
                    }
                });
                this.AllBlogs = [];
                // this.fetchAllBlogs(data.category);
                this.fetchAllBlogs({category: data.category});
                return response;
            }
            catch (error) {
                console.log(error);
            }
        },

        async deleteBlog(data) {
            try {
                console.log(data);
                const response = await axios.delete(`/api/blogs/${data.id}`, {
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('auth_token')}`
                    }
                });
                this.AllBlogs = [];
                // this.fetchAllBlogs();
                this.fetchAllBlogs({category: data.category});
                return response;
            }
            catch (error) {
                console.log(error);
            }
        },
        async fetchAllBlogsByCategory(category) {
            try {
               if(this.AllBlogs.length == 0) {
                   const response = await axios.get("/api/blogs", {
                       headers: {
                           'Content-Type': 'multipart/form-data',
                           'Authorization': `Bearer ${localStorage.getItem('auth_token')}`
                       }
                   });
                   this.AllBlogs = response.data;
               }
               }
               catch (error) {
                   console.log(error);
               }
           }
    },

    getters: {
    },

});