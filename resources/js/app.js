require('./bootstrap');
import React from 'react';
import ReactDOM from 'react-dom';

import { Provider } from 'react-redux';
import { BrowserRouter as Router, Route } from 'react-router-dom';

import App from './containers/App';

import { store } from './redux/store';
import {toast} from 'react-toastify'
import 'react-toastify/dist/ReactToastify.css'

toast.configure({
	autoClose: 8000,
	draggable: false,
})

const MainApp = () => (
	<Provider store={store}>
		<Router>
			<Route path="/" component={App} />
		</Router>
	</Provider>
);

export default  ReactDOM.render(
	<MainApp />,
	document.getElementById("root")
);
