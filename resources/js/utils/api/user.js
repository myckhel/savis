import request from './request';

export const loginUser = data => request('api/auth/login', data, 'post');

export const logoutUser = data => request('api/auth/logout', data, 'get');

export const registerUser = data => request('api/auth/signup', data, 'post');
