import { Head, usePage } from '@inertiajs/react';
import { memo, ReactNode } from 'react';
import Layout from '../../../layouts/Layout';
import { showBusiness } from '../../../utils/api/business';
import { useDataRequest } from '../../../utils/hooks/useRequest';

const Home = memo(() => {
  const {
    props: { id }
  } = usePage<{ id: string }>();

  const { data } = useDataRequest({
    name: `business.byId.${id}`,
    asyncRequest: showBusiness,
    params: [id],
    loadOnMount: !!id
  });

  return (
    <div>
      {data?.name && <Head title={data?.name} />}
      <h1>{data?.name}</h1>
    </div>
  );
});

// @ts-expect-error
Home.layout = (page: ReactNode) => (
  <Layout title="Businesses" children={page} />
);

export default Home;
