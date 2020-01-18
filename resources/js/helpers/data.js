import countries from '../data/countries'

export const getCountries = () => {
  return countries.map( ({name,code,dial_code,currency_name,currency_symbol,currency_code}) => {
    return {code, dial_code,}
  })
}

export const getCountriesCode = () => {
  return countries.map( ({name,code,dial_code,currency_name,currency_symbol,currency_code}) => {
    return {code, dial_code}
  })
}

export const selectable = (datas, fields, type = '') => {
  return datas.map( (data) => {
    if (type === 'country_code') {
      return {label: `${data.code} ${data.dial_code}`, value: data.dial_code}
    } else {
      return {label: `${data[fields[0]]}`, value: data[fields[1]], id: data.id}
    }
  })
}
