import React, {Component} from 'react';
import {List,Map,fromJS} from 'immutable';


const detailRowStyle = {
    borderTop: '2px solid #822433',
    borderBottom: '2px solid #822433',
    padding: '10px'
}

export default class WBSimpleDataTableDetailRow extends Component {
  constructor(props) {
    super(props);
  }
  render() {
    let detailRowContent = React.cloneElement(this.props.detailRowContent, { item: this.props.item});
    return (
        <tr><td colSpan={this.props.colSpan} style={detailRowStyle}>{detailRowContent}</td></tr>
    );
  }
}