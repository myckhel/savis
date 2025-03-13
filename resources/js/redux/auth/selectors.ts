export const selectAuthUser = ({ auth }) => auth.user;

export const selectIsAuth = ({ auth }) => auth.user && auth.token;
