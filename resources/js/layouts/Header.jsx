import { createElement, useState } from 'react';
import { MenuFoldOutlined, MenuUnfoldOutlined } from '@ant-design/icons';
import { Layout, theme } from 'antd';
import { memo } from 'react';
import { logo } from '../../assets/images';

const { Header: AHeader } = Layout;

const Header = () => {
  const [collapsed, setCollapsed] = useState(false);
  const menu = false;
  const {
    token: { colorBgContainer }
  } = theme.useToken();

  return (
    <AHeader
      className="flex items-center"
      style={{ padding: 0, background: colorBgContainer }}
    >
      {menu
        ? createElement(collapsed ? MenuUnfoldOutlined : MenuFoldOutlined, {
            className: 'trigger',
            onClick: () => setCollapsed(!collapsed)
          })
        : null}
      <div className="flex items-center text-2xl font-semibold mx-4 my-auto">
        <img src={logo} className="w-12 h-12" alt="logo" />
        <span>Savis</span>
      </div>
    </AHeader>
  );
};

export default memo(Header);
