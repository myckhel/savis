import React, {Component} from 'react'
import {
  Row, Button, ModalHeader, ModalBody,
  ModalFooter, Input, Label, Form, Col
} from "reactstrap";
import Select from "react-select";

import ReeValidate from 'ree-validate'
import { removeErrors, addErrors } from '../../../helpers/errors'
import formToObj from '../../../helpers/formToObj'
import { getCountriesCode, selectable } from '../../../helpers/data'
import { addCustomers } from '../../../helpers/ajax/customer'
import {toast} from 'react-toastify'
import csc from 'country-state-city'


export default class extends Component {
  constructor(props) {
    super(props)

    this.validator = new ReeValidate({
      firstname: 'required|min:3|max:35',
      lastname: 'required|min:3|max:35',
      country_code: '',
      phone: 'numeric|min:10|max:15',
      email: 'email',
      city: 'min:3|max:45',
      state: 'min:3|max:45',
      address: 'min:3|max:105',
      country: 'min:3|max:45',
    });

    this.state = {
      isLoading: false,
      selected: {
        country_code: {label: 'NG +234', value: '+234'},
      },
      errors: this.validator.errors,
      options: {
        countries: selectable(csc.getAllCountries(), ['name', 'name']),
      },
      country_codes: selectable(getCountriesCode(), [], 'country_code'),
      form: {
        firstname: '',
        lastname: '',
        country_code: '',
        phone: '',
        email: '',
        city: '',
        state: '',
        address: '',
        country: '',
      },
    }
  }

  // input change
  handleInputChange = ({ target }) => {
    const name = target.name
    const value = target.value
    const { errors } = this.validator

    this.setState(prev => ({form: {...prev.form, [name]: value} }))

    errors.remove(name)
    this.validator.validate(name, value)
      .then(() => {
        this.setState({ errors })
      })
  }

  handleSelectChange = async ({label, value, id}, field) => {
    const { errors } = this.validator

    await this.setState(prev => ({form: {...prev.form, [field]: value},
        selected: {...prev.selected, [field]: {label, value, id}}
    }))

    const empty = {label: '', value: ''}

    if (field === 'country') {
      const states = selectable(csc.getStatesOfCountry(this.state.selected.country.id), ['name', 'name'])
      await this.setState(prev => ({
        selected: {...prev.selected, state: empty, city: empty},
        options: {...prev.options, states}
      }))
    }

    if (field === 'state') {
      const cities = selectable(csc.getCitiesOfState(this.state.selected.state.id), ['name', 'name'])
      await this.setState(prev => ({
        selected: {...prev.selected, city: empty},
        options: {...prev.options, cities}
      }))
    }

    errors.remove(field)
    await this.validator.validate(field, value)
      .then(() => {
        this.setState({ errors })
      })
  }

  // form submit
  submitForm = (event) => {
    const formNode = $(event.target)
    const form = formToObj($(event.target).serializeArray())
    event.persist()
    event.preventDefault();

    const { errors } = this.validator
    this.setState(prev => ({
      errors: removeErrors(prev.errors),
      isLoading: true,
    }))

    this.validator.validateAll(form)
    .then( async (success) => {
      if (success) {
        if (typeof this.props.beforeSubmit === 'function') {
          await this.props.beforeSubmit()
        }
        try {
          const res = await addCustomers(form)
          if (res.status) {
            $(formNode).trigger('reset')
            this.setState({form: {}})
            swal('Success', `${res.customer.firstname} As ${res.message}`, 'success')
            // createNotification('success', 'True')
          } else {
            // alert warning
            swal('Ooops', res.message, 'error')
          }
        } catch (err) {
          if (err.response.status === 422) {
            toast.warn(err.response.data.message)
            await this.setState(prev => addErrors(this.state.errors, err.response.data.errors))
          } else {
            swal('Ooops', 'Internal Server Error', 'error')
          }
        } finally {
          if (typeof this.props.afterSubmit === 'function') {
            await this.props.afterSubmit()
          }
          this.setState({isLoading: false})
        }
      } else {
        this.setState({ errors })
      }
    })
  }

  // componentWillReceiveProps = (prev, next) => {
  //   console.log(prev, next);
  //   if (prev !== next) {
  //     this.setState({...next}, () => console.log(this.state))
  //   }
  // }

  render(){
    const errors = this.state.errors;
    console.log(errors);
    return (
      <Form id={'customer-form'} onSubmit={(e) => {this.submitForm(e)}}>
        <ModalBody>
          <Row>
            <Col sm="6" className="col-sm-offset-2">
              <Label> Firstname </Label>
              {errors.has('firstname') && <div className="invalid-feedback">{errors.first('firstname')}</div>}
              <Input
                className={errors.has('firstname') ? 'error-input' : ''}
                onChange={this.handleInputChange}
                value={this.state.form.firstname}
                type="text" required name="firstname"
                id="firstname" placeholder="Firstname"
              />
            </Col>
            <Col sm="6" className="col-sm-offset-2">
              <Label> Lastname </Label>
              {errors.has('lastname') && <div className="invalid-feedback">{errors.first('lastname')}</div>}
              <Input
              className={errors.has('lastname') ? 'error-input' : ''}
                onChange={this.handleInputChange}
                value={this.state.form.lastname}
                type="lastname" name="lastname"
                id="lastname" placeholder="lastname"
              />
            </Col>
          </Row>
          <Label>Email</Label>
          {errors.has('email') && <div className="invalid-feedback">{errors.first('email')}</div>}
          <Input
            className={errors.has('email') ? 'error-input' : ''}
            onChange={this.handleInputChange}
            value={this.state.form.email}
            type="email" name="email" id="email"
            placeholder="Email"
          />
          {/*<Label> Country Code </Label>
          {errors.has('country_code') && <div className="invalid-feedback">{errors.first('country_code')}</div>}
          <Input
            className={errors.has('country_code') ? 'error-input' : ''}
            onChange={this.handleInputChange}
            value={this.state.form.country_code}
            type="country_code" name="country_code"
            id="country_code" placeholder="Country Code"
          />*/}
            <Row>
              <Col sm="5" className="col-sm-offset-2">
                <Label> Country Code </Label>
                <Select
                  value={this.state.selected.country_code} name='country_code'
                  onChange={(e) => this.handleSelectChange(e, 'country_code')}
                  options={this.state.country_codes}
                ></Select>
              </Col>
              <Col sm="7" className="col-sm-offset-2">
                <Label> Phone </Label>
                {errors.has('phone') && <div className="invalid-feedback">{errors.first('phone')}</div>}
                <Input
                  className={errors.has('phone') ? 'error-input' : ''}
                  onChange={this.handleInputChange}
                  value={this.state.form.phone}
                  type="text" name="phone"
                  id="phone" placeholder="phone"
                />
              </Col>
            </Row>
            <Row>
              <Col sm="12" className="col-sm-offset-2">
                <Label> Country </Label>
                {errors.has('country') && <div className="invalid-feedback">{errors.first('country')}</div>}
                <Select
                  options={this.state.options.countries}
                  className={errors.has('country') ? 'error-input' : ''}
                  onChange={(e) => this.handleSelectChange(e, 'country')}
                  value={this.state.selected.country}
                  type="text" name="country"
                  id="country" placeholder="country"
                />
              </Col>
            </Row>
            <Row>
              <Col sm="6" className="col-sm-offset-2">
                <Label> State </Label>
                {/*<Select
                  value={this.state.selected.country_code} name='country_code'
                  onChange={(e) => this.handleSelectChange(e, 'country_code')}
                  options={this.state.country_codes}
                />*/}
                <Select
                  options={this.state.options.states}
                  className={errors.has('state') ? 'error-input' : ''}
                  onChange={(e) => this.handleSelectChange(e, 'state')}
                  value={this.state.selected.state}
                  type="text" name="state"
                  id="state" placeholder="state"
                />
              </Col>
              <Col sm="6" className="col-sm-offset-2">
                <Label> City </Label>
                {errors.has('city') && <div className="invalid-feedback">{errors.first('city')}</div>}
                <Select
                  options={this.state.options.cities}
                  className={errors.has('city') ? 'error-input' : ''}
                  onChange={(e) => this.handleSelectChange(e, 'city')}
                  value={this.state.selected.city}
                  type="text" name="city"
                  id="city" placeholder="city"
                />
              </Col>
            </Row>
          <Row>
            <Col sm="12" className="col-sm-offset-2">
              <Label> Address </Label>
              {errors.has('address') && <div className="invalid-feedback">{errors.first('address')}</div>}
              <Input
                className={errors.has('address') ? 'error-input' : ''}
                onChange={this.handleInputChange}
                value={this.state.form.address}
                type="text" name="address"
                id="address" placeholder="address"
              />
            </Col>
          </Row>
        </ModalBody>
        <ModalFooter>
          <Button
            color="secondary"
            outline
            onClick={this.props.toggleModal}
          >
            cancel
          </Button>
          <Button type="submit"
            disabled={this.state.errors.any() || this.state.isLoading}
            id="btn" color="primary" >
            {this.state.isLoading?
              <div className="btn-loading"></div>
           : 'Submit'}
          </Button>{" "}
        </ModalFooter>
      </Form>
    )
  }
}
