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
import { Avatar, Dropdown, Layout, theme, MenuProps } from 'antd';
import { memo } from 'react';
import { logo } from '../../assets/images';
import { Link, router } from '@inertiajs/react';
import { useLogout, useUser } from '../redux/auth/hooks';
import { useMemo } from 'react';

const { Header: AHeader } = Layout;

interface HeaderProps {
  showLogo?: boolean;
}

const Header = ({ showLogo }: HeaderProps) => {
  const [collapsed, setCollapsed] = useState(false);
  const menu = false;
  const {
    token: { colorBgContainer }
  } = theme.useToken();

  return (
    <AHeader
      className="flex items-center justify-between px-4"
      style={{ background: colorBgContainer, paddingInline: 10 }}
    >
      {menu
        ? createElement(collapsed ? MenuUnfoldOutlined : MenuFoldOutlined, {
            className: 'trigger',
            onClick: () => setCollapsed(!collapsed)
          })
        : null}
      {showLogo ? <Logo /> : <div />}
      <UserMenu />
    </AHeader>
  );
};

const UserMenu = memo(() => {
  const user = useUser();
  const logout = useLogout();

  const onClick: MenuProps['onClick'] = async ({ key }) => {
    try {
      if (key === 'signout') {
        await router.visit('/api/auth/logout', { replace: true });
        logout();
      } else if (key === 'signin') {
        await router.visit('/auth');
      } else if (key === 'dashboard') {
        await router.visit('/dash');
      } else {
        await router.visit('/' + key);
      }
    } catch (error) {
      console.log(error);
    }
  };

  const items = useMemo(() => {
    const items: MenuProps['items'] = [];

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
