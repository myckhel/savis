import {
  CURRENT_USER,
  STORE_USER
} from '../../constants/actionTypes';

const INIT_STATE = {
  currentUser: {}
};

export default (state = INIT_STATE, action) => {
	switch (action.type) {

		case CURRENT_USER:
		return { ...state };

    case STORE_USER:
		return Object.assign({}, state, {
			currentUser: 'mic'//action.payload.user,
		})

		default: return { ...state };
	}
}
