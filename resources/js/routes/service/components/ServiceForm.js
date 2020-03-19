import React, {Fragment, Component} from 'react'
import {
  Row, Button, ModalHeader, ModalBody,
  ModalFooter, Input, Label, Form, Col
} from "reactstrap";
import Select from "react-select/async";

import ReeValidate from 'ree-validate'
import { removeErrors, addErrors } from '../../../helpers/errors'
import formToObj from '../../../helpers/formToObj'
import { getCountriesCode, selectable } from '../../../helpers/data'
import { addServices } from '../../../helpers/ajax/service'
import {toast} from 'react-toastify'
import Http from '../../../util/Http'

import Service from '../Service';
import ServiceProp from '../ServiceProp';
import ServiceProps from './ServiceProps';

export default class extends Component {
  constructor(props) {
    super(props)

    this.validator = new ReeValidate({
      name: 'required|min:3|max:45',
      parent: 'numeric',
      charge: '',
      price: 'numeric',
      logo: '',
    })

    this.state = {
      errors: this.validator.errors,
      loading: false,
      isLoading: false,
      credentials: {
        name: ''
      },
      form: {
        name: '',
        submitName: 'Save',
        cancelName: 'Cancel',
        state: props.formState
      },
    }
  }

  submitForm = (event) => {
    const form = $(event.target)
    event.persist()
    event.preventDefault();

    const { errors } = this.validator
    const formData = formToObj($(form).serializeArray());
    const slug = this.state.form.state === 'type' ? '/api/service-metas' : '/api/services';
    const callback = this.state.form.state === 'type' ? false : this.toggleFormState;

    console.log(formData);
    // return
    this.setState(prev => ({
      errors: removeErrors(prev.errors)
    }))

    this.validator.validateAll(formData)
    .then((success) => {
      if (success) {
        this.setState({isLoading: true}, () => {
          Http.post(slug, formData)//, { headers: {'content-Type': `multipart/form-data; application/x-www-form-urlencoded; charset=UTF-8` } })//{url: slug, data: formData, config: { headers: {'content-Type': 'multipart/form-data'}}})
          .then((res) => res.data )
          .then( async (res) => {
            if (res.status) {
              // $(form).trigger('reset')

              if (this.state.form.state === 'service') {
                const service = {label: res.service.name, value: res.service.id};
                await this.setState( (prev) => ({services: [ service ] }), () => this.selectService(service) )
              } else {
                this.toggleFormState()
              }

              if (callback) {
                callback()
              }
              swal('Success', res.errors, 'success')
            } else {
              swal('Ooooops!', res.errors || res.text, 'error')
            }
            this.props.dataListRender()
          })
          .catch((err) => {
            this.props.dataListRender()
            swal('Ooooops!', 'Internal Server Error', 'error')
          })
          .finally(() => {
            // this.setState({isLoading: false})
          })
        })
      } else {
        this.setState({ errors })
      }
    })
  }

  handleInputChange = ({ target }) => {
    const name = target.name
    const value = target.value
    const { errors } = this.validator

    errors.remove(name)
    this.validator.validate(name, value)
      .then(() => {
        this.setState({ errors })
      })
      this.setState(prev => ({credentials: {...prev.credentials, [name]: value}}) )
  }

  selectService = (service) => {
    this.props.selectService(service)
  }

  toggleFormState = () => {
    this.props.toggleFormState()
  }

  getServices = (input, callback) => this.handleKeyUp(input)

  handleKeyUp = (input) => {
    return new Promise((resolve, reject) => {
      Http.get(`/api/services?pagenate=${false}&search=${input}`)
      .then((res) => resolve(selectable(res.data.data, ['name', 'id'])) )
      .catch((err) => reject(err))
    });
  }

  render = () => {
    const { selectService, toggleModal, services, selectedService } = this.props
    const { state } = this.state.form
    const { credentials, errors } = this.state
    const serviceType = this.state.serviceTypeRow;
    const { getServices } = this

    return (
      <Form id={'service-form'} onSubmit={this.submitForm}>
        <ModalHeader toggle={toggleModal}>
          Add New Service +
        </ModalHeader>
        <ModalBody>
          {state === 'service'
          ? <Service
              services={services}
              selectService={selectService}
              selectedService={selectedService}
              getServices={getServices}
              credentials={credentials}
              handleInputChange={this.handleInputChange}
              errors={errors}
            />
          : <ServiceProps services={services}
              selectService={selectService}
              selectedService={selectedService}
              getServices={getServices} />
          }
        </ModalBody>
        <ModalFooter>
          <Button
            color="secondary"
            outline
            onClick={toggleModal}
          >
          {this.state.form.cancelName}
          </Button>
          <Button
          disabled={this.state.errors.any() || this.state.isLoading}
          type="submit" id="btn" color="primary" >
          {this.state.isLoading?
            <div className="btn-loading"></div>
         : this.state.form.submitName}
          </Button>{" "}
        </ModalFooter>
      </Form>
    )
  }
}

/*<Fragment>
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
  {serviceType}
  <Row id="add-service-type-row">
    <Col xs="8" className="col-xs-offset-2">
      <Button
        color="info" onClick={this.addServiceTypeRow}
      > <span aria-hidden > Add More Service Type</span>
      </Button>
    </Col>
  </Row>
</Fragment>*/
