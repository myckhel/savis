import React, { Component } from 'react';
import { Col, Row as RRow } from 'reactstrap';
export const Colxx = (props) => (
    <Col {...props} widths={['xxs', 'xs', 'sm', 'md', 'lg', 'xl', 'xxl']} />
);

export const Separator = (props) => (
    <div className={`separator ${props.className}`}></div>
);

export const Table = (props) => (
  <table className="table" {...props}>{props.children}</table>
)

export const Tr = (props) => (
  <tr className="tr" {...props}>{props.children}</tr>
)

export const Td = (props) => (
  <td className="td" {...props}>{props.children}</td>
)

export const THead = (props) => (
  <thead className="thead" {...props}>{props.children}</thead>
)

export const TBody = (props) => (
  <tbody className="tbody" {...props}>{props.children}</tbody>
)

export const TFoot = (props) => (
  <tfoot className="tfoot" {...props}>{props.children}</tfoot>
)

export const Title = (props) => (
  <h1 className="text-center text-primary" {...props}>{props.children}</h1>
)
