import '../css/app.css';
import '../sass/app.scss';
import './bootstrap';

import { createRoot } from 'react-dom/client';
import { createInertiaApp } from '@inertiajs/inertia-react';
import { InertiaProgress } from '@inertiajs/progress';
import { Provider } from 'react-redux';
import store from './redux/store';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';

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
        <App {...props} />
      </Provider>
    );
  }
});
