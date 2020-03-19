import React, { Component, Fragment } from "react";
import IntlMessages from "../../util/IntlMessages";
import { Row, Card, CardTitle, Form, Label, Input, Button } from "reactstrap";
import { NavLink } from "react-router-dom";

import { Colxx } from "../../components/CustomBootstrap";

import { connect } from "react-redux";
import { registerUser } from "../../redux/actions";
import ReeValidate from 'ree-validate'

class RegisterLayout extends Component {
  constructor(props) {
    super(props);

    this.validator = new ReeValidate({
      name: "required|min:3|max:30",
      email: 'required|email',
      password: 'required|min:6',
      password_confirmation: "required|min:6",
      logo: '',
    })

    this.state = {
      credentials: {
        name: "",
        email: "",
        password: "",
        password_confirmation: "",
      },
      errors: props.errors
    };
  }
  onUserRegister() {
    if (this.state.credentials.email !== "" && this.state.credentials.password !== "") {
      const { errors } = this.validator
      const { credentials } = this.state

      this.validator.validateAll(credentials)
        .then((success) => {
          if (success) {
            this.props.registerUser(credentials, this.props.history);
          } else {
            this.setState({ errors })
          }
        })
    }
  }

  handleInputChange = ({ target }) => {
    const {name, value} = target
    const { errors } = this.validator

    this.setState(prev => { return {credentials: {...prev.credentials, [name]: value}} })

    errors.remove(name)
    this.validator.validate(name, value)
      .then(() => {
        this.setState({ errors })
      })
  }

  static getDerivedStatesFromProps = (nextProps) => {
  if(this.props.errors != nextProps.errors) {
    this.setState({
      errors: nextProps.errors
    });
  }
}

  componentDidMount() {
    document.body.classList.add("background");
  }
  componentWillUnmount() {
    document.body.classList.remove("background");
  }
  render() {
    return (
      <Fragment>
        <div className="fixed-background" />
        <main>
          <div className="container">
            <Row className="h-100">
              <Colxx xxs="12" md="10" className="mx-auto my-auto">
                <Card className="auth-card">
                  <div className="position-relative image-side ">
                    <p className="text-white h2">MAGIC IS IN THE DETAILS</p>
                    <p className="white">
                      Please use this form to register. <br />
                      If you are a member, please{" "}
                      <NavLink to={`/login`} className="white">
                        login
                      </NavLink>
                      .
                    </p>
                  </div>
                  <div className="form-side">
                    <NavLink to={`/`} className="white">
                      <span className="logo-single" />
                    </NavLink>
                    <CardTitle className="mb-4">
                      <IntlMessages id="user.register" />
                    </CardTitle>
                    <Form>
                    {this.state.errors.has('name') && <div className="invalid-feedback">{this.state.errors.first('name')}</div>}
                      <Label className="form-group has-float-label mb-4">
                        <Input
                          type="name" name="name" onChange={(e) => this.handleInputChange(e)}
                          defaultValue={this.state.credentials.name}
                        />
                        <IntlMessages id="user.name" />
                      </Label>
                      {this.state.errors.has('email') && <div className="invalid-feedback">{this.state.errors.first('email')}</div>}
                      <Label className="form-group has-float-label mb-4">
                        <Input
                          type="email" name="email" defaultValue={this.state.credentials.email}
                          onChange={(e) => this.handleInputChange(e)}
                        />
                        <IntlMessages id="user.email" />
                      </Label>
                      {this.state.errors.has('password') && <div className="invalid-feedback">{this.state.errors.first('password')}</div>}
                      <Label className="form-group has-float-label mb-4">
                        <Input type="password" name="password"
                           onChange={(e) => this.handleInputChange(e)}
                        />
                        <IntlMessages
                          id="user.password"
                          defaultValue={this.state.credentials.password}
                        />
                      </Label>
                      {this.state.errors.has('password_confirmation') && <div className="invalid-feedback">{this.state.errors.first('password_confirmation')}</div>}
                      <Label className="form-group has-float-label mb-4">
                        <Input type="password" name="password_confirmation"
                          defaultValue={this.state.credentials.password_confirmation}
                           onChange={(e) => this.handleInputChange(e)}
                        />
                        <IntlMessages
                          id="user.password_confirmation"
                        />
                      </Label>
                      <div className="d-flex justify-content-end align-items-center">
                        <Button
                          color="primary"
                          className="btn-shadow"
                          size="lg"
                          disabled={this.state.errors.any() || this.props.loading}
                          onClick={() => this.onUserRegister()}
                        >
                        {this.props.loading?
                          <div className="btn-loading"></div>
                       : <IntlMessages id="user.register-button" /> }
                        </Button>
                      </div>
                    </Form>
                  </div>
                </Card>
              </Colxx>
            </Row>
          </div>
        </main>
      </Fragment>
    );
  }
}
const mapStateToProps = ({ authUser }) => {
  const { user, loading, errors } = authUser;
  return { user, loading, errors };
};

export default connect(
  mapStateToProps,
  {
    registerUser
  }
)(RegisterLayout);
