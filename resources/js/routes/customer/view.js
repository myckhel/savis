import React, { PureComponent, Fragment } from "react";
import { Row, Col, Media } from "reactstrap";
import { customerProfile } from '../../helpers/ajax/customer'
import ViewAble from '../../components/app/ViewAble'
import { Text, IText } from '../../components/app/Page'
import PHistory from '../../components/customer/PHistory'
import JHistory from '../../components/customer/JHistory'
import Properties from '../../components/customer/Property'

export default class extends ViewAble {
  constructor(props){
    super(props)
    this.state = {
      params: props.params,
      isLoading: true,
      profile: {},
      status: null,
    }
  }

  componentDidMount = () => {
    this.initAsync()
  }

  name="profile"
	viewAsync = (id) => customerProfile(id)

  render = () => {
    const { profile } = this.state.profile
    const { id } = this.props.match.params
    const { firstname, lastname, jobs_failed, jobs_pending, jobs_completed, jobs_on_hold, credentialServices } = profile || {}

    return (
      <this.Template pageName={firstname && firstname+' '+lastname || 'Customer'}
        right={this.Right}>
        <Col sm={12} className="bg-primary d-flex align-items-center justify-content-center">
          <Col md={2} className="d-flex flex-column justify-content-around">
            <Media style={styles.img} object src="/assets/img/default-service.png" />
            <Text sm className="" style={styles.head}>{`${firstname} ${lastname}`}</Text>
          </Col>
          <Col md={10}>
            <Row>
              <this.Status bg="danger" hd head="Jobs Failed" />
              <this.Status bg="warning" hd head="Jobs On Hold" />
              <this.Status bg="info" hd head="Jobs Pending" />
              <this.Status bg="success" hd head="Jobs Completed" />
            </Row>
            <Row>
              <this.Status bg="danger" status={jobs_failed} />
              <this.Status bg="warning" status={jobs_on_hold} />
              <this.Status bg="info" status={jobs_pending} />
              <this.Status bg="success" status={jobs_completed} />
            </Row>
          </Col>
        </Col>

        <Col style={styles.body}>
          <Properties id={id} title="Properties"
            Table={this.Table} TableActions={this.TableFilter} />
          {/*<this.Table title="Properties" data={credentialServices} config={{
            key: 'id', fields: ['name', 'rule', (meta) => <this.TableActions data={meta} />],
            heads: ['Name', 'Rules', 'Actions']
          }} />*/}

          <PHistory Table={this.Table} TableActions={this.TableFilter}
          id={id} title="Payment History" />

          <JHistory Table={this.Table} TableActions={this.TableFilter}
          id={id} title="Jobs History" />

          <Col xxs="12">
            <Row>
              <Text size={2}>Credential Services</Text>
              {credentialServices && credentialServices.map((cred, i) => (
              <Row key={i}>
                <Text>Airtime: </Text>
                {cred.Airtime.map((serv, name) => (
                  <Row key={name}>
                    <Text>{name}</Text>
                    <Text>{serv.Phone}</Text>
                  </Row>
                ))}
              </Row>
              ))}
            </Row>
          </Col>
        </Col>
      </this.Template>
    );
  }
}

const styles = {
  img: {
    width: '200px',
    height: '200px',
    backgroundColor: 'grey',
    backgroundSize: 'contain',
    borderRadius: '5px',
    border: '5px gold',
  },
  head: {
    justifyContent: 'center',
    alignItems: 'center',
  },
  body: {
    justifyContent: 'center',
    alignItems: 'center',
    minHeight: '500px',
    backgroundColor: 'rgba(251,65,02,0.5)'
  }
}
