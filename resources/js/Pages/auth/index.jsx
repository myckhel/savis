import {
  LoginForm,
  ProFormCheckbox,
  ProFormText
} from '@ant-design/pro-components';
import { LockOutlined, MailOutlined } from '@ant-design/icons';
import { Tabs } from 'antd';
import useState from 'use-react-state';
import { memo } from 'react';

const Auth = () => {
  const [{ loginType }, setState] = useState({
    loginType: 'phone'
  });

  return (
    <div className="flex h-screen items-center justify-center bg-yellow-200">
      <LoginForm
        logo="https://www.pngall.com/wp-content/uploads/8/Service-Gear-PNG-Free-Download.png"
        title="Savis"
        subTitle="Nigerian largest online service rendering"
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
        <div
          style={{
            marginBlockEnd: 24
          }}
        >
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
};

export default memo(Auth);
