import { useDispatch } from 'react-redux';
import { useMemoSelector, useRootMemoSelector } from 'use-redux-states';

import { selectIsAuth } from './selectors';
import { logoutUser } from '.';

export const useLogout = () => {
  const dispatch = useDispatch();
  return () => {
    dispatch(logoutUser());
  };
};

export const useUser = (...p) => useRootMemoSelector('auth.user', ...p);

export const useIsLoggedIn = () => useMemoSelector(selectIsAuth);
