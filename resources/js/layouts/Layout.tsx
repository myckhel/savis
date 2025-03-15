import { Head } from '@inertiajs/react';
import { Layout as ALayout, notification, theme } from 'antd';
import { memo, FC, ReactNode } from 'react';
import Footer from './Footer';
import Header from './Header';
import SideBar from './SideBar';

const { Content } = ALayout;

interface LayoutProps {
  children: ReactNode;
  title: string;
  description?: string;
}

const Layout: FC<LayoutProps> = memo(({ children, title, description }) => {
  const {
    token: { colorBgContainer }
  } = theme.useToken();
  const [, contextHolder] = notification.useNotification();

  return (
    <ALayout hasSider>
      <Head title={`Savis | ${title}`} />
      <SideBar />
      <ALayout
        className="site-layout h-screen"
        style={{
          marginLeft: 200
        }}
      >
        <Header />
        <Content
          className="h-full"
          style={{
            margin: '24px 16px',
            padding: 24,
            background: colorBgContainer
          }}
        >
          {contextHolder}
          {children}
        </Content>
        <Footer />
      </ALayout>
    </ALayout>
  );
});

interface BasicLayoutProps {
  children: ReactNode;
  title: string;
}

export const BasicLayout: FC<BasicLayoutProps> = memo(({ children, title }) => {
  const {
    token: { colorBgContainer }
  } = theme.useToken();
  const [, contextHolder] = notification.useNotification();

  return (
    <ALayout>
      <Head title={`Savis | ${title}`} />
      <ALayout className="site-layout h-screen">
        <Header showLogo />
        <Content
          className="h-full"
          style={{
            margin: '24px 16px',
            padding: 24,
            background: colorBgContainer
          }}
        >
          {contextHolder}
          {children}
        </Content>
        <Footer />
      </ALayout>
    </ALayout>
  );
});

export default Layout;
