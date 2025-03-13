import { memo } from 'react';
import { BasicLayout } from '../layouts/Layout';

const Home = memo(() => (
  <div>
    <h1>Home</h1>
  </div>
));

Home.layout = page => <BasicLayout title="Home" children={page} />;

export default Home;
