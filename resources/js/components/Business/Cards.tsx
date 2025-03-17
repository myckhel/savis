import { Link } from '@inertiajs/react';
import { Badge, Card, List, Tag } from 'antd';
import { memo, useCallback } from 'react';
import { route } from 'ziggy-js';
import { placeholder } from '../../../assets/images';
import { useUser } from '../../redux/auth/hooks';
import { useList } from '@refinedev/core';
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
    <Link href={route('app.businesses.show', [id])}>
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

interface BusinessCardsProps {}

export const BusinessCards = memo(({}: BusinessCardsProps) => {
  // @ts-expect-error
  const userId = useUser(({ id } = {}) => id);

  const { data } = useList({ resource: 'business' });

  const renderItem = useCallback(
    (item: any) => (
      <BusinessCard
        key={item.id}
        name={item.name}
        description={item.description}
        avatar={item.avatar}
        isOwner={item.user_id === userId}
        id={item.id}
      />
    ),
    [userId]
  );

  return (
    <div className="flex">
      <List
        grid={{ gutter: 2, column: 4 }}
        dataSource={data?.data?.length ? data?.data : []}
        renderItem={renderItem}
      />
    </div>
  );
});

export default BusinessCard;
