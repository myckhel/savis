import {
  LoginForm,
  ProFormCheckbox,
  ProFormText
} from '@ant-design/pro-components';
import { LockOutlined, MailOutlined, UserOutlined } from '@ant-design/icons';
import { Tabs } from 'antd';
import useState from 'use-react-state';
import { memo } from 'react';
import './styles.sass';
import { BasicLayout } from '../../layouts/Layout';
import { logo } from '../../../assets/images';
import { useCallback } from 'react';
import { loginUser, registerUser } from '../../utils/api/user';
import { Notify } from '../../utils';
import { batch, useDispatch } from 'react-redux';
import { setToken, storeUser } from '../../redux/auth';
import { Inertia } from '@inertiajs/inertia';

const Auth = memo(() => {
  const dispatch = useDispatch();
  const [{ loginType, password }, setState] = useState({
    loginType: 'login',
    password: undefined
  });

  const onFinish = useCallback(
    async ({ name, email, password, password_confirmation }) => {
      try {
        const {
          access_token: token,
          token_type,
          expires_at,
          user
        } = await (loginType === 'login' ? loginUser : registerUser)({
          name,
          email,
          password,
          password_confirmation
        });

        batch(() => {
          dispatch(storeUser(user));
          dispatch(setToken({ token, token_type, expires_at }));
        });

        Inertia.get('/dash', {}, { replace: true });

        Notify({
          type: 'success',
          message: 'Yaaaaaay!',
          description: loginType === 'login' ? 'Logged In!' : 'Registered!'
        });
      } catch (error) {
        console.log(error);
      }
    },
    [loginType]
  );

  const auth = (
    <>
      {loginType === 'signup' && (
        <ProFormText
          name="name"
          fieldProps={{
            size: 'large',
            prefix: <UserOutlined className={'prefixIcon'} />,
            type: 'text'
          }}
          placeholder={'Enter name'}
          rules={[
            {
              required: true,
              message: 'Please enter a name!'
            }
          ]}
        />
      )}
      <ProFormText
        name="email"
        fieldProps={{
          size: 'large',
          prefix: <MailOutlined className={'prefixIcon'} />,
          type: 'email'
        }}
        placeholder={'Enter email address'}
        rules={[
          {
            required: true,
            message: 'Please enter an email!'
          },
          {
            pattern: /^\w+([.-]?\w+)*@\w+([.-]?\w+)*(.\w{2,3})+$/,
            message: 'Please enter a valid email!'
          }
        ]}
      />
      <ProFormText.Password
        name="password"
        fieldProps={{
          size: 'large',
          prefix: <LockOutlined className={'prefixIcon'} />,
          type: 'password',
          onChange: ({ target: { value: password } }) => setState({ password })
        }}
        placeholder={'Enter password'}
        rules={[
          {
            required: true,
            message: 'Please enter your password! '
          }
        ]}
      />
      {loginType === 'signup' && (
        <ProFormText.Password
          name="password_confirmation"
          fieldProps={{
            size: 'large',
            prefix: <LockOutlined className={'prefixIcon'} />,
            type: 'password'
          }}
          placeholder={'Enter password confirmation'}
          rules={[
            {
              required: true,
              message: 'Please confirm your password! '
            },
            {
              validator: (_, _password) => {
                if (password === _password) {
                  return Promise.resolve(true);
                } else {
                  return Promise.reject({ message: 'Password does not match' });
                }
              }
            }
          ]}
        />
      )}
      {loginType === 'login' && (
        <div style={{ marginBlockEnd: 24 }}>
          <ProFormCheckbox noStyle name="remember">
            remember me
          </ProFormCheckbox>
          <a href=".#" style={{ float: 'right' }}>
            Forgot password
          </a>
        </div>
      )}
    </>
  );

  return (
    <div className="flex flex-col h-full items-center justify-center">
      <LoginForm
        autoComplete="on"
        logo={logo}
        title="Savis"
        subTitle="Nigerian largest online service rendering"
        onFinish={onFinish}
      >
        <Tabs
          centered
          activeKey={loginType}
          onChange={loginType => setState({ loginType })}
          items={[
            {
              label: `Login`,
              key: 'login',
              children: auth
            },
            {
              label: 'Signup',
              key: 'signup',
              children: auth
            }
          ]}
        />
      </LoginForm>
    </div>
  );
});

Auth.layout = page => <BasicLayout title="Authenticate" children={page} />;

export default Auth;
