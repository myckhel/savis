import { memo, ReactNode } from 'react';
import Layout from '../../layouts/Layout';

const Home = memo(() => (
  <div>
    <h1>Welcome</h1>
  </div>
));

// @ts-expect-error
Home.layout = (page: ReactNode) => <Layout title="Home" children={page} />;

export default Home;
