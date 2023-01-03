import { Head, usePage } from '@inertiajs/inertia-react';
import { memo } from 'react';
import Layout from '../../../layouts/Layout';
import { showBusiness } from '../../../utils/api/business';
import { useDataRequest } from '../../../utils/hooks/useRequest';

const Home = memo(() => {
  const {
    props: { id }
  } = usePage();
  const { data } = useDataRequest({
    path: `business.byId.${id}`,
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

Home.layout = page => <Layout title="Businesses" children={page} />;

export default Home;
