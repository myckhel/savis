import request from './request';

export const getBusinesses = data => request('api/business', data);
export const showBusiness = (id, data) => request('api/business/' + id, data);
