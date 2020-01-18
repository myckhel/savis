import React from 'react';
import Page, { View, Button, Input, Label, Colxx, Text,
	Table, Tr, Td, THead, TBody, Title
} from '../../components/app/Page'
import classnames from "classnames";

class ViewAble extends Page {
	constructor(props) {
		super(props);

		this.state = {
			isLoading: true,
		}
	}

  name="data"

  initAsync = async () => {
    try {
			const id = this.props.match.params.id
    	const data = await this.viewAsync(id)
			this.setState({[this.name]: data})
    } catch (e) {
      if (e.response) {
        if (e.response.status === 404) {
          this.setState({status: 404})
        }
      }
    } finally {
      if (this.state.status !== 404) {
        this.setState({isLoading: false})
      }
    }
  }

  viewAsync = (id) => ({})

	Right = () => (
		<View className="col">
			<Button color="danger"
			size="lg"
			className="top-right-button col-md-6"
			onClick={this.function} >Delete</Button>
			<Button color="warning"
			size="lg"
			className="top-right-button col-md-6"
			onClick={this.function} >Modify</Button>
		</View>
	)

	TableActions = ({data, onView}) => (
		<View className="row">
			<Button color="danger"
					size="xs" className="col-md-4"
					onClick={this.function}>Delete</Button>
			<Button color="warning"
					size="xs" className="col-md-4"
					onClick={this.function}>Edit</Button>
			<Button color="success"
					size="xs" className="col-md-4"
					onClick={onView}>View</Button>
		</View>
	)

	Table = ({data, config, title}) => (
		<View className="col-md-6 text-center">
			<Title>{title}</Title>
			<Table>
				<THead>
					<Tr>
						{config.heads.map((head, i) => <Td key={i}>{head}</Td>)}
					</Tr>
				</THead>
				<TBody>
					{data && data.map((dt) =>
					<Tr key={`${dt[config.key]}`}>
						{config.fields.map((field, i) =>
							<Td key={i}>{typeof field === 'function' ? field(dt) : dt[field]}</Td>
						)}
					</Tr>)}
				</TBody>
			</Table>
		</View>
	)

	Status = ({status, hd, bg, head}) =>
		<Colxx  className={classnames({[`bg-${bg}`]: !!bg})}
			sm={3}>
			{hd
			? <Text className="text-center justify-content-center text-light text-large">{head}</Text>
			: <Text className="text-center justify-content-center text-light text-large">{status}</Text>}
		</Colxx>

	render = () => {
		const { [this.name]: data } = this.state
		return (
			<this.Template
				pageName={ data.name || this.name}
				right={this.right}>

			</this.Template>
		);
	}
}

export default ViewAble
