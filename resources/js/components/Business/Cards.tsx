import { Link } from '@inertiajs/react';
import { Badge, Card, List, Tag } from 'antd';
import { memo } from 'react';
import { route } from 'ziggy-js';
import { placeholder } from '../../../assets/images';
import { useUser } from '../../redux/auth/hooks';
import { getBusinesses } from '../../utils/api/business';
import useRequest from '../../utils/hooks/useRequest';
const { Meta } = Card;

interface BusinessCardProps {
  name: string;
  description: string;
  avatar: { thumb: string } | null;
  isOwner: boolean;
  id: number;
}

const BusinessCard = memo(
  ({ name, description, avatar, isOwner, id }: BusinessCardProps) => (
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
  )
);

interface BusinessCardsProps {
  name?: string;
}

export const BusinessCards = memo(
  ({ name = 'business.all' }: BusinessCardsProps) => {
    // @ts-expect-error
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
          dataSource={data?.length ? data : []}
          renderItem={p => (
            // @ts-expect-error
            <BusinessCard isOwner={userId === p.user_id} {...p} />
          )}
        />
      </div>
    );
  }
);

export default BusinessCard;
