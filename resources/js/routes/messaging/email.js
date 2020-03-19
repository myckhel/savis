import React, { PureComponent, Fragment } from "react";
import { Row, Card, CardBody, CardTitle, Button, Jumbotron } from "reactstrap";
import { Colxx, Separator } from "../../components/CustomBootstrap";
import BreadcrumbContainer from "../../components/BreadcrumbContainer";

export default class extends PureComponent {
  constructor(props){
    super(props)
    this.state = {
      // id: props.id
    }
  }

  render = () => {
    return (
      <Fragment>
        <Row>
          <Colxx xxs="12">
            <BreadcrumbContainer
              heading={'Email'}
              match={this.props.match}
            />
            <Separator className="mb-5" />
          </Colxx>
        </Row>
        <Row>

        </Row>
      </Fragment>
    );
  }
}
