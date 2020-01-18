import React from 'react';
import { Redirect, Route, Switch } from 'react-router-dom';

import All from './all';
import View from './view';

export default ({ match }) => {
  return (
    <Switch>
        <Route exact path={`${match.url}`} component={All} />
        <Route exact path={`${match.url}/:id`} render={props => <View {...props} id={props.id} />} />
        <Redirect to="/error" />
    </Switch>
)};
// <Redirect exact from={`${match.url}/`} to={`${match.url}/start`} />
