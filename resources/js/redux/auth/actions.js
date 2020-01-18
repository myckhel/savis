import {
  LOGIN_USER,
  LOGIN_USER_SUCCESS,
  LOGIN_ERROR,
  LOGOUT_USER,
  REGISTER_USER,
  REGISTER_USER_SUCCESS,
  REGISTER_ERROR,
  CHECK_AUTH,
  STORE_USER,
} from '../../constants/actionTypes';

export const loginUser = (user, history) => ({
  type: LOGIN_USER,
  payload: { user, history }
});
export const loginUserSuccess = (user) => ({
  type: LOGIN_USER_SUCCESS,
  payload: user
});

export const registerUser = (user, history) => ({
  type: REGISTER_USER,
  payload: { user, history }
})
export const registerUserSuccess = (user) => ({
  type: REGISTER_USER_SUCCESS,
  payload: user
})

export const logoutUser = (history) => ({
  type: LOGOUT_USER,
  payload : {history}
});

export const checkAuth = () => ({
  type: CHECK_AUTH,
  payload: null
});

export const storeUser = (user) => ({
  type: STORE_USER,
  payload: user
})

export const loginError = (errors) => ({
  type: LOGIN_ERROR,
  payload: errors
})

export const registerError = (errors) => ({
  type: REGISTER_ERROR,
  payload: errors
})
