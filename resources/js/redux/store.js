import { configureStore } from '@reduxjs/toolkit';
import {
  mergeReducers,
  setConfig,
  SET_REDUX_STATE,
  SUBSCRIBE_REDUX_STATE
} from 'use-redux-states';

const reducer = mergeReducers({});

const store = configureStore({
  reducer,
  middleware: getDefaultMiddleware =>
    getDefaultMiddleware({
      serializableCheck: {
        ignoredActions: [SET_REDUX_STATE, SUBSCRIBE_REDUX_STATE]
      }
    })
});

setConfig({ cleanup: false });

export default store;
