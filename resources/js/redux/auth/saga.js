import Http from '../../util/Http'

import { all, call, fork, put, takeEvery } from 'redux-saga/effects';
import {
    LOGIN_USER,
    REGISTER_USER,
    LOGOUT_USER,
    CHECK_AUTH
} from '../../constants/actionTypes';

import {auth} from '../../helpers'

import {
    loginUserSuccess,
    registerUserSuccess,
    registerError,
    storeUser,
    loginError
} from './actions';

const loginWithEmailPasswordAsync = async (email, password, remember_me) =>
    await auth.signInWithEmailAndPassword(email, password, remember_me)
        .then(authUser => authUser)
        .catch(error => error);


function* loginWithEmailPassword({ payload }) {
    const { email, password, remember_me } = payload.user;
    const { history } = payload;
    try {
        const loginUser = yield call(loginWithEmailPasswordAsync, email, password, remember_me);
        if (loginUser.status) {
          if (loginUser.status === 200) {
              Http.defaults.headers.common['Authorization'] = `Bearer ${loginUser.data.access_token}`;
              localStorage.setItem('access_token', loginUser.data.access_token);
              yield put(loginUserSuccess(loginUser.data.user));
              // history.push('/');
          }
        } else {
          if (loginUser.response.status === 401) {
            yield put(loginError({invalid: loginUser.response.data.message}));
          } else if (loginUser.response.status === 422) {
            // send errors
            yield put(loginError(loginUser.response.data.errors));
          } else {
            yield put(loginError({}));
              // catch throw
              console.log('login failed :', loginUser.response.message)
          }
        }
    } catch (error) {
      yield put(loginError({error: error}));
        // catch throw
        console.log('login error : ', error)
    }

    // return {'obj': 'obg'}
}

const registerWithEmailPasswordAsync = async (email, password, name,  password_confirmation) =>
    await auth.createUserWithEmailAndPassword(email, password, name,  password_confirmation)
        .then(authUser => authUser)
        .catch(error => error);

function* registerWithEmailPassword({ payload }) {
    const { name, email, password, password_confirmation } = payload.user;
    const { history } = payload
    try {
        const registerUser = yield call(registerWithEmailPasswordAsync, name, email, password,  password_confirmation);
        if (registerUser.status === 201) {
          // if (registerUser.message === "Successfully created user!") {
              Http.defaults.headers.common['Authorization'] = `Bearer ${registerUser.data.access_token}`;
              localStorage.setItem('access_token', registerUser.data.access_token);
              yield put(registerUserSuccess(registerUser.data.user));
              // history.push('/')
          // }
        } else {
          if (registerUser.response.status === 401) {
            yield put(registerError({invalid: registerUser.response.data.message}));
          } else if (registerUser.response.status === 422) {
            // send errors
            yield put(registerError(registerUser.response.data.errors));
          } else {
            yield put(registerError({}));
            // catch throw
            console.log('login failed :', registerUser.response.message)
          }
        }
    } catch (error) {
        // catch throw
        console.log('register error : ', error)
    }
}

const checkAuthAsync = async () =>
  await auth.checkAuth()
  .then(authUser => authUser)
  .catch(error => error);


function* checkAuth () {
  const access_token = localStorage.getItem('access_token');
  if (access_token){
    Http.defaults.headers.common['Authorization'] = `Bearer ${access_token}`;
    try {
        const authUser = yield call(checkAuthAsync);
        if (authUser.data.status) {
            yield put(storeUser(authUser.data.user));
        } else {
            // catch throw
            console.log('Auth failed :', authUser.data.text)
        }
    } catch (error) {
        // catch throw
        console.log('Auth error : ', error)
    }
  }
}

const logoutAsync = async (history) => {
    if (history) {
      await auth.logout().then(authUser => authUser).catch(error => error);
      history.push('/')
    } else {
      // window.location.push('/login')
      window.location.reload()
    }
}

function* logout({payload}) {
    const { history } = payload
    try {
        yield call(logoutAsync,history);
        localStorage.removeItem('access_token');
    } catch (error) {
      console.log(error);
    }
}



export function* watchRegisterUser() {
    yield takeEvery(REGISTER_USER, registerWithEmailPassword);
}

export function* watchCheckAuth() {
    yield takeEvery(CHECK_AUTH, checkAuth);
}

export function* watchLoginUser() {
    yield takeEvery(LOGIN_USER, loginWithEmailPassword);
}

export function* watchLogoutUser() {
    yield takeEvery(LOGOUT_USER, logout);
}


export default function* rootSaga() {
    yield all([
        fork(watchLoginUser),
        fork(watchLogoutUser),
        fork(watchRegisterUser),
        fork(watchCheckAuth),
    ]);
}
