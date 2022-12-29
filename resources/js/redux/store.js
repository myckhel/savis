import { combineReducers, createStore } from 'redux';
// import rootReducer from './reducer';

const initialState = {};

// const middleware = [];

const store = createStore(combineReducers({}), initialState);

export default store;
