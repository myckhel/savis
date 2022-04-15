import 'nouislider/distribute/nouislider.css'
import '../css/app.css'
import '../css/custom.css'
import '../css/style.css'
import './bootstrap'

import { render } from 'react-dom'
import { createInertiaApp } from '@inertiajs/inertia-react'
import { InertiaProgress } from '@inertiajs/progress'
import { Provider } from 'react-redux'
import store from './redux/store'
import SimpleReactLightbox from 'simple-react-lightbox'

InertiaProgress.init()
createInertiaApp({
  resolve: async (name) => {
    const pages = import.meta.glob('./pages/**/*.jsx')
    console.log({ pages })

    for (const path in pages) {
      if (path.endsWith(`${name.replace('.', '/')}.jsx`)) {
        return typeof pages[path] === 'function'
          ? (await pages[path]()).default
          : pages[path]
      }
    }

    throw new Error(`Page not found: ${name}`)
  },
  setup ({ el, App, props }) {
    render(
      <Provider store={store}>
        <SimpleReactLightbox>
          <App {...props} />
        </SimpleReactLightbox>
      </Provider>,
      el
    )
  }
})
