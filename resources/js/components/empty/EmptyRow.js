import React from 'react'

export default function EmptyRow (props){
  return (
    <div style={styles.container}>Nothing Found</div>
  )
}

const styles = {
  container: {
    width: '100%',
    fontSize: '5em',
    fontWeight: '2em',
    color: 'white',
    backgroundColor: '#922c88',
    padding: '10px',
    textAlign: 'center',
    justifyContent: 'center',
  }
}
