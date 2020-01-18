import _ from 'lodash'

export const addErrors = (errors, payload) => {
  // remove existing
  _.forOwn(payload, (message, field) => {
    errors.remove(field);
  });
  // add
  _.forOwn(payload, (message, field) => {
    errors.add(field, message);
  });
  return errors
}

export const removeErrors = (errors) => {
  _.forOwn(errors, (message, field) => {
    errors.remove(field);
  });
  return errors
}
