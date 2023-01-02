import {
  LoginForm,
  ProFormCheckbox,
  ProFormText
} from '@ant-design/pro-components';
import { LockOutlined, MailOutlined } from '@ant-design/icons';
import { Tabs } from 'antd';
import useState from 'use-react-state';
import { memo } from 'react';
import './styles.sass';
import { BasicLayout } from '../../layouts/Layout';
import { logo } from '../../../assets/images';

const Auth = memo(() => {
  const [{ loginType }, setState] = useState({
    loginType: 'account'
  });

  return (
    <div className="flex h-full items-center justify-center">
      <LoginForm
        logo={logo}
        title="Savis"
        subTitle="Nigerian largest online service rendering"
        onFinish={e => console.log(e)}
        // actions={
        //   <Space>
        //     other login methods
        //     <WeiboCircleOutlined style={iconStyles} />
        //   </Space>
        // }
      >
        <Tabs
          centered
          activeKey={loginType}
          onChange={loginType => setState({ loginType })}
          items={[
            {
              label: `Password login`,
              key: 'account',
              children: (
                <>
                  <ProFormText
                    name="email"
                    fieldProps={{
                      size: 'large',
                      prefix: <MailOutlined className={'prefixIcon'} />
                    }}
                    placeholder={'Enter email address'}
                    rules={[
                      {
                        required: true,
                        message: 'Please enter a email!'
                      }
                    ]}
                  />
                  <ProFormText.Password
                    name=" password "
                    fieldProps={{
                      size: 'large',
                      prefix: <LockOutlined className={'prefixIcon'} />
                    }}
                    placeholder={'Enter password'}
                    rules={[
                      {
                        required: true,
                        message: 'Please enter your password! '
                      }
                    ]}
                  />
                </>
              )
            }
            // {
            //   label: `Mobile number login`,
            //   key: 'phone',
            //   children: (
            //     <>
            //       <ProFormText
            //         fieldProps={{
            //           size: 'large',
            //           prefix: <MobileOutlined className={'prefixIcon'} />
            //         }}
            //         name=" mobile "
            //         placeholder={'Mobile phone number'}
            //         rules={[
            //           {
            //             required: true,
            //             message: 'Please enter your phone number! '
            //           }
            //           // {
            //           //   pattern: / ^1\d{10}$ /,
            //           //   message: 'The mobile phone number format is wrong! '
            //           // }
            //         ]}
            //       />
            //       <ProFormCaptcha
            //         fieldProps={{
            //           size: 'large',
            //           prefix: <LockOutlined className={'prefixIcon'} />
            //         }}
            //         captchaProps={{ size: 'large' }}
            //         placeholder={'Please enter the verification code'}
            //         captchaTextRender={(timing, count) => {
            //           if (timing) {
            //             return ` ${count} ${'get verification code'} `;
            //           }
            //           return 'get verification code';
            //         }}
            //         name="captcha"
            //         rules={[
            //           {
            //             required: true,
            //             message: 'Please enter the verification code! '
            //           }
            //         ]}
            //         onGetCaptcha={async () => {
            //           message.success(
            //             'Obtain the verification code successfully! The verification code is: 1234'
            //           );
            //         }}
            //       />
            //     </>
            //   )
            // }
          ]}
        />
        <div style={{ marginBlockEnd: 24 }}>
          <ProFormCheckbox noStyle name=" autoLogin ">
            automatic log-in
          </ProFormCheckbox>
          <a
            href="./#"
            style={{
              float: 'right'
            }}
          >
            Forgot password
          </a>{' '}
          _ _
        </div>
      </LoginForm>
    </div>
  );
});

Auth.layout = page => <BasicLayout title="Authenticate" children={page} />;

export default Auth;
