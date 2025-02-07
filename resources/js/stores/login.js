import { defineStore } from 'pinia';
import axios from 'axios';


export const useLoginStore = defineStore('login', {
    state: () => ({
        AllInsurers: [],
    }),
    actions: {
        async fetchToken(data) {
         try {
            const response = await axios.post('/api/login', data);
            //Agregar el token al local storage
            localStorage.setItem('auth_token', response.data.token);
            console.log('token ok');
            }
            catch (error) {
                console.log(error);
            }
        },

    },
    getters: {
    },

});