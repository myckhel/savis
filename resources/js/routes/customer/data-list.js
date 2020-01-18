import React, { Component, Fragment } from "react";
import { injectIntl} from 'react-intl';
import {
  Row, Button, Modal, ModalHeader,
  ButtonDropdown, DropdownMenu,
  DropdownToggle, DropdownItem, Input, Label,
} from "reactstrap";
import classnames from "classnames";

import { Instructions } from '../../components/common/UI';
import TableFilter from '../../components/List/TableFilter';

import Table from '../../components/List/Table';

import { Colxx, Separator } from "../../components/CustomBootstrap";
import { BreadcrumbItems } from "../../components/BreadcrumbContainer";

import mouseTrap from "react-mousetrap";

import { ContextMenu, MenuItem } from "react-contextmenu";
function collect(props) {
  return { data: props.data };
}

import { fetchCustomers, deleteCustomers } from '../../helpers/ajax/customer'

import CustomerForm from './components/CustomerForm'

class DataListLayout extends Component {
    constructor(props) {
      super(props);

      this.state = {
        items: [],
        visible: true,
        displayMode: "list",
        pageSizes: [10, 20, 30, 50, 100, 1000],
        selectedPageSize: 10,
        categories:  [
          {label:'Cakes',value:'Cakes',key:0},
          {label:'Cupcakes',value:'Cupcakes',key:1},
          {label:'Desserts',value:'Desserts',key:2},
        ],
        orderOptions:[
          {column: "firstname",label: "Firstname"},
          {column: "lastname",label: "Lastname"},
          {column: "phone",label: "Phone"},
          {column: "email",label: "Email"},
          {column: "created_at",label: "Latest"}
        ],
        selectedOrderOption:  {column: "created_at",label: "Latest"},
        dropdownSplitOpen: false,
        modalOpen: false,
        currentPage: 1,
        totalItemCount: 0,
        totalPage: 1,
        search: "",
        selectedItems: [],
        lastChecked: null,
        displayOptionsIsOpen: false,
        isLoading:false
      };
    }

    onDismiss = () => {
      this.setState({ visible: false });
    }

    //  component section
    componentDidMount = () => {
      this.props.bindShortcut(["ctrl+a", "command+a"], () =>
        this.handleChangeSelectAll(false)
      );
      this.props.bindShortcut(["ctrl+d", "command+d"], () => {
        this.setState({
          selectedItems: []
        });
        return false;
      });
      this.dataListRender();
    }

    // toggle section
    toggleModal = () => {
      this.setState({
        modalOpen: !this.state.modalOpen
      });
    }

    toggleDisplayOptions = () => {
      this.setState({ displayOptionsIsOpen: !this.state.displayOptionsIsOpen });
    }

    toggleSplit = () => {
      this.setState(prevState => ({
        dropdownSplitOpen: !prevState.dropdownSplitOpen
      }));
    }

    // change section
    changeOrderBy = async (column) => {
      await this.setState({
        selectedOrderOption: this.state.orderOptions.find(
          x => x.column === column
        )},
      );
      this.dataListRender()
    }

    changePageSize = async (size) => {
      await this.setState({
        selectedPageSize: size,
        currentPage: 1
      });
      this.dataListRender()
    }

    changeDisplayMode = (mode) => {
      this.setState({
        displayMode: mode
      });
      return false;
    }

    onChangePage = async (page) => {
      await this.setState({ currentPage: page });
      this.dataListRender()
    }

    // key event section
    handleSearchChange = (e) => {
      this.setState({ search: e.target.value.toLowerCase() })
    }

    handleKeyPress = (e) => {
      if (e.key === "Enter") {
          this.dataListRender()
      }
    }

    handleCheckChange = (event, id) => {
      if (
        event.target.tagName == "A" ||
        (event.target.parentElement &&
          event.target.parentElement.tagName == "A")
      ) {
        return true;
      }
      if (this.state.lastChecked == null) {
        this.setState({
          lastChecked: id
        });
      }

      let selectedItems = this.state.selectedItems;
      if (selectedItems.includes(id)) {
        selectedItems = selectedItems.filter(x => x !== id);
      } else {
        selectedItems.push(id);
      }
      this.setState({
        selectedItems: [...selectedItems]
      });

      if (event.shiftKey) {
        var items = this.state.items;
        var start = this.getIndex(id, items, "id");
        var end = this.getIndex(this.state.lastChecked, items, "id");
        items = items.slice(Math.min(start, end), Math.max(start, end) + 1);
        selectedItems.push(
          ...items.map(item => {
            return item.id;
          })
        );
        selectedItems = Array.from(new Set(selectedItems));
        this.setState({
          selectedItems
        });
      }
      document.activeElement.blur();
    }

    // helper section
    getIndex = (value, arr, prop) => {
      for (var i = 0; i < arr.length; i++) {
        if (arr[i][prop] === value) {
          return i;
        }
      }
      return -1;
    }

    handleChangeSelectAll(isToggle) {
      if (this.state.selectedItems.length >= this.state.items.length) {
        if (isToggle) {
          this.setState({
            selectedItems: []
          });
        }
      } else {
        this.setState({
          selectedItems: this.state.items.map(x => x.id)
        });
      }
      document.activeElement.blur();
      return false;
    }

    // ajax section
    dataListRender = async () => {
      await this.setState({ isLoading:false })
      try {
        const data = await fetchCustomers(this.state)
        await this.setState({
          totalPage: data.last_page,
          items: data.data,
          selectedItems:[],
          totalItemCount : data.total,
          isLoading:true,
        });
      } catch (e) {
        // toast
        console.log(e);
      } finally {
        await this.setState({ isLoading: true })
      }
    }

    // delete
    delete = async () => {
      const items = this.state.selectedItems
      if (!items.length > 0) {
        return;
      }
      // delete items
      if (confirm('Are you sure you wish to delete selected customers?')) {
        await this.setState({isLoading: false})
        try {
          const res = await deleteCustomers(items)
          swal("Success!", res.message.length > 0 ? `${res.message.length} could not be deleted` : '', "success");
        } catch (e) {
          swal('Oooops!', e.message, 'error');
        } finally {
          this.dataListRender()
        }
      }
    }

    onContextMenuClick = (e, data, target) => {
      console.log("onContextMenuClick - selected items",this.state.selectedItems)
      console.log("onContextMenuClick - action : ", data.action);
    };

    onContextMenu = (e, data) => {
      const clickedProductId = data.data;
      if (!this.state.selectedItems.includes(clickedProductId)) {
        this.setState({
          selectedItems :[clickedProductId]
        });
      }
      return true;
    };

    render() {
      const startIndex= (this.state.currentPage-1)*this.state.selectedPageSize
      const endIndex= (this.state.currentPage)*this.state.selectedPageSize
      const {messages} = this.props.intl;
      const {errors, currentPage, totalItemCount, totalPage,
        displayOptionsIsOpen, displayMode, selectedOrderOption, orderOptions, search,
        selectedPageSize, pageSizes, isLoading,
      } = this.state;

      const { toggleDisplayOptions, changeDisplayMode, handleSearchChange,
        handleKeyPress, changeOrderBy, changePageSize
      } = this

      return (
        <Fragment>
        {!isLoading &&
          <div className="loading"></div>
        }
        {isLoading &&
          <Fragment>
          <div className="disable-text-selection">
            <Row>
              <Colxx xxs="12">
                <div className="mb-2">
                  <h1> Customers List </h1>

                  <div className="float-sm-right">
                    <Button
                      color="primary"
                      size="lg"
                      className="top-right-button"
                      onClick={this.toggleModal}
                    >
                      Add New Customer
                    </Button>
                    {"  "}
                    <Modal
                      isOpen={this.state.modalOpen}
                      toggle={this.toggleModal}
                      wrapClassName="modal-right"
                      backdrop="static"
                    >
                    <ModalHeader toggle={this.toggleModal}>
                      <Instructions
                        head={'Add New Customer'}
                        text={'coming soon'} />
                    </ModalHeader>
                    <CustomerForm
                      toggleModal={this.toggleModal}
                      beforeSubmit={() => this.setState({isLoading: false})}
                      afterSubmit={() => {this.setState({isLoading: true}); this.dataListRender(); }} />
                    </Modal>
                    <ButtonDropdown
                      isOpen={this.state.dropdownSplitOpen}
                      toggle={this.toggleSplit}
                    >
                      <div className="btn btn-primary pl-4 pr-0 check-button">
                        <Label
                          for="checkAll"
                          className="custom-control custom-checkbox mb-0 d-inline-block"
                        >
                          <Input
                            className="custom-control-input"
                            type="checkbox"
                            id="checkAll"
                            onChange = {() => {}}
                            checked={
                              this.state.selectedItems.length >=
                              this.state.items.length
                            }
                            onClick={() => this.handleChangeSelectAll(true)}
                          />
                          <span
                            className={`custom-control-label ${
                              this.state.selectedItems.length > 0 &&
                              this.state.selectedItems.length <
                                this.state.items.length
                                ? "indeterminate"
                                : ""
                            }`}
                          />
                        </Label>
                      </div>
                      <DropdownToggle
                        caret
                        color="primary"
                        className="dropdown-toggle-split pl-2 pr-2"
                      />
                      <DropdownMenu right>
                        <DropdownItem onClick={() => this.delete()} >
                          <i className="simple-icon-trash" /> <span>Delete</span>
                        </DropdownItem>
                      </DropdownMenu>
                    </ButtonDropdown>
                  </div>

                  <BreadcrumbItems match={this.props.match} />
                </div>
                <TableFilter
                  toggleDisplayOptions={toggleDisplayOptions}
                  displayOptionsIsOpen={displayOptionsIsOpen}
                  changeDisplayMode={changeDisplayMode}
                  displayMode={displayMode}
                  selectedOrderOption={selectedOrderOption}
                  orderOptions={orderOptions}
                  changeOrderBy={changeOrderBy}
                  search={search}
                  handleSearchChange={handleSearchChange}
                  handleKeyPress={handleKeyPress}
                  startIndex={startIndex}
                  endIndex={endIndex}
                  messages={messages}
                  totalItemCount={totalItemCount}
                  selectedPageSize={selectedPageSize}
                  pageSizes={pageSizes}
                  changePageSize={changePageSize}
                />
                <Separator className="mb-5" />
              </Colxx>
            </Row>
            <Table
              def={product => ([
                {name: product.id, key: true, route: '/customers/' },
                {link: {to: `customers/${product.id}`}, name: product.firstname, },
                {link: {to: `customers/${product.id}`}, name: product.lastname, },
                {name: `${product.country_code} ${product.phone}`, badge: true},
                {name: `${product.email}`},
                {name: `${product.state}, ${product.country}`},
              ])}
              selectedItems={this.state.selectedItems}
              handleCheckChange={this.handleCheckChange}
              displayMode={this.state.displayMode}
              onChangePage={this.onChangePage}
              items={this.state.items}
              selectedPageSize={selectedPageSize}
              currentPage={currentPage}
              totalItemCount={totalItemCount}
              totalPage={totalPage}
              dataListRender={this.dataListRender}
            />
          </div>

          <ContextMenu
            id="menu_id"
            onShow={e => this.onContextMenu(e, e.detail.data)}
          >
            <MenuItem
              onClick={this.onContextMenuClick}
              data={{ action: "copy" }}
            >
              <i className="simple-icon-docs" /> <span>Copy</span>
            </MenuItem>
            <MenuItem
              onClick={this.onContextMenuClick}
              data={{ action: "move" }}
            >
              <i className="simple-icon-drawer" /> <span>Move to archive</span>
            </MenuItem>
            <MenuItem
              onClick={this.onContextMenuClick}
              data={{ action: "delete" }}
            >
              <i className="simple-icon-trash" /> <span>Delete</span>
            </MenuItem>
          </ContextMenu>
        </Fragment>}
      </Fragment>
      );
    }
  }
  export default injectIntl(mouseTrap(DataListLayout))
