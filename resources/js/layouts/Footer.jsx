import { memo } from 'react';
import { Layout } from 'antd';

const { Footer: AFooter } = Layout;

const Footer = memo(() => (
  <AFooter style={{ textAlign: 'center' }}>
    Savis ©{new Date().getFullYear()} Created by{' '}
    <a href="https://myckhel.com">Myckhel</a>
  </AFooter>
));

export default Footer;
