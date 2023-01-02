import { notification } from 'antd';

import { forOwn } from 'lodash';

export const Notify = ({ type, ...p }) => notification[type](p);

export const NotifyError = ({
  type = 'error',
  description,
  message = 'Ooooops!'
}) =>
  notification[type]({
    message: message || 'Something bad happened',
    description
  });

export const readErrors = e => {
  const messages = [];
  forOwn(e.response.data.errors, (msgs, field) => {
    msgs.map(msg => messages.push(msg));
  });
  return messages;
};

export const addErrors = (errors, payload) => {
  // remove existing
  forOwn(payload, (message, field) => {
    errors.remove(field);
  });
  // add
  forOwn(payload, (message, field) => {
    errors.add(field, message);
  });
  return errors;
};

export const removeErrors = errors => {
  forOwn(errors, (message, field) => {
    errors.remove(field);
  });
  return errors;
};

export const propStyles = (retStyle, props = {}) => {
  const styles = {
    w: 'width',
    h: 'height',
    color: 'color',
    ff: 'fontFamily',
    l: 'left',
    bottom: 'bottom',
    top: 'marginTop',
    ml: 'marginLeft',
    mr: 'marginRight',
    mt: 'marginTop',
    mb: 'marginBottom',
    r: 'right',
    p: 'padding',
    fs: 'fontSize',
    ls: 'letterSpacing',
    ta: 'textAlign'
  };

  const boolies = { bold: 'fontWeight', center: 'justifyContent' };

  forOwn(props, (value, key) => {
    if (props[key]) {
      retStyle[styles[key]] = value;
    }
  });

  forOwn(boolies, (styleProp, styleValue) => {
    if (props[styleValue]) {
      retStyle[styleProp] = styleValue;
    }
  });

  return retStyle;
};
