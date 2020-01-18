import React, { Fragment, useState, useEffect, PureComponent } from 'react';
import { Col, Row, Button } from "reactstrap";
import ServiceProp from '../ServiceProp';
import Select from "react-select/async";

export default class extends PureComponent {
  constructor(props) {
    super(props)

    this.state = {
      serviceTypes: []
    }
  }

  componentDidMount = () => {
    this.addServiceTypeRow()
  }

  addServiceTypeRow = () => {
    const { serviceTypes } = this.state
    let last = serviceTypes.length;
    const key = last > 0 ? parseInt(serviceTypes[last-1].key)+1 : 0
    const row = [...serviceTypes];
    row[last] = <ServiceProp
      remove={this.removeServiceTypeRow}
      key={key} index={key} />
    this.setState({serviceTypes: row})
  }

  removeServiceTypeRow = (index) => {
    const { serviceTypes } = this.state
    const types = serviceTypes.filter((v, i) => v.props.index !== index)
    this.setState({serviceTypes: types})
  }

  render = () => {
    const {services, selectService, selectedService, getServices} = this.props
    const { serviceTypes } = this.state
    const { addServiceTypeRow } = this

    return (
      <Fragment>
        <Row id="add-service-type-row">
          <Col sm="8" className="col-sm-offset-2">
            <Select
              cacheOptions
              defaultOptions={services}
              onChange={selectService}
              defaultValue={selectedService}
              name='service_id'
              loadOptions={getServices}
            />
          </Col>
        </Row>
        {serviceTypes}
        <Row id="add-service-type-row">
          <Col xs="8" className="col-xs-offset-2">
            <Button
              color="info" onClick={addServiceTypeRow}
            > <span aria-hidden > Add More Service Type</span>
            </Button>
          </Col>
        </Row>
      </Fragment>
    )
  }
}
//
// export const ll = ({services, selectService, selectedService, getServices}) => {
//   useEffect(() => {
//     addServiceTypeRow()
//   }, [])
//   var [serviceTypes, setServiceTypes] = useState([])
//
//   const removeServiceTypeRow = (key) => {
//     console.log(serviceTypes);
//     const types = serviceTypes.filter(
//       (v, i) => {
//         console.log(v, i, key);
//         return i !== key
//       }
//     )
//     // console.log(types);
//     // setServiceTypes(types)
//   }
//
//   const addServiceTypeRow = () => {
//       let last = serviceTypes.length;
//       const row = [...serviceTypes];
//       row[last] = <ServiceType
//         remove={removeServiceTypeRow}
//         key={last} index={last} />
//       setServiceTypes(row)
//   }
//
//   return (
//     <Fragment>
//       <Row id="add-service-type-row">
//         <Col sm="8" className="col-sm-offset-2">
//           <Select
//             cacheOptions
//             defaultOptions={services}
//             onChange={selectService}
//             defaultValue={selectedService}
//             name='service_id'
//             loadOptions={getServices}
//           />
//         </Col>
//       </Row>
//       {serviceTypes}
//       <Row id="add-service-type-row">
//         <Col xs="8" className="col-xs-offset-2">
//           <Button
//             color="info" onClick={addServiceTypeRow}
//           > <span aria-hidden > Add More Service Type</span>
//           </Button>
//         </Col>
//       </Row>
//     </Fragment>
//   )
// }
