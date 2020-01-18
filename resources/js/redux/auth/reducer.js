import {
  LOGIN_USER,
  LOGIN_USER_SUCCESS,
  LOGIN_ERROR,
  REGISTER_USER,
  REGISTER_USER_SUCCESS,
  REGISTER_ERROR,
  LOGOUT_USER,
  CHECK_AUTH,
  STORE_USER,
} from "../../constants/actionTypes";
import Http from '../../util/Http'
import ReeValidate from 'ree-validate'
import { removeErrors, addErrors } from '../../helpers/errors'
// const { errors } = new ReeValidate()

const INIT_STATE = {
  user: null,
  loading: false,
  errors: new ReeValidate().errors,
  access_token: localStorage.getItem("access_token"), //=== "undefined" ? null : localStorage.getItem("access_token"),
  authenticated: !!localStorage.getItem("access_token") //=== "undefined" ? false : true,
};


const merge = (state, newState) => {
  return Object.assign({}, state, newState)
}

export default (state = INIT_STATE, action) => {
  switch (action.type) {
    case LOGIN_USER:
      return { ...state, loading: true };
    case LOGIN_USER_SUCCESS:
      //notify.success('Login Success');
      return { ...state, authenticated: true, loading: false, user: action.payload };
    case REGISTER_USER:
      return { ...state, loading: true };
    case REGISTER_USER_SUCCESS:
      //notify.success('Register User Success');
      return { ...state, authenticated: true, loading: false, user: action.payload };
    case LOGOUT_USER:
      localStorage.removeItem('access_token')
      return { ...state, user: null, authenticated: false, access_token: null };
    case CHECK_AUTH:
      state = merge(state, { authenticated: !!localStorage.getItem('access_token') })

      if (state.access_token) {
        Http.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('access_token')}`;
      }
      return state;
    case STORE_USER:
		  return merge(state, { user: action.payload, authenticated: true} );
    case LOGIN_ERROR:
      let errors = addErrors(new ReeValidate().errors, action.payload)
      return merge(state, { errors, loading: false } );
    case REGISTER_ERROR:
      errors = addErrors(new ReeValidate().errors, action.payload)
      return merge(state, { errors, loading: false } );
    default:
      return { ...state };
  }
};
