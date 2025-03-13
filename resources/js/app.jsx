// import '../css/app.css';
import '../sass/app.scss';
import './bootstrap';
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

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
  title: title => `${title} - ${appName}`,
  resolve: name =>
    resolvePageComponent(
      `./Pages/${name}.jsx`,
      import.meta.glob('./Pages/**/*.jsx')
    ),
  setup({ el, App, props }) {
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
            <App {...props} />
          </ConfigProvider>
        </PersistGate>
      </Provider>
    );
  },
  progress: {
    color: '#4B5563'
  }
});
