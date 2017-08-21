import React, {Component} from 'react';
import {List,Map,fromJS} from 'immutable';
import WBSimpleDataTableCell from './WBSimpleDataTableCell';


export default class WBSimpleDataTableRow extends Component {
  constructor(props) {
    super(props);
  }

  
  render() {
    return (
        <tr className="yui3-datatable-even">
            {
                this.props.columns.map((column, index) => {
                    return <WBSimpleDataTableCell key={`item-${index}`} 
                    item={this.props.item} 
                    column={column} 
                    showDetails={this.props.showDetails} 
                    checkItem={this.props.checkItem}
                    itemClick={this.props.itemClick}/>
                })
            }
        </tr>
    );
  }
}