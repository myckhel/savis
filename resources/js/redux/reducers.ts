import { mergeReducers } from 'use-redux-states';
import auth from './auth';
import { Action } from 'redux';

const appReducer = mergeReducers({ auth });

const rootReducer = (
  state: ReturnType<typeof appReducer> | undefined,
  action: Action
) => {
  if (action.type === 'auth/logoutUser') {
    // for all keys defined in your persistConfig(s)
    // localStorage.clear()
    state = undefined;
  }
  return appReducer(state, action);
};

export default rootReducer;
