import React, { PureComponent, Fragment } from "react";
import { injectIntl} from 'react-intl';
import mouseTrap from "react-mousetrap";
import Select from "react-select";
// import "react-select-search/style.css";
import { Row, Button, Modal, ModalHeader,
ButtonDropdown, DropdownMenu,
DropdownToggle, DropdownItem, Input, Label } from "reactstrap";

import { Colxx, Separator } from "../../components/CustomBootstrap";
import { BreadcrumbItems } from "../../components/BreadcrumbContainer";

import ServiceForm from './components/ServiceForm';
import TableFilter from '../../components/List/TableFilter';

import Table from '../../components/List/Table';

import { selectable } from '../../helpers/data'
import Http from '../../util/Http'
import formToObj from '../../helpers/formToObj'

class All extends PureComponent {
  constructor(props){
    super(props)

    this.state = {
      // errors: this.validator.errors,
      items: [],
      // visible: true,
      formState: 'service',
      selectedService: {},
      // serviceTypeRow: [],
      services: [],//{label: 'James', value: 33},{name: 'Fake', value: 29}
      displayMode: "imagelist",
      pageSizes: [10, 20, 30, 50, 100],
      selectedPageSize: 10,
      orderOptions:[
        {column: "name",label: "Name"},
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

  // onDismiss = () => {
  //   this.setState({ visible: false });
  // }

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

  toggleModal = () => {
    this.setState(prev => ({
      modalOpen: !prev.modalOpen,
      formState: prev.modalOpen ? 'service' : prev.formState
    }));
  }

  toggleDisplayOptions = () => {
    this.setState({ displayOptionsIsOpen: !this.state.displayOptionsIsOpen });
  }

  toggleSplit = () => {
    this.setState(prevState => ({
      dropdownSplitOpen: !prevState.dropdownSplitOpen
    }));
  }

  changeOrderBy = (column) => {
    this.setState(
      {
        selectedOrderOption: this.state.orderOptions.find(
          x => x.column === column
        )
      },
      () => this.dataListRender()
    );
  }

  changePageSize = (size) => {
    this.setState(
      {
        selectedPageSize: size,
        currentPage: 1
      },
      () => this.dataListRender()
    );
  }
  changeDisplayMode = (mode) => {
    this.setState({
      displayMode: mode
    });
    return false;
  }

  onChangePage = (page) => {
    this.setState({ currentPage: page },
      () => this.dataListRender()
    );
  }

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

  getIndex = (value, arr, prop) => {
    for (var i = 0; i < arr.length; i++) {
      if (arr[i][prop] === value) {
        return i;
      }
    }
    return -1;
  }

  handleChangeSelectAll = (isToggle) => {
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

  dataListRender = () => {
    const {selectedPageSize,currentPage,selectedOrderOption,search} = this.state;
    this.setState({
      isLoading:false
    }, () => {
      // console.log();
      Http.get(`/api/services?pageSize=${selectedPageSize}&page=${currentPage}
        &orderBy=${selectedOrderOption.column}&search=${search}`)
      .then(res => {
        return res.data
      }).then(data=>{
        this.setState({
          totalPage: data.last_page,
          items: data.data,
          selectedItems:[],
          totalItemCount : data.total,
          isLoading:true,
          services: selectable(data.data, ['name', 'id'])
        });
      })
    });
  }

  // delete
  delete = async () => {
    const items = this.state.selectedItems
    if (!items.length > 0) {
      return;
    }
    // delete items
    if (confirm('Are you sure you wish to delete selected services?')) {
      this.setState({isLoading: false}, () => {
        Http({url: `/api/services/delete/multiple`, data: {ids: items}, method: 'DELETE'})
        .then((res) => {
          return res.data
        })
        .then((res) => {
          swal("Success!", res.text ? '' : `${res.text.length} could not be deleted`, "success");
          this.dataListRender()
        })
        .catch((err) => {
          swal("Oops", "Internal Server Error", "error");
          this.dataListRender()
        })
      })
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

  toggleFormState = () => {
    this.setState((prev) => ({
      formState: prev.formState === 'service' ? 'type' : 'service'
    }))
  }

  selectService = (service) => {
    this.setState({ selectedService: service})
  }

  render() {
    const startIndex= (this.state.currentPage-1)*this.state.selectedPageSize
    const endIndex= (this.state.currentPage)*this.state.selectedPageSize
    const {messages} = this.props.intl;
    const serviceType = this.state.serviceTypeRow;
    const { formState, selectedService, errors, currentPage, totalItemCount, totalPage,
      displayOptionsIsOpen, displayMode, selectedOrderOption, orderOptions, search,
      selectedPageSize, pageSizes, isLoading} = this.state
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
        <Row>
          <Colxx xxs="12">
            <div className="mb-2">
              <h1>
                Services
              </h1>

              <div className="float-sm-right">
                <Button
                  color="primary"
                  size="lg"
                  className="top-right-button"
                  onClick={this.toggleModal}
                >
                  Add New Service
                </Button>
                {"  "}

                <Modal
                  isOpen={this.state.modalOpen}
                  toggle={this.toggleModal}
                  wrapClassName="modal-right"
                  backdrop="static"
                >
                  <ServiceForm
                    selectService={this.selectService}
                    selectedService={selectedService}
                    toggleFormState={this.toggleFormState}
                    formState={formState}
                    dataListRender={this.dataListRender}
                    toggleModal={this.toggleModal}
                    services={this.state.services} />
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
                    <DropdownItem onClick={this.delete} >
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
            {name: product.id, key: true, route: '/services/' },
            {link: {to: `services/${product.id}`}, name: product.name },
            {name: `${product.updated_at}`},
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
      </Fragment>}
      </Fragment>
    );
  }
}

export default injectIntl(mouseTrap(All))
