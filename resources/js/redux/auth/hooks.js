import { useDispatch } from "react-redux";
import { useMemoSelector, useRootMemoSelector } from "use-redux-states";

import { logoutUser } from "./";
import { selectIsAuth } from "./selectors";

export const useLogout = () => {
  const dispatch = useDispatch();
  return () => {
    dispatch(logoutUser());
  };
};

export const useUser = (...p) => useRootMemoSelector("auth.user", ...p);

export const useIsLoggedIn = () => useMemoSelector(selectIsAuth);
