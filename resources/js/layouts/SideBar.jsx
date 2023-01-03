import { BankOutlined, DashboardOutlined } from '@ant-design/icons';
import { Inertia } from '@inertiajs/inertia';
import { usePage } from '@inertiajs/inertia-react';
import { Layout, Menu } from 'antd';
import { memo } from 'react';
import { Logo } from './Header';

const { Sider } = Layout;

const items = [
  { Icon: DashboardOutlined, key: '/dash', label: 'Dashboard' },
  {
    Icon: BankOutlined,
    key: '/dash/businesses',
    label: 'Businesses'
  }
].map(({ Icon, label, key }) => ({
  key,
  icon: <Icon />,
  label,
  onClick: () => Inertia.visit(key)
}));

const SideBar = memo(() => {
  const { url } = usePage();

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
        defaultSelectedKeys={url}
        items={items}
      />
    </Sider>
  );
});

export default SideBar;
