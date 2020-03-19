import Http from '../../util/Http'
import request from './request'

export const payments = (customer_id) => request(`customers/payments/${customer_id}`)
export const jobs = (customer_id) => request(`customers/jobs/${customer_id}`)
export const properties = (customer_id) => request(`customers/properties/${customer_id}`)

export const fetchCustomers = ({selectedPageSize,currentPage,selectedOrderOption,search}) => {
  return new Promise((resolve, reject) => {
    Http.get(`/api/customers?pageSize=${selectedPageSize}&page=${currentPage}
      &orderBy=${selectedOrderOption.column}&search=${search}`)
    .then(res => res.data).then(data=>{
      resolve(data)
    })
    .catch((err) => {
      reject(err)
    })
  });
}

// export const request = (route, data = null, method = 'get') => {
//   return new Promise(async function(resolve, reject) {
//     try {
//       data = data ? (method === 'post') ? data : {params: {data}} : null
//       const res = await Http[method](route, data)
//       console.log(res);
//       resolve(res.data)
//     } catch (e) {
//       reject(e)
//     }
//   });
// }

export const customerProfile = (id) => {
  return request(`customers/profile/${parseInt(id)}`);
}

export const deleteCustomers = (ids) => {
  // let data = new URLSearchParams();
  // data.append("ids", ids);
  return new Promise( async function(resolve, reject) {
    try {
      const res = await Http.delete(`/api/customers/delete/multiple`, {params:{ids}})
      resolve(res.data)
    } catch (e) {
      reject(e)
    }
  });
}

export const addCustomers = (data) => {
  return new Promise(async (resolve, reject) => {
    try {
      const res = await Http.post(`api/customers`, data)
      resolve(res.data)
    } catch (e) {
      reject(e)
    }
  })
}

export const viewCustomers = (id) => {
  return new Promise( async (resolve, reject) => {
    try {
      const res = await Http.get(`api/customers/${id}`)
      resolve(res.data)
    } catch (e) {
      reject(e)
    }
  })
}

export const searchCustomers = (query) => {
  return new Promise(async (resolve, reject) => {
    try {
      const res = await Http.get(`api/customers?${query}`)
      resolve(res.data)
    } catch (e) {
      reject(e)
    }
  })
}
