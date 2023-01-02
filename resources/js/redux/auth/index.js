import { createSlice } from "@reduxjs/toolkit";

const INIT_STATE = {
  token: null,
  user: null,
};

const { actions, reducer } = createSlice({
  name: "auth",
  initialState: INIT_STATE,
  reducers: {
    setToken: (state, { payload: token }) => {
      state.token = token;
    },

    logoutUser: (state) => {
      state.token = null;
      state.user = null;
    },

    signOutSuccess: () => {},

    storeUser: (state, { payload }) => {
      state.user = payload;
    },
  },
});

export const { setToken, logoutUser, signOutSuccess, storeUser } = actions;

export default reducer;
