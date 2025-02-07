import { defineStore } from 'pinia';
import axios from 'axios';


export const useInsurerStore = defineStore('insurer', {
    state: () => ({
        AllInsurers: [],
    }),
    actions: {
        async fetchAllInsurers() {
         try {
            const response = await axios.get('/api/insurance-companies');
            this.AllInsurers = response.data;
            }
            catch (error) {
                console.log(error);
            }
        },

        async postInsurer(data) {
            try {
               await axios.post('/api/insurance-companies', data, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                    'Authorization': `Bearer ${localStorage.getItem('auth_token')}`
                }
            });
               this.fetchAllInsurers();
            }
            catch (error) {
                console.log(error);
            }
        }


    },

    getters: {
    },

});