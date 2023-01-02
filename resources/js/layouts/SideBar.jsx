import React from 'react';
import {
  AppstoreOutlined,
  BarChartOutlined,
  CloudOutlined,
  ShopOutlined,
  TeamOutlined,
  UploadOutlined,
  UserOutlined,
  VideoCameraOutlined
} from '@ant-design/icons';
import { Layout, Menu } from 'antd';
import { memo } from 'react';
import { Logo } from './Header';

const { Sider } = Layout;

const items = [
  UserOutlined,
  VideoCameraOutlined,
  UploadOutlined,
  BarChartOutlined,
  CloudOutlined,
  AppstoreOutlined,
  TeamOutlined,
  ShopOutlined
].map((icon, index) => ({
  key: String(index + 1),
  icon: React.createElement(icon),
  label: `nav ${index + 1}`
}));

const SideBar = memo(() => {
  return (
    <Sider
      style={{
        overflow: 'auto',
        height: '100vh',
        position: 'fixed',
        left: 0,
        top: 0,
        bottom: 0
      }}
    >
      <Logo />
      <Menu
        theme="dark"
        mode="inline"
        defaultSelectedKeys={['4']}
        items={items}
      />
    </Sider>
  );
});

export default SideBar;
