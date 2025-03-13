import { memo } from 'react';
import { BusinessCards } from '../../../components/Business/Cards';
import Layout from '../../../layouts/Layout';

const Home = memo(() => (
  <div>
    <h1>Businesses</h1>
    <BusinessCards />
  </div>
));

Home.layout = page => <Layout title="Businesses" children={page} />;

export default Home;
