import React from 'react';

import ViewAble from '../../components/app/ViewAble'
import { View, Button, Colxx, Text,
	IText
} from '../../components/app/Page'
import { injectIntl} from 'react-intl';
import mouseTrap from "react-mousetrap";
import classnames from "classnames";
import { viewService } from '../../helpers/ajax/service'

class ViewService extends ViewAble {
	constructor(props) {
		super(props);

		this.state = {
			isLoading: true,
			service: {},
		}
	}

	name="service"
	viewAsync = (id) => viewService(id)

	componentDidMount = () => {
    this.initAsync()
  }

	render = () => {
		const { service } = this.state
		console.log(this.props);
		const { service_metas, services, jobs_failed, jobs_on_hold, jobs_pending, jobs_completed } = service
		return (
			<this.Template
				pageName={ service.name || 'Service'}
				right={this.Right}>
				<View className="col-sm-12">
					<this.Status bg="danger" hd head="Jobs Failed" />
					<this.Status bg="warning" hd head="Jobs On Hold" />
					<this.Status bg="info" hd head="Jobs Pending" />
					<this.Status bg="success" hd head="Jobs Completed" />
				</View>
				<View className="col-sm-12">
					<this.Status bg="danger" status={jobs_failed} />
					<this.Status bg="warning" status={jobs_on_hold} />
					<this.Status bg="info" status={jobs_pending} />
					<this.Status bg="success" status={jobs_completed} />
				</View>
				<View className="col-sm-12">
					<this.Table title={<IText id="sub-categories" />} data={services} config={{
						key: 'id', fields: [
							'name', 'price', 'charge', (service) => <this.TableActions onView={() => this.props.history.replace(`/services/${service.id}`)} data={service} />
						],
						heads: ['Name', 'Price', 'Charge', 'Actions']
					}} />

					<this.Table title="Properties" data={service_metas} config={{
						key: 'id', fields: ['name', 'rule', (meta) => <this.TableActions data={meta} />],
						heads: ['Name', 'Rules', 'Actions']
					}} />

					<this.Table title="Reserved" data={[]} config={{
						key: 'id', fields: [],
						heads: []
					}} />
				</View>
			</this.Template>
		);
	}
}

export default injectIntl(mouseTrap(ViewService))
