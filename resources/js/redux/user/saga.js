import { all, call, fork, put, takeEvery } from 'redux-saga/effects';
import {
    CURRENT_USER,
} from '../../constants/actionTypes';

import {user} from '../../helpers'

import {
    getCurrentUser,
    storeUser
} from './actions';

const getCurrentUserAsync = async () =>
    await user.getCurrentUser()
        .then(res => res)
        .catch(error => error);


function* currentUser() {
    try {
        const user = yield call(getCurrentUserAsync);
        if (user.status) {
            yield put(storeUser(user.user));
        } else {
            // catch throw
            console.log('Unauthenticated :', user.text)
        }
    } catch (error) {
        // catch throw
        console.log('authentication error : ', error)
    }
}


export function* watchGetUser() {
    yield takeEvery(CURRENT_USER, currentUser);
}

export default function* rootSaga() {
    yield all([
        fork(watchGetUser),
    ]);
}
