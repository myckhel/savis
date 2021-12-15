// import Router from "next/router";
import { Fragment, useEffect, useState } from "react";
// import { getUser } from "../redux/action/auth";
import Footer from "./Footer";
// import ChatBox from "./header/chatbox/ChatBox";
import Header from "./header/Header";
import NavHeader from "./header/NavHeader";
import PreLoader from "./PreLoader";
import Sidebar from "./Sidebar";

const Layout = ({ children }) => {
    const [height, setHeight] = useState();
    const [active, setActive] = useState(false);
    useEffect(() => {
        setHeight(window.screen.height - 100);
        setActive(document.querySelectorAll(".metismenu a") ? true : false);
    }, []);

    return (
        <Fragment>
            {!active ? (
                <PreLoader />
            ) : (
                <div id="main-wrapper" className="show">
                    <NavHeader />
                    {/* <ChatBox /> */}
                    <Header />
                    <Sidebar />
                    <div className="content-body" style={{ minHeight: height }}>
                        <div className="container-fluid">{children}</div>
                    </div>
                    <Footer />
                </div>
            )}
        </Fragment>
    );
};

export default Layout;
