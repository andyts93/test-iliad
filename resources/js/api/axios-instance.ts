import axios from 'axios';
import { toast } from 'vue3-toastify';

const instance = axios.create();

instance.interceptors.response.use(
    response => response,
    error => {
        console.error('API Error: ', error);

        let errMsg = 'An error occured';

        if (error.response) {
            if (error.response.data.error) {
                errMsg = error.response.data.error;
            }
            else if (error.response.data.errors) {
                errMsg = error.response.data.errors.join(',');
            }
        }
        else {
            errMsg = 'Network error, please check your connection';
        }

        toast.error(errMsg);

        return Promise.reject(error);
    }
);

export default instance;