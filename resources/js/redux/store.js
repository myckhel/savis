import { configureStore } from '@reduxjs/toolkit';
import hardSet from 'redux-persist/lib/stateReconciler/hardSet';
import {
  setConfig,
  SET_REDUX_STATE,
  SUBSCRIBE_REDUX_STATE
} from 'use-redux-states';
import storage from 'redux-persist/lib/storage';
import rootReducer from './reducers';
import {
  persistStore,
  persistReducer,
  REHYDRATE,
  PERSIST
} from 'redux-persist';
import createSagaMiddleware from '@redux-saga/core';

const persistConfig = {
  key: 'root',
  storage,
  stateReconciler: hardSet
};

const reducer = persistReducer(persistConfig, rootReducer);

const sagaMiddleware = createSagaMiddleware();

const store = configureStore({
  reducer,
  middleware: getDefaultMiddleware => [
    sagaMiddleware,
    ...getDefaultMiddleware({
      serializableCheck: {
        ignoredActions: [
          PERSIST,
          REHYDRATE,
          SET_REDUX_STATE,
          SUBSCRIBE_REDUX_STATE
        ]
      }
    })
  ]
});

setConfig({ cleanup: false });

export const persistor = persistStore(store);

export default store;
