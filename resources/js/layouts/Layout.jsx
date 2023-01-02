// import Router from "next/router";
import { Layout as ALayout, theme } from 'antd';
import Header from './Header';
// import { getUser } from "../redux/action/auth";

const { Content } = ALayout;

const Layout = ({ children }) => {
  const {
    token: { colorBgContainer }
  } = theme.useToken();

  return (
    <ALayout>
      {/* <Sider trigger={null} collapsible collapsed={collapsed}>
        <div className="logo" />
        <Menu
          theme="dark"
          mode="inline"
          defaultSelectedKeys={['1']}
          items={[
            {
              key: '1',
              icon: <UserOutlined />,
              label: 'nav 1'
            },
            {
              key: '2',
              icon: <VideoCameraOutlined />,
              label: 'nav 2'
            },
            {
              key: '3',
              icon: <UploadOutlined />,
              label: 'nav 3'
            }
          ]}
        />
      </Sider> */}
      <ALayout className="site-layout">
        <Header />
        <Content
          style={{
            margin: '24px 16px',
            padding: 24,
            minHeight: 280,
            background: colorBgContainer
          }}
        >
          {children}
        </Content>
      </ALayout>
    </ALayout>
  );
};

export default Layout;
