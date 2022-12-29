import 'nouislider/distribute/nouislider.css';
import '../css/app.css';
import '../css/custom.css';
import '../css/style.css';
import './bootstrap';

import { createRoot } from 'react-dom/client';
import { createInertiaApp } from '@inertiajs/inertia-react';
import { InertiaProgress } from '@inertiajs/progress';
import { Provider } from 'react-redux';
import store from './redux/store';
import SimpleReactLightbox from 'simple-react-lightbox';
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
        <SimpleReactLightbox>
          <App {...props} />
        </SimpleReactLightbox>
      </Provider>
    );
  }
});
