import { memo, ReactNode } from 'react';
import { BusinessCards } from '../../../components/Business/Cards';
import Layout from '../../../layouts/Layout';
import { Button, Row } from 'antd';
import { route } from 'ziggy-js';

const Home = () => (
  <div>
    <Row align="middle" justify="space-between">
      <h1>Businesses</h1>
      <Button type="primary" href={route('app.businesses.create')}>
        Create Business
      </Button>
    </Row>

    <BusinessCards />
  </div>
);
Home.layout = (page: ReactNode) => (
  <Layout title="Businesses" children={page} />
);

export default Home;
