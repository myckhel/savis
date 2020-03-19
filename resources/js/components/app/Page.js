import React, { PureComponent, Fragment } from "react";
import { Row, Button, Input, Label
} from "reactstrap";
import { Colxx, Separator } from "../CustomBootstrap";
import BreadcrumbContainer, { BreadcrumbItems } from "../BreadcrumbContainer";
import IntlMessages from "../../util/IntlMessages";
import { Redirect } from 'react-router-dom';
import classnames from 'classnames'
// export
export { Colxx, Separator }
export { Row as View, Button, Input, Label}
export { Table, Tr, Td, THead, TBody, TFoot, Title } from "../CustomBootstrap";


export const Text = ({children, style, className, lg, sm, }) => <p  className={classnames({
    // 'text-medium': md,
    'text-large': lg, 'text-small': sm,
  })+`${className ? className : ''}`} >{children}
</p>
export const IText = (props) => <IntlMessages id={props.id} />

class Page extends PureComponent {
  constructor(props) {
    super(props)

    this.state = {
      isLoading: false,
    }
  }

  function = () => {}

  Template = ({children, pageName, right}) => {
    const {isLoading} = this.state
    if (this.state.status === 404) {
      return <Redirect to={{
        pathname: '/error',
        state: { from: this.props.location }
      }} />
    }

    return (
      <Row>
        <Colxx xxs="12">
          <BreadcrumbContainer
            heading={pageName || 'Page'}
            match={this.props.match}
          />

          <Row className="float-sm-right">
            {right && right()}
          </Row>
        </Colxx>
        <Colxx xxs="12"><Separator className="mb-5" /></Colxx>
        <Colxx xxs="12">
        {isLoading && <div className="loading"></div>}
        {!isLoading && children}
        </Colxx>
      </Row>
    )
  }
}

export default
 // injectIntl(mouseTrap(
  Page//))
