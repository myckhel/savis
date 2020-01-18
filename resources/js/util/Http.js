/* eslint-disable no-console */
import axios from 'axios'
import {configureStore} from '../redux/store'
import { logoutUser } from '../redux/auth/actions'
import {toast} from 'react-toastify'

const version = 'v1'
const API_URL = (process.env.NODE_ENV === 'test') ?
process.env.BASE_URL || (`http://localhost:${process.env.PORT}/api/${version}/`)
: ``;//`/api`;
// : `/api/${version}`;

export const _token = document.head.querySelector('meta[name="csrf-token"]').content;
axios.defaults.baseURL = API_URL;
axios.defaults.headers.common.Accept = 'application/json';
axios.defaults.headers.common['X-CSRF-TOKEN'] = _token;//window.Laravel.csrfToken;
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

axios.interceptors.response.use(
  response => response,
  (error) => {
    console.log({err: error});
    if (error.response) {
      if (error.response.status === 401 && window.location.pathname !== '/login') {
        configureStore().dispatch(logoutUser())
        toast.warning('Unauthorized. Not Logged In')
      } else if (error.response.status === 500) {
        toast.error(error.message)
      } else {
        toast.error(error.message)
      }
    } else {
      toast.error(`${error.message}. Please Try Again`)
    }
    return Promise.reject(error);
  });

export default axios
