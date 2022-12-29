// import Router from "next/router";
import { Fragment } from 'react';
// import { getUser } from "../redux/action/auth";

const Layout = ({ children }) => {
  return (
    <Fragment>
      <div id="main-wrapper" className="show">
        {/* <NavHeader /> */}
        {/* <ChatBox /> */}
        {/* <Header /> */}
        {/* <Sidebar /> */}
        <div className="content-body" style={{}}>
          <div className="container-fluid">{children}</div>
        </div>
        {/* <Footer /> */}
      </div>
    </Fragment>
  );
};

export default Layout;
