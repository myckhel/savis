import React from 'react'
import { color } from '../../constants/theme'

export const Instructions = ({ text, head }) => {
  return (
    <div style={styles.container} className="col-12">
      {head &&
      <div style={styles.head} className="">
        <h3 style={styles.headText}>{head && head}</h3>
      </div>
      }
      {text &&
      <div style={styles.body} className="row">
        <div style={styles.icon} className="col-2">
          <i style={styles.star} className="simple-icon-star" />
        </div>
        <div style={styles.bodyTextContainer} className="col-10">
          <p style={styles.bodyText}>{text && text}</p>
        </div>
      </div>
      }
    </div>
  )
}

export const Text = ({className, style, children, size}) => (
  <p className={className} style={style}>{children}</p>
)

export const Thead = ({ cols }) => {
  return (
    <div className={'row'}>
      {cols.map((col, i) => (
        <div key={i} className={'col text-primary text-lg'}>
          <h3 className={'text-center'}>{col}</h3>
        </div>
      ))}
    </div>
  )
}

const styles = {
  container: {
    maxHeight: '100px',
    // marginVertical: 50,
  },
  head: {
    justifyContent: 'center',
  },
  body: {
    // flexDirection: 'row',
    border: 'red 2 solid',
    borderColor: 'red',
    borderWidth: 1,
  },
  star: {
    color: color['1'],
    // fontSize: 9,
  },
  bodyText: {
    fontSize: 9,
    color: color['1'],
  }
}
