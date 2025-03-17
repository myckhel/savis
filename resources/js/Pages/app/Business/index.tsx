import { memo, ReactNode } from 'react';
import { BusinessCards } from '../../../components/Business/Cards';
import Layout from '../../../layouts/Layout';
import { Button, Row } from 'antd';

const Home = () => (
  <div>
    <Row align="middle" justify="space-between">
      <h1>Businesses</h1>
      <Button type="primary" href="/dash/businesses/create">
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
