import React from 'react';
import { Redirect, Route, Switch } from 'react-router-dom';

// import all from './all';
import View from './view';
import dataList from './data-list';

export default ({ match }) => {
  return (
    <Switch>
        <Route exact path={`${match.url}`} component={dataList} />
        <Route exact path={`${match.url}/:id`} render={props => <View {...props} params={props.match.params} />} />
        <Redirect to="/error" />
    </Switch>
)};
// <Redirect exact from={`${match.url}/`} to={`${match.url}/start`} />
