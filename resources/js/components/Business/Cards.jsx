import { Link } from '@inertiajs/inertia-react';
import { Badge, Card, List, Tag } from 'antd';
import { memo } from 'react';
import route from 'ziggy-js';
import { placeholder } from '../../../assets/images';
import { useUser } from '../../redux/auth/hooks';
import { getBusinesses } from '../../utils/api/business';
import useRequest from '../../utils/hooks/useRequest';
const { Meta } = Card;

const BusinessCard = memo(({ name, description, avatar, isOwner, id }) => (
  <Link href={route('businesses.show', [id])}>
    <Badge.Ribbon text="category">
      <Card cover={<img alt={name} src={avatar?.thumb || placeholder} />}>
        <Meta title={name} description={description} />
        <Tag className="mt-4" color={isOwner ? '#2db7f5' : '#f50'}>
          {isOwner ? 'Owner' : 'Worker'}
        </Tag>
      </Card>
    </Badge.Ribbon>
  </Link>
));

export const BusinessCards = memo(({ name = 'business.all' }) => {
  const userId = useUser(({ id } = {}) => id);
  const {
    state: { data }
  } = useRequest({
    asyncRequest: getBusinesses,
    name,
    dataPath: 'business.byId'
  });

  return (
    <div className="flex">
      <List
        grid={{ gutter: 2, column: 4 }}
        dataSource={data}
        renderItem={p => <BusinessCard isOwner={userId === p.user_id} {...p} />}
      />
    </div>
  );
});

export default BusinessCard;
