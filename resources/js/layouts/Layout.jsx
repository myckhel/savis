// import Router from "next/router";
import { Head } from '@inertiajs/inertia-react';
import { Layout as ALayout, theme } from 'antd';
import { memo } from 'react';
import Footer from './Footer';
import Header from './Header';
import SideBar from './SideBar';
// import { getUser } from "../redux/action/auth";

const { Content } = ALayout;

const Layout = memo(({ children, title, description }) => {
  const {
    token: { colorBgContainer }
  } = theme.useToken();

  return (
    <ALayout hasSider>
      <Head title={`Savis | ${title}`} />
      <SideBar />
      <ALayout
        className="site-layout"
        style={{
          marginLeft: 200
        }}
      >
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
        <Footer />
      </ALayout>
    </ALayout>
  );
});

export const BasicLayout = memo(({ children, title }) => {
  const {
    token: { colorBgContainer }
  } = theme.useToken();

  return (
    <ALayout>
      <Head title={`Savis | ${title}`} />
      <ALayout className="site-layout">
        <Header showLogo />
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
        <Footer />
      </ALayout>
    </ALayout>
  );
});

export default Layout;
