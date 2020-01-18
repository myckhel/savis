import React, {useState, useEffect} from 'react'
import {properties} from '../../helpers/ajax/customer'

const Loader = () => <div className="loader"></div>
export default ({id, route, Table, TableActions, title}) => {
  const [data, setData] = useState([])
  const [loading, setLoading] = useState(true)

  useEffect(() => {
    loadProps()
  }, [])

  const loadProps = async () => {
    try {
      const {status, props} = await properties(id)
      status && setData(props)
    } catch (e) {
      console.log({e});
    } finally {
      setLoading(false)
    }
  }

  return (
    loading ? <Loader /> :
    <Table loading={loading} title={title} data={data} config={{
      key: 'id', fields: ['name', 'rule', /*(meta) => <this.TableActions data={meta} />*/],
      heads: ['Name', 'Rules'/*, 'Actions'*/]
    }} />
  )
}

// <Table loading={loading} title={title} data={data} config={{
//   key: 'id', fields: ['currency_code', 'paid', 'status'],
//   heads: ['Currency', 'Amount', 'Status']
// }} />
