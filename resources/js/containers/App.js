import React, { PureComponent, Fragment } from 'react';
import { connect } from 'react-redux';
import { Redirect, Route, Switch } from 'react-router-dom';
import { IntlProvider } from 'react-intl';
import { NotificationContainer } from "../components/ReactNotifications";
import { defaultStartPath } from '../constants/defaultValues'

import { store } from '../redux/store';
import { checkAuth } from '../redux/actions';

import AppLocale from '../lang';
import MainRoute from '../routes';
import login from '../routes/login'
import register from '../routes/register'
import error from '../routes/error'
import forgotPassword from '../routes/forgot-password'

import '../assets/css/vendor/bootstrap.min.css'
import 'react-perfect-scrollbar/dist/css/styles.css';
import '../assets/css/sass/themes/gogo.light.purple.scss';

const InitialPath = ({ component: Component,  authUser,...rest }) =>{
	return (<Route
		{...rest}
		render={props =>
			authUser
				? <Component {...props} />
				: <Redirect
					to={{
						pathname: '/login',
						state: { from: props.location }
					}}
				/>}
	/>);}

const UnAuthRoute = ({ component: Component,  authUser,...rest }) =>{
	return (<Route
		{...rest}
		render={props =>
			authUser
				? <Component {...props} />
				: <Redirect
					to={{
						pathname: '/',
						state: { from: props.location }
					}}
				/>}
	/>);}

class App extends PureComponent {
	constructor(props){
		super(props)

		props.checkAuth()
	}

	render() {
		const { location, match, authenticated, locale } = this.props;
		const currentAppLocale = AppLocale[locale];
		if (location.pathname === '/' && authenticated) {
			return (<Redirect to={defaultStartPath} />);
		}
		if ((location.pathname === '/login' || location.pathname === '/register'
		|| location.pathname === '/forgot-password') && authenticated) {
			return (<Redirect to='/' />);
		}

		return (
				<Fragment>
					<NotificationContainer />
					<IntlProvider
						locale={currentAppLocale.locale}
						messages={currentAppLocale.messages}
					>
					<Fragment>
						<Switch>
							<Route exact path={`/login`} component={login} />
							<Route exact path={`/register`} component={register} />
							<Route exact path={`/forgot-password`} component={forgotPassword} />
							<Route exact path={`/error`} component={error} />
							<InitialPath
								path={`${match.url}`}
								authUser={authenticated}
								component={MainRoute}
							/>
							<Redirect to="/error" />
						</Switch>
					</Fragment>
				</IntlProvider>
			</Fragment>
		);
	}
}

const mapStateToProps = ({ authUser, settings }) => {
	const { authenticated } = authUser;
	const { locale } = settings;
	return { authenticated, locale };
};

export default connect(mapStateToProps,{ checkAuth })(App);
// || location.pathname === '/app' || location.pathname === '/app/'
