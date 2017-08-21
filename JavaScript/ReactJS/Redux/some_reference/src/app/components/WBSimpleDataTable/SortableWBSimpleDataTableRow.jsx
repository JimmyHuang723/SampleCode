import React, {Component} from 'react';
import {List,Map,fromJS} from 'immutable';
import WBSimpleDataTableCell from './WBSimpleDataTableCell';
import {SortableElement} from 'react-sortable-hoc';

const SortableWBSimpleDataTableRow = SortableElement(({columns, item, showDetails, checkItem, itemClick}) => {
   return (
        <WBSimpleDataTableRowComponent 
            columns={columns}
            item={item} 
            showDetails={showDetails} 
            checkItem={checkItem}
            itemClick={itemClick}
        />
  );
});

class WBSimpleDataTableRowComponent extends Component {
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

export default SortableWBSimpleDataTableRow;