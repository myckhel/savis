import { createElement, useState } from 'react';
import {
  MenuFoldOutlined,
  MenuUnfoldOutlined,
  PoweroffOutlined,
  SolutionOutlined,
  UserOutlined
} from '@ant-design/icons';
import { Avatar, Dropdown, Layout, theme } from 'antd';
import { memo } from 'react';
import { logo } from '../../assets/images';
import { Link } from '@inertiajs/inertia-react';
import { useIsLoggedIn, useLogout, useUser } from '../redux/auth/hooks';
import { Inertia } from '@inertiajs/inertia';

const { Header: AHeader } = Layout;

const Header = ({ showLogo }) => {
  const [collapsed, setCollapsed] = useState(false);
  const menu = false;
  const {
    token: { colorBgContainer }
  } = theme.useToken();
  const isLoggendin = useIsLoggedIn();

  return (
    <AHeader
      className="flex items-center justify-between"
      style={{ padding: 0, background: colorBgContainer }}
    >
      {menu
        ? createElement(collapsed ? MenuUnfoldOutlined : MenuFoldOutlined, {
            className: 'trigger',
            onClick: () => setCollapsed(!collapsed)
          })
        : null}
      {showLogo && <Logo />}
      {isLoggendin && <UserMenu />}
    </AHeader>
  );
};

const UserMenu = memo(() => {
  const user = useUser();
  const logout = useLogout();

  const onClick = async ({ key }) => {
    try {
      if (key === 'signout') {
        await Inertia.visit('/api/auth/logout', undefined, { replace: true });
        logout();
      }
    } catch (error) {
      console.log(error);
    }
  };

  return (
    <Dropdown
      className="ml-auto mr-4"
      menu={{
        onClick,
        items: [
          {
            key: 'profile',
            label: 'Profile',
            icon: <SolutionOutlined className="icon" />
          },
          {
            key: 'signout',
            label: 'Sign out',
            icon: <PoweroffOutlined className="icon" />,
            danger: true
          }
        ]
      }}
    >
      <Avatar
        className="cursor-pointer"
        icon={user?.avatar || <UserOutlined />}
      />
    </Dropdown>
  );
});

export const Logo = () => (
  <Link
    href="/"
    className="flex items-center text-2xl font-semibold mx-4 my-auto"
  >
    <img src={logo} className="w-12 h-12" alt="logo" />
    <span>Savis</span>
  </Link>
);

export default memo(Header);
