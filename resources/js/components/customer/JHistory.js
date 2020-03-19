import React, {useState, useEffect} from 'react'
import {jobs} from '../../helpers/ajax/customer'

// currency_code: "NGN"
// customer_service_id: 501
// id: 501
// laravel_through_key: 501
// paid: 210
// payed_at: "2019-07-17 00:00:00"
// reference: null
// status: "completed"
// updated_at: "2019-07-17 00:00:00"
const Loader = () => <div className="loader"></div>
export default ({id, route, Table, TableActions, title}) => {
  // const [id, setId] = useState()
  const [data, setData] = useState([])
  const [loading, setLoading] = useState(true)

  useEffect(() => {
    loadHistory()
  }, [])

  const loadHistory = async () => {
    try {
      const {status, histories} = await jobs(id)
      status && setData(histories)
    } catch (e) {
      console.log({e});
    } finally {
      setLoading(false)
    }
  }

  return (
    loading ? <Loader /> :
    <Table loading={loading} title={title} data={data} config={{
      key: 'id', fields: ['currency_code', 'paid', 'status'],
      heads: ['Currency', 'Amount', 'Status']
    }} />
  )
}
