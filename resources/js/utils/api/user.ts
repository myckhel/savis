import request from './request';

export const loginUser = (data: any) =>
  request('/api/auth/login', data, 'post');

export const logoutUser = (data: any) =>
  request('/api/auth/logout', data, 'get');

export const registerUser = (data: any) =>
  request('/api/auth/signup', data, 'post');
