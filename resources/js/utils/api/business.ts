import request from './request';

export const getBusinesses = (data: any) => request('/api/business', data);
export const showBusiness = (id: string, data: any) =>
  request('/api/business/' + id, data);
