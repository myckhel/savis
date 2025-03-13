import { notification } from 'antd';
import { ArgsProps } from 'antd/es/notification';
import { IconType } from 'antd/es/notification/interface';
import { forOwn } from 'lodash';

interface NotifyParams {
  type: string;
  [key: string]: any;
}

interface NotifyErrorParams {
  type?: IconType;
  description?: string;
  message?: string;
}

interface Errors {
  remove: (field: string) => void;
  add: (field: string, message: string) => void;
}

export const Notify = ({ type, ...p }: ArgsProps) =>
  notification[type as IconType](p);

export const NotifyError = ({
  type = 'error',
  description,
  message = 'Ooooops!'
}: NotifyErrorParams) =>
  notification[type]({
    message: message || 'Something bad happened',
    description
  });

export const readErrors = (e: any): string[] => {
  const messages: string[] = [];
  forOwn(e.response.data.errors, (msgs: string[]) => {
    msgs.map((msg: string) => messages.push(msg));
  });
  return messages;
};

export const addErrors = (
  errors: Errors,
  payload: Record<string, string>
): Errors => {
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

export const removeErrors = (errors: Errors): Errors => {
  forOwn(errors, (message, field) => {
    errors.remove(field);
  });
  return errors;
};

export const propStyles = (
  retStyle: Record<string, any>,
  props: Record<string, any> = {}
): Record<string, any> => {
  const styles: Record<string, string> = {
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

  const boolies: Record<string, string> = {
    bold: 'fontWeight',
    center: 'justifyContent'
  };

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
