import React from 'react';
import { Redirect, Route, Switch } from 'react-router-dom';

import dashboard from './dashboard';

const Dashboards = ({ match }) => (
    <div className="dashboard-wrapper">
        <Switch>
            <Route exact path={`${match.url}`} component={dashboard} />
            <Redirect to="/error" />

        </Switch>
    </div>
);
export default Dashboards;
