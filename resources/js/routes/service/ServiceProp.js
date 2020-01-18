import React, { useEffect, useState, Fragment, PureComponent } from 'react';
import { Input, Label, Col, Row, Button } from "reactstrap";
import Select from "react-select";

export default ({remove, index}) => {
	const [limit, showLimit] = useState(false)
	const [select, showSelect] = useState(false)
	const [file, showFile] = useState(false)

	const onChange = (opt, act) => {
		const {action, option, removedValue} = act
		switch (option && option.label || removedValue && removedValue.label) {
			case 'Limit':
				showLimit(action === 'select-option')
				break;
			case 'Select':
				showSelect(action === 'select-option')
				break;
			case 'File':
				showFile(action === 'select-option')
				break;
		}
		if (action === 'clear') {
			if (limit) showLimit(false)
			if (select) showSelect(false)
			if (file) showFile(false)
		}
	}

	return (
    <Fragment>
      <Row id="service-type-inputs">
        <Col xs="5">
          <Input
            type="text" required name="name[]"
            id="name" placeholder="Prop Name"
          />
        </Col>
        <Col xs="5">
          <Select
						isMulti
						// defaultValue={[]}
						// filterOption
						onChange={onChange}
						options={[
							{label: 'Required', value: 'required', isub: true},
							{label: 'Number', value: 'number'},
							{label: 'Limit', value: 'limit'},
							{label: 'Select', value: 'select'},
							{label: 'File', value: 'file'},
						]}
            name={`rule_${index}[]`}
            placeholder="Select Rule"
          />
        </Col>
        <Col xs="2">
          <Button color="warning" onClick={() => remove(index)}>
						<span aria-hidden > &ndash;</span>
					</Button>
        </Col>
      </Row>
			<Row>
				{limit && (
				<Row>
					<Col xs="6">
						<Input type="number" required name={`min_${index}`}
	            placeholder="Min"/>
					</Col>
					<Col xs="6">
						<Input type="number" required name={`max_${index}`}
            	placeholder="Max"/>
					</Col>
				</Row>
				)}

				{select && (
				<ServiceSelect index={index}/>
				)}

				{file && (
				<Row>
					<Col xs="10">
						<Select
							isMulti
							onChange={onChange}
							required
							options={[
								{selected: true, label: 'Any', value: 'any', isub: true},
								{label: 'Cdr', value: 'cdr', isub: true},
								{label: 'Image', value: 'image'},
								{label: 'Pdf', value: 'pdf'},
								{label: 'Docs', value: 'doc'},
							]}
							name={`file_${index}[]`}
							placeholder="File Type"
						/>
					</Col>
				</Row>
				)}
			</Row>
    </Fragment>
	);
}

class ServiceSelect extends PureComponent {
	constructor(props) {
		super(props)
		this.state = {
			rows: [],
		}
	}

	componentDidMount = () => {
    this.addRow()
  }

	SelectRow = ({remove, index}) => (
		<Row>
			<Col xs="5">
				<Input type="text" name={`select_name_${this.props.index}[]`}
					placeholder="Name"/>
			</Col>
			<Col xs="5">
				<Input type="text" name={`select_value_${this.props.index}[]`}
					placeholder="Value"/>
			</Col>
			<Col xs="2">
				<Button color="danger" onClick={() => remove(index)}>
					<span aria-hidden > &ndash;</span>
				</Button>
			</Col>
		</Row>
	)

  addRow = () => {
    const { rows } = this.state
    let last = rows.length;
    const key = last > 0 ? rows[last-1].key+1 : 0
    const row = [...rows];
    row[last] = <this.SelectRow
      remove={this.removeRow}
      key={key} index={key} />
    this.setState({rows: row})
  }

  removeRow = (index) => {
    const { rows } = this.state
    const types = rows.filter((v, i) => v.props.index !== index)
    this.setState({rows: types})
  }

	render = () => {
		const { rows } = this.state
		return (
			<div>
				is multiple selection? <Input type='checkbox' />
				{rows}
				<Row>
          <Col xs="4" className="col-xs-offset-2">
            <Button
              color="primary" onClick={this.addRow}
            > <span aria-hidden > Add Select</span>
            </Button>
          </Col>
        </Row>
			</div>
		)
	}
}
