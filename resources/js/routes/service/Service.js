import React, { useState, Fragment } from 'react';
import { Input, Label, Row, Col } from "reactstrap";
import Select from "react-select/async";

export default ({services, selectService, selectedService, getServices, credentials, handleInputChange, errors, parent}) => {
	// const [service, selectService] = useState(selectedService)
	return (
    <Fragment>
			<Row id="add-service-row">
			<Col sm="5" className="col-sm-offset-2">
				Parent Category
			</Col>
				<Col sm="7" className="col-sm-offset-2">
					<Select
						cacheOptions
						defaultOptions={services}
						placeholder={"Search Now"}
						onChange={selectService}
						defaultValue={selectedService}
						name='parent'
						loadOptions={getServices}
					/>
				</Col>
			</Row>
			{errors.has('parent') && <div className="invalid-feedback">{errors.first('parent')}</div>}

			<Row>
				<Label> Service Name </Label>
	      <Input
					value={credentials.name}
					onChange={handleInputChange}
	        type="text" required name="name"
	        id="name" placeholder="Service Name"
	      />
				{errors.has('name') && <div className="invalid-feedback">{errors.first('name')}</div>}
			</Row>

			<Row>
				<Label> Price </Label>
	      <Input
					value={credentials.price}
					onChange={handleInputChange}
	        type="text" name="price"
	        id="price" placeholder="Service Price"
	      />
				{errors.has('price') && <div className="invalid-feedback">{errors.first('price')}</div>}
			</Row>

			<Row>
				<Label> Charge </Label>
				<Input
					value={credentials.charge}
					onChange={handleInputChange}
					type="text" name="charge"
					id="charge" placeholder="Service Charge"
				/>
				{errors.has('charge') && <div className="invalid-feedback">{errors.first('charge')}</div>}
			</Row>

			<Row>
				<Label> Logo </Label>
				<Input
					disabled
					value={'coming soon!'}
					// onChange={handleInputChange}
					type="text" name="logo"
					id="logo" placeholder="Logo Coming Soon"
				/>
				{errors.has('logo') && <div className="invalid-feedback">{errors.first('logo')}</div>}
			</Row>

    </Fragment>
	)
}
