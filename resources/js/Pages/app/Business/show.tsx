import { Head, usePage } from '@inertiajs/react';
import { memo, ReactNode } from 'react';
import Layout from '../../../layouts/Layout';
import { useOne } from '@refinedev/core';

const Home = memo(() => {
  const {
    props: { id }
  } = usePage<{ id: string }>();

  const { data } = useOne({ resource: 'business', id });
  const business = data?.data;

  return (
    <div>
      {business?.name && <Head title={business?.name} />}
      <h1>{business?.name}</h1>
    </div>
  );
});

// @ts-expect-error
Home.layout = (page: ReactNode) => (
  <Layout title="Businesses" children={page} />
);

export default Home;
