import { createSlice } from '@reduxjs/toolkit';

const INIT_STATE = {
  token: null,
  user: null,
  token_type: null,
  expires_at: null
};

const { actions, reducer } = createSlice({
  name: 'auth',
  initialState: INIT_STATE,
  reducers: {
    setToken: (state, { payload: { token, token_type, expires_at } }) => {
      state.token = token;
      state.token_type = token_type;
      state.expires_at = expires_at;
    },

    logoutUser: state => {
      state.token = null;
      state.user = null;
    },

    signOutSuccess: () => {},

    storeUser: (state, { payload }) => {
      state.user = payload;
    }
  }
});

export const { setToken, logoutUser, signOutSuccess, storeUser } = actions;

export default reducer;
