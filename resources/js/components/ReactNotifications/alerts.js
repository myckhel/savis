import React, { Component, Fragment } from "react";
import { Alert } from "reactstrap";

import {  NotificationManager} from "./";

class AlertsUi extends Component {
  constructor(props) {
    super(props);
    this.state = {
      visible: true
    };
    this.onDismiss = this.onDismiss.bind(this);
  }
  createNotification = (type, className) => {
    let cName = className || "";
    return () => {
      switch (type) {
        case "primary":
          NotificationManager.primary(
            "This is a notification!",
            "Primary Notification",
            3000,
            null,
            null,
            cName
          );
          break;
        case "secondary":
          NotificationManager.secondary(
            "This is a notification!",
            "Secondary Notification",
            3000,
            null,
            null,
            cName
          );
          break;
        case "info":
          NotificationManager.info("Info message", "", 3000, null, null, cName);
          break;
        case "success":
          NotificationManager.success(
            "Success message",
            "Title here",
            3000,
            null,
            null,
            cName
          );
          break;
        case "warning":
          NotificationManager.warning(
            "Warning message",
            "Close after 3000ms",
            3000,
            null,
            null,
            cName
          );
          break;
        case "error":
          NotificationManager.error(
            "Error message",
            "Click me!",
            5000,
            () => {
              alert("callback");
            },
            null,
            cName
          );
          break;
        default:
          NotificationManager.info("Info message");
          break;
      }
    };
  };

  onDismiss() {
    this.setState({ visible: false });
  }
  render() {
    return (
      <Fragment>
        <Row>
          <Colxx xxs="12">
            <Card className="mb-4">
              <CardBody>
                <CardSubtitle>
                  <IntlMessages id="alert.filled" />
                </CardSubtitle>
                <Button
                  className="mb-3"
                  color="primary"
                  onClick={this.createNotification("primary", "filled")}
                >
                  <IntlMessages id="alert.primary" />
                </Button>{" "}
                <Button
                  className="mb-3"
                  color="secondary"
                  onClick={this.createNotification("secondary", "filled")}
                >
                  <IntlMessages id="alert.secondary" />
                </Button>{" "}
                <Button
                  className="mb-3"
                  color="info"
                  onClick={this.createNotification("info", "filled")}
                >
                  <IntlMessages id="alert.info" />
                </Button>{" "}
                <Button
                  className="mb-3"
                  color="success"
                  onClick={this.createNotification("success", "filled")}
                >
                  <IntlMessages id="alert.success" />
                </Button>{" "}
                <Button
                  className="mb-3"
                  color="warning"
                  onClick={this.createNotification("warning", "filled")}
                >
                  <IntlMessages id="alert.warning" />
                </Button>{" "}
                <Button
                  className="mb-3"
                  color="danger"
                  onClick={this.createNotification("error", "filled")}
                >
                  <IntlMessages id="alert.error" />
                </Button>

              </CardBody>
            </Card>
          </Colxx>
        </Row>
      </Fragment>
    );
  }
}

export default function createNotification (type, status) {
  let cName = "filled";
  return () => {
    switch (type) {
      case "primary":
        NotificationManager.primary(
          status,
          "Normal",
          3000,
          null,
          null,
          cName
        );
        break;
      case "secondary":
        NotificationManager.secondary(
          status,
          "Secondary",
          3000,
          null,
          null,
          cName
        );
        break;
      case "info":
        NotificationManager.info("Info message", "Info", 3000, null, null, cName);
        break;
      case "success":
        NotificationManager.success(
          status,
          "Success!",
          3000,
          null,
          null,
          cName
        );
        break;
      case "warning":
        NotificationManager.warning(
          status,
          "Warning",
          3000,
          null,
          null,
          cName
        );
        break;
      case "error":
        NotificationManager.error(
          status,
          "Error!",
          5000,
          () => {
            alert("callback");
          },
          null,
          cName
        );
        break;
      default:
        NotificationManager.info("Info message");
        break;
    }
  };
};
