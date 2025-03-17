// {"id":2,"name":"myckhel","email":"myckhel123@gmail.com","email_verified_at":null,"active":0,"metas":null,"lat":null,"lng":null,"created_at":"2025-03-13T00:23:29.000000Z","updated_at":"2025-03-13T00:23:29.000000Z","deleted_at":null}

import { Edit, useForm } from '@refinedev/antd';
import { Form, Input } from 'antd';
import Layout from '../../layouts/Layout';
import { ReactNode } from 'react';
import { usePage } from '@inertiajs/react';
import { useUser } from '../../utils';

const Profile = () => {
  const user = useUser();

  const { saveButtonProps, formProps } = useForm({
    action: 'edit',
    resource: 'users',
    id: user?.id
  });

  return (
    <Edit resource="user" saveButtonProps={saveButtonProps}>
      <Form {...formProps} initialValues={user}>
        <Form.Item
          label="Name"
          name="name"
          rules={[{ required: true, message: 'Please enter your name' }]}
        >
          <Input placeholder="Enter your name" />
        </Form.Item>
        <Form.Item
          label="Email"
          name="email"
          rules={[
            {
              required: true,
              type: 'email',
              message: 'Please enter a valid email'
            }
          ]}
        >
          <Input disabled placeholder="Enter email" />
        </Form.Item>
      </Form>
    </Edit>
  );
};

Profile.layout = (page: ReactNode) => (
  <Layout title="Profile" children={page} />
);

export default Profile;
