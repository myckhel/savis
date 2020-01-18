import React, { Component, Fragment } from "react";
import IntlMessages from "../../util/IntlMessages";
import { Row, Card, CardTitle, Form, Label, Input, Button } from "reactstrap";
import { NavLink } from "react-router-dom";
import ReeValidate from 'ree-validate'

import { Colxx } from "../../components/CustomBootstrap";

import { connect } from "react-redux";
import { loginUser } from "../../redux/actions";

class LoginLayout extends Component {
  constructor(props) {
    super(props);

    this.validator = new ReeValidate({
      email: 'required|email',
      password: 'required|min:6',
      remember_me: 'required',
    })

    this.state = {
      credentials: {
        email: "",
        password: "",
        remember_me: false,
      },
      errors: props.errors
    };
  }

  handleInputChange = ({target}) => {
    const name = target.name
    const value = target.value
    const { errors } = this.validator
    this.setState(prev => { return {credentials: {...prev.credentials, [name]: value}} })

    errors.remove(name)
    this.validator.validate(name, value)
      .then(() => {
        this.setState({ errors })
      })
  }

  onUserLogin() {
    if (this.state.email !== "" && this.state.password !== "") {
      const { credentials } = this.state
      const { errors } = this.validator

      this.validator.validateAll(credentials)
      .then((success) => {
        if (success) {
          this.props.loginUser(credentials, this.props.history);
        } else {
          this.setState({ errors })
        }
      })
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
                      Please use your credentials to login.
                      <br />
                      If you are not a member, please{" "}
                      <NavLink to={`/register`} className="white">
                        register
                      </NavLink>
                    </p>
                  </div>
                  <div className="form-side">
                    <NavLink to={`/`} className="white">
                      <span className="logo-single" />
                    </NavLink>
                     <Fragment>
                      <CardTitle className="mb-4">
                        <IntlMessages id="user.login-title" />
                      </CardTitle>
                      <CardTitle className="mb-4">
                        {this.props.errors.has('invalid') && <div className="invalid-feedback">{this.props.errors.first('invalid')}</div>}
                      </CardTitle>
                      <Form>
                      {this.state.errors.has('email') && <div className="invalid-feedback">{this.state.errors.first('email')}</div>}
                        <Label className="form-group has-float-label mb-4">
                          <Input type="email" name="email" defaultValue={this.state.credentials.email}
                            onChange={(e) => this.handleInputChange(e)}
                          />
                          <IntlMessages id="user.email" />
                        </Label>
                        {this.state.errors.has('password') && <div className="invalid-feedback">{this.state.errors.first('password')}</div>}
                        <Label className="form-group has-float-label mb-4">
                          <Input type="password" onChange={(e) => this.handleInputChange(e)}
                            name='password'
                          />
                          <IntlMessages
                            id="user.password"
                            defaultValue={this.state.credentials.password}
                          />
                        </Label>
                        <div className="d-flex justify-content-between align-items-center">
                          <NavLink to={`/forgot-password`}>
                            <IntlMessages id="user.forgot-password-question" />
                          </NavLink>
                          <Button
                            color="primary"
                            className="btn-shadow"
                            size="lg"
                            disabled={this.state.errors.any() || this.props.loading}
                            onClick={() => this.onUserLogin()}
                          >
                          {this.props.loading?
                            <div className="btn-loading"></div>
                         :
                            <IntlMessages id="user.login-button" />}
                          </Button>
                        </div>
                      </Form>
                    </Fragment>
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
    loginUser
  }
)(LoginLayout);


// <Label className="remember-label form-check-label form-group has-float-label mb-4">
//   <Input type="checkbox"
//     onChange={(e) => this.handleInputChange(e)}
//     name='remember_me'
//   />
//   <div className="remember-label">
//     <IntlMessages
//       id="user.remember"
//     />
//   </div>
// </Label>
