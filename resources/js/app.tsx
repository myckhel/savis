import '../sass/app.scss';
// import 'antd/dist/reset.css';
import '@ant-design/v5-patch-for-react-19';
import { createInertiaApp } from '@inertiajs/react';
import { Provider } from 'react-redux';
import store, { persistor } from './redux/store';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ConfigProvider } from 'antd';
import en_US from 'antd/locale/en_US';
import { PersistGate } from 'redux-persist/integration/react';
import Loader from './components/core/Loader';
import { createRoot, hydrateRoot } from 'react-dom/client';
import { Refine } from '@refinedev/core';
import { useNotificationProvider } from '@refinedev/antd';
import '@refinedev/antd/dist/reset.css';
import routerProvider from './utils/refine/routerProvider';
import { Api } from './utils/api/Http';
import { dataProvider } from './utils/rest-data-provider';

// @ts-expect-error
const appName = import.meta.env.VITE_APP_NAME || 'Laravel';
// @ts-expect-error
const { VITE_APP_URL } = import.meta.env;

createInertiaApp({
  title: title => `${title} - ${appName}`,
  resolve: name =>
    resolvePageComponent(
      `./Pages/${name}.tsx`,
      // @ts-expect-error
      import.meta.glob('./Pages/**/*.tsx')
    ),
  setup({ el, App, props }) {
    // @ts-expect-error
    if (import.meta.env.SSR) {
      hydrateRoot(el, <App {...props} />);
      return;
    }

    createRoot(el).render(
      <Provider store={store}>
        <PersistGate loading={<Loader />} persistor={persistor}>
          <ConfigProvider
            theme={{
              token: {
                colorPrimary: '#F97316'
                // colorSecondary: '#F97316'
              }
            }}
            locale={en_US}
          >
            <Refine
              // authProvider={authProvider}
              dataProvider={dataProvider(VITE_APP_URL + '/api', Api)}
              options={{ disableTelemetry: true }}
              routerProvider={routerProvider}
              notificationProvider={useNotificationProvider}
              resources={[
                { name: 'users' },
                { name: 'business', list: 'businesses' }
              ]}
            >
              <App {...props} />
            </Refine>
          </ConfigProvider>
        </PersistGate>
      </Provider>
    );
  },
  progress: {
    color: '#4B5563'
  }
});
