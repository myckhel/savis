import '../css/app.css';
import '../sass/app.scss';
import './bootstrap';
import 'antd/dist/reset.css';

import { createRoot } from 'react-dom/client';
import { createInertiaApp } from '@inertiajs/inertia-react';
import { InertiaProgress } from '@inertiajs/progress';
import { Provider } from 'react-redux';
import store from './redux/store';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ConfigProvider } from 'antd';
import en_US from 'antd/locale/en_US';

InertiaProgress.init();
createInertiaApp({
  resolve: name =>
    resolvePageComponent(
      `./Pages/${name}.jsx`,
      import.meta.glob('./Pages/**/*.jsx')
    ),
  setup({ el, App, props }) {
    createRoot(el).render(
      <Provider store={store}>
        <ConfigProvider locale={en_US}>
          <App {...props} />
        </ConfigProvider>
      </Provider>
    );
  }
});
