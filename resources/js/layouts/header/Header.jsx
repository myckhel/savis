import { Link } from "@inertiajs/inertia-react";
import { useEffect, useState } from "react";
import { Dropdown } from "react-bootstrap";
import PerfectScrollbar from "react-perfect-scrollbar";
import { connect } from "react-redux";
import { getHeaderData } from "../../redux/action/utils";
// import ChatBoxicon from "./chatbox/ChatBoxicon";
// import Notification from "./Notification";
// import Notification2 from "./Notification2";
// import Profile from "./Profile";

const Header = ({ title, getHeaderData, searchData }) => {
    const [searchText, setSearchText] = useState("");
    useEffect(() => {
        getHeaderData();
    }, []);
    return (
        <div className="header">
            <div className="header-content">
                <nav className="navbar navbar-expand">
                    <div className="collapse navbar-collapse justify-content-between">
                        {title && (
                            <div className="header-left">
                                <div className="dashboard_bar">{title}</div>
                            </div>
                        )}

                        <ul className="navbar-nav header-right">
                            <Dropdown
                                as="li"
                                className="nav-item position-relative"
                            >
                                <Dropdown.Toggle
                                    variant=""
                                    as="a"
                                    className="i-false p-0 input-group search-area d-xl-inline-flex d-none"
                                >
                                    <input
                                        type="text"
                                        className="form-control"
                                        placeholder="Search here..."
                                        onChange={(e) =>
                                            setSearchText(e.target.value)
                                        }
                                    />
                                    <div className="input-group-append">
                                        <button className="input-group-text">
                                            <i className="flaticon-381-search-2" />
                                        </button>
                                    </div>
                                </Dropdown.Toggle>
                                <Dropdown.Menu
                                    alignRight={true}
                                    className="mt-3"
                                >
                                    <PerfectScrollbar
                                        id="DZ_W_Notification1"
                                        className="widget-media dz-scroll p-2 "
                                        style={{ maxHeight: 280 }}
                                    >
                                        <ul className="search-result-bar timeline">
                                            {searchData &&
                                            searchData.filter(
                                                (page) =>
                                                    page.name
                                                        .toLowerCase()
                                                        .includes(searchText) &&
                                                    page
                                            ).length == 0
                                                ? "No search data found"
                                                : searchData &&
                                                  searchData
                                                      .filter(
                                                          (page) =>
                                                              page.name
                                                                  .toLowerCase()
                                                                  .includes(
                                                                      searchText
                                                                  ) && page
                                                      )
                                                      .map((page, i) => (
                                                          <li
                                                              className="pb-2"
                                                              key={i}
                                                          >
                                                              <Link
                                                                  href={
                                                                      page.pathName
                                                                  }
                                                              >
                                                                  <a
                                                                      style={{
                                                                          textTransform:
                                                                              "capitalize",
                                                                      }}
                                                                  >
                                                                      {
                                                                          page.name
                                                                      }
                                                                  </a>
                                                              </Link>
                                                          </li>
                                                      ))}
                                        </ul>
                                    </PerfectScrollbar>
                                </Dropdown.Menu>
                            </Dropdown>
                            {/* <Notification />
              <ChatBoxicon />
              <Notification2 />
              <Profile /> */}
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
    );
};

const mapSateToProps = (state) => ({
    title: state.utils.pageTitle,
    searchData: state.utils.searchData,
});

export default connect(mapSateToProps, { getHeaderData })(Header);
