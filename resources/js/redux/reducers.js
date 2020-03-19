import { combineReducers } from 'redux';
import settings from './settings/reducer';
import menu from './menu/reducer';
import authUser from './auth/reducer';
// import user from './user/reducer';

const reducers = combineReducers({
  menu,
  settings,
  authUser,
  // user
});

export default reducers;
