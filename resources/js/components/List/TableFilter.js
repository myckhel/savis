import React, { PureComponent } from 'react'
import { Button, Collapse, UncontrolledDropdown, DropdownToggle, DropdownMenu, DropdownItem} from "reactstrap";

function collect(props) {
  return { data: props.data };
}

export default class extends PureComponent {
  constructor(props) {
    super(props)

  }

  render = () => {
    const { toggleDisplayOptions, displayOptionsIsOpen, messages,
      changeDisplayMode, displayMode, selectedOrderOption, orderOptions, changeOrderBy, search, handleSearchChange, handleKeyPress,
      startIndex, endIndex, totalItemCount, selectedPageSize, pageSizes, changePageSize } = this.props
    return (
      <div className="mb-2">
        <Button
          color="empty"
          className="pt-0 pl-0 d-inline-block d-md-none"
          onClick={toggleDisplayOptions}
        >
          Display Options{" "}
          <i className="simple-icon-arrow-down align-middle" />
        </Button>
        <Collapse
          isOpen={displayOptionsIsOpen}
          className="d-md-block"
          id="displayOptions"
        >
         <span className="mr-3 mb-2 d-inline-block float-md-left">
          <a
            className={`mr-2 view-icon ${
              displayMode === "list" ? "active" : ""
              }`}
            onClick={() => changeDisplayMode("list")}
          >
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 19 19">
          <path className="view-icon-svg" d="M17.5,3H.5a.5.5,0,0,1,0-1h17a.5.5,0,0,1,0,1Z" />
          <path className="view-icon-svg" d="M17.5,10H.5a.5.5,0,0,1,0-1h17a.5.5,0,0,1,0,1Z" />
          <path className="view-icon-svg" d="M17.5,17H.5a.5.5,0,0,1,0-1h17a.5.5,0,0,1,0,1Z" /></svg>
          </a>
          <a
            className={`mr-2 view-icon ${
              displayMode === "thumblist" ? "active" : ""
              }`}
            onClick={() => changeDisplayMode("thumblist")}
          >
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 19 19">
          <path className="view-icon-svg" d="M17.5,3H6.5a.5.5,0,0,1,0-1h11a.5.5,0,0,1,0,1Z" />
          <path className="view-icon-svg" d="M3,2V3H1V2H3m.12-1H.88A.87.87,0,0,0,0,1.88V3.12A.87.87,0,0,0,.88,4H3.12A.87.87,0,0,0,4,3.12V1.88A.87.87,0,0,0,3.12,1Z" />
          <path className="view-icon-svg" d="M3,9v1H1V9H3m.12-1H.88A.87.87,0,0,0,0,8.88v1.24A.87.87,0,0,0,.88,11H3.12A.87.87,0,0,0,4,10.12V8.88A.87.87,0,0,0,3.12,8Z" />
          <path className="view-icon-svg" d="M3,16v1H1V16H3m.12-1H.88a.87.87,0,0,0-.88.88v1.24A.87.87,0,0,0,.88,18H3.12A.87.87,0,0,0,4,17.12V15.88A.87.87,0,0,0,3.12,15Z" />
          <path className="view-icon-svg" d="M17.5,10H6.5a.5.5,0,0,1,0-1h11a.5.5,0,0,1,0,1Z" />
          <path className="view-icon-svg" d="M17.5,17H6.5a.5.5,0,0,1,0-1h11a.5.5,0,0,1,0,1Z" /></svg>
          </a>
          <a
            className={`mr-2 view-icon ${
              displayMode === "imagelist" ? "active" : ""
              }`}
            onClick={() => changeDisplayMode("imagelist")}
          >
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 19 19">
          <path className="view-icon-svg" d="M7,2V8H1V2H7m.12-1H.88A.87.87,0,0,0,0,1.88V8.12A.87.87,0,0,0,.88,9H7.12A.87.87,0,0,0,8,8.12V1.88A.87.87,0,0,0,7.12,1Z" />
          <path className="view-icon-svg" d="M17,2V8H11V2h6m.12-1H10.88a.87.87,0,0,0-.88.88V8.12a.87.87,0,0,0,.88.88h6.24A.87.87,0,0,0,18,8.12V1.88A.87.87,0,0,0,17.12,1Z" />
          <path className="view-icon-svg" d="M7,12v6H1V12H7m.12-1H.88a.87.87,0,0,0-.88.88v6.24A.87.87,0,0,0,.88,19H7.12A.87.87,0,0,0,8,18.12V11.88A.87.87,0,0,0,7.12,11Z" />
          <path className="view-icon-svg" d="M17,12v6H11V12h6m.12-1H10.88a.87.87,0,0,0-.88.88v6.24a.87.87,0,0,0,.88.88h6.24a.87.87,0,0,0,.88-.88V11.88a.87.87,0,0,0-.88-.88Z" /></svg>
          </a>
        </span>

          <div className="d-block d-md-inline-block">
            <UncontrolledDropdown className="mr-1 float-md-left btn-group mb-1">
              <DropdownToggle caret color="outline-dark" size="xs">
                Order By
                {selectedOrderOption.label}
              </DropdownToggle>
              <DropdownMenu>
                {orderOptions.map((order, index) => {
                  return (
                    <DropdownItem
                      key={index}
                      onClick={() => changeOrderBy(order.column)}
                    >
                      {order.label}
                    </DropdownItem>
                  );
                })}
              </DropdownMenu>
            </UncontrolledDropdown>
            <div className="search-sm d-inline-block float-md-left mr-1 mb-1 align-top">
              <input
                type="text"
                name="keyword"
                value={search}
                id="search"
                onChange={handleSearchChange}
                placeholder={messages["menu.search"]}
                onKeyPress={handleKeyPress}
              />
            </div>
          </div>
          <div className="float-md-right">
            <span className="text-muted text-small mr-1">{`${startIndex}-${endIndex} of ${
              totalItemCount
            } `}</span>
            <UncontrolledDropdown className="d-inline-block">
              <DropdownToggle caret color="outline-dark" size="xs">
                {selectedPageSize}
              </DropdownToggle>
              <DropdownMenu right>
                {pageSizes.map((size, index) => {
                  return (
                    <DropdownItem
                      key={index}
                      onClick={() => changePageSize(size)}
                    >
                      {size}
                    </DropdownItem>
                  );
                })}
              </DropdownMenu>
            </UncontrolledDropdown>
          </div>
        </Collapse>
      </div>
    )
  }
}
