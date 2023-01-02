import { DatePicker } from 'antd';
import { memo } from 'react';
import Layout from '../../layouts/Layout';

const Home = memo(() => (
  <div>
    <h1>Hello</h1>
    <DatePicker />
    <button className="bg-blue-200 p-4">btn</button>
  </div>
));

Home.layout = page => <Layout title="Home" children={page} />;

export default Home;
