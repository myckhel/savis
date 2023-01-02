import { createElement, useState } from 'react';
import {
  DashboardOutlined,
  MenuFoldOutlined,
  MenuUnfoldOutlined,
  PoweroffOutlined,
  SolutionOutlined,
  UserOutlined,
  UserSwitchOutlined
} from '@ant-design/icons';
import { Avatar, Dropdown, Layout, theme } from 'antd';
import { memo } from 'react';
import { logo } from '../../assets/images';
import { Link } from '@inertiajs/inertia-react';
import { useLogout, useUser } from '../redux/auth/hooks';
import { Inertia } from '@inertiajs/inertia';
import { useMemo } from 'react';

const { Header: AHeader } = Layout;

const Header = ({ showLogo }) => {
  const [collapsed, setCollapsed] = useState(false);
  const menu = false;
  const {
    token: { colorBgContainer }
  } = theme.useToken();

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
      <UserMenu />
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
      } else if (key === 'signin') {
        await Inertia.visit('auth');
      } else if (key === 'dashboard') {
        await Inertia.visit('dash');
      }
    } catch (error) {
      console.log(error);
    }
  };

  const items = useMemo(() => {
    const items = [];

    user?.id
      ? items.push(
          {
            key: 'dashboard',
            label: 'Dashboard',
            icon: <DashboardOutlined className="icon" />
          },
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
        )
      : items.push({
          key: 'signin',
          label: 'Sign in / Sign up',
          icon: <UserSwitchOutlined className="icon" />
        });

    return items;
  }, [user?.id]);

  return (
    <Dropdown className="ml-auto mr-4" menu={{ onClick, items }}>
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
