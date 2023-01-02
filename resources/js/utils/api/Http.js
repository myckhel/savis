import axios from 'axios';
import { NotifyError } from '..';
import { logoutUser } from '../../redux/actions';

const { VITE_APP_URL } = import.meta.env;

axios.defaults.baseURL = VITE_APP_URL;

axios.interceptors.response.use(
  response => response,
  error => {
    if (error.response && error.response.status === 401) {
      logoutUser();
    } else if (
      error.response &&
      (error.response.status === 500 || error.response.status === 405)
    ) {
      console.log(error.response.data);
    } else if (error.response.status === 400) {
      NotifyError({ msg: error.response?.data?.message });
    }

    // return error
    return Promise.reject(error);
  }
);

window.Http = axios.create({
  baseURL: VITE_APP_URL,
  headers: {
    Accept: 'application/json',
    'X-Requested-With': 'XMLHttpRequest'
  }
});

export default window.Http;
