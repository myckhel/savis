import Http from './Http';

import { storeUser, setToken } from '../../redux/actions';
import { Notify, readErrors } from '../';
import store from '../../redux/store';

const request = async (route, data = {}, method = 'get', config = {}) => {
  try {
    const params =
      method === 'post' || method === 'put' ? data : { params: data };
    console.log(params);
    const res = await Http[method](route, params, config);

    if (['login', 'register', 'auth'].includes(route)) {
      if (res?.data?.user) {
        store.dispatch(storeUser(res.data.user));
      }
    }

    if (res?.data?.token) {
      Http.defaults.headers.common[
        'Authorization'
      ] = `Bearer ${res.data.token}`;
      store.dispatch(setToken({ token: res.data.token, cache: true }));
    }

    console.log(res);
    return res.data;
  } catch (e) {
    if (e.message) {
      e.message === 'Networ Error' &&
        Notify({ type: 'error', message: e.message });
      if (
        e.response &&
        (e.response.status === 422 || e.response.status === 400)
      ) {
        if (e.response.data.errors || e.response.data.error) {
          if (!e.response.data.errors) {
            e.response.data.errors = e.response.data.error;
          }
          readErrors(e).map((err, i) =>
            Notify({
              type: 'error',
              description: err,
              message: i === 0 && e.response.data.message
            })
          );
        }
      }
    }
    console.log({ e });
    return Promise.reject(e);
  }
};

export const fake = callback =>
  new Promise(ressolve =>
    setTimeout(() => ressolve(callback && callback()), 2000)
  );

export default request;
