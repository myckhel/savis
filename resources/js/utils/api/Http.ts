import axios from 'axios';
import { NotifyError } from '..';
import { logoutUser } from '../../redux/actions';

// @ts-expect-error
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
      NotifyError({ message: error.response?.data?.message as string });
    }

    // return error
    return Promise.reject(error);
  }
);

const Http = axios.create({
  baseURL: VITE_APP_URL,
  headers: {
    Accept: 'application/json',
    'X-Requested-With': 'XMLHttpRequest'
  }
});

const Api = axios.create({
  baseURL: VITE_APP_URL + '/api',
  headers: {
    Accept: 'application/json',
    'X-Requested-With': 'XMLHttpRequest'
  }
});

export { Api };

export default Http;
