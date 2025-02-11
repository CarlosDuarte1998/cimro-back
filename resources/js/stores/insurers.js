import { defineStore } from 'pinia';
import axios from 'axios';

export const useInsurerStore = defineStore('insurer', {
    state: () => ({
        AllInsurers: [],
    }),
    actions: {
        async fetchAllInsurers() {
         try {
            if(this.AllInsurers.length == 0) {
                const response = await axios.get("/api/insurance-companies", {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                        'Authorization': `Bearer ${localStorage.getItem('auth_token')}`
                    }
                });
                this.AllInsurers = response.data;
            }
            }
            catch (error) {
                console.log(error);
            }
        },

        async postInsurer(data) {
            try {
              const response =  await axios.post('/api/insurance-companies', data, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                    'Authorization': `Bearer ${localStorage.getItem('auth_token')}`
                }
            });

            this.AllInsurers = [];
            this.fetchAllInsurers();

            return response;
                
            }
            catch (error) {
                console.log(error);
            }
        },

        async deleteInsurer(id) {
            try {
                const response = await axios.delete(`/api/insurance-companies/${id}`, {
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('auth_token')}`
                    }
                });

                this.AllInsurers = [];
                this.fetchAllInsurers();

                return response;

            }
            catch (error) {
                console.log(error);
            }
        }

      


    },

    getters: {
    },

});