import { ReactNode } from 'react';
import Layout from '../../../layouts/Layout';
import { Create } from '@refinedev/antd';
import { Form, Input } from 'antd';
import { useForm } from '@refinedev/antd';

const CreateBusiness = () => {
  const form = useForm({
    resource: 'business',
    action: 'create',
    redirect: 'list'
  });

  return (
    <div>
      <Create resource="business" saveButtonProps={form.saveButtonProps}>
        <Form {...form.formProps} layout="vertical">
          <Form.Item
            label="Business Name"
            name="name"
            rules={[{ required: true, message: 'Please enter business name' }]}
          >
            <Input placeholder="Enter business name" />
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
            <Input placeholder="Enter email" />
          </Form.Item>
        </Form>
      </Create>
    </div>
  );
};

CreateBusiness.layout = (page: ReactNode) => (
  <Layout title="Business Create" children={page} />
);

export default CreateBusiness;
