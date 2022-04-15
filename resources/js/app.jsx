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
// import SimpleReactLightbox from 'simple-react-lightbox'

InertiaProgress.init()
createInertiaApp({
  resolve: async (name) => {
    const pages = import.meta.glob('./pages/**/*.jsx')
    const page = Object.keys(pages).find((page) =>
      page.endsWith(`${name}.jsx`)
    )

    return (await pages[page]()).default
  },
  setup ({ el, App, props }) {
    render(
      <Provider store={store}>
      {/* //     <SimpleReactLightbox> */}
               <App {...props} />
      {/* //     </SimpleReactLightbox> */}
      </Provider>,
      el
    )
  }
})
