import React, { PureComponent, Fragment } from "react";
import { Row, Card, CardBody, CardTitle, Button, Jumbotron } from "reactstrap";
import { Colxx, Separator } from "../../components/CustomBootstrap";
import BreadcrumbContainer from "../../components/BreadcrumbContainer";

export default class extends PureComponent {
  constructor(props){
    super(props)
    this.state = {
      customers: []
    }
  }

  componentDidMount = () => axios.get('/api/customer')
    .then((res) => res.data.data ? this.setState({customers: res.data.data}) : console.log('no data') )
    .catch((error) => console.log('error fetching', error))

  Customers = props => (
    <Colxx xxs="12">
      <p>{props.customer.firstname}</p>
      <p>{props.customer.lastname}</p>
      <p>{props.customer.email}</p>
      <p>{props.customer.phone}</p>
    </Colxx>
  )

  render() {
    let customers = []
    this.state.customers.map((c, i) => {
      customers.push(<this.Customers key={i} customer={c} />)
    })
    return (
      <Fragment>
        <Row>
          <Colxx xxs="12">
            <BreadcrumbContainer
              heading={'customers'}
              match={this.props.match}
            />
            <Separator className="mb-5" />
          </Colxx>
        </Row>
        <Row>
          {customers}
        </Row>
      </Fragment>
    );
  }
}
