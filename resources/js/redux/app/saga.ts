import { REHYDRATE } from 'redux-persist';
import { all, fork, put, takeLatest } from 'redux-saga/effects';
import { SET_TOKEN } from '../../constants/ActionTypes';
import Http from '../../utils/api/Http';

interface RehydrateAction {
  type: typeof REHYDRATE;
  payload: {
    auth?: {
      token?: string;
    };
  };
}

function* rehydrated({ payload }: RehydrateAction) {
  const token = payload?.auth?.token;
  if (token) {
    Http.defaults.headers.common['Authorization'] = `Bearer ${token}`;
    yield put({ type: SET_TOKEN, payload: token });
  }
}

export function* watchRehydration() {
  yield takeLatest(REHYDRATE, rehydrated);
}

export default function* rootSaga() {
  yield all([fork(watchRehydration)]);
}
