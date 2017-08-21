import React, {Component} from 'react';
import {List,Map,fromJS} from 'immutable';

export default class WBSimpleDataTableCell extends Component {
  constructor(props) {
    super(props);
  }

  onItemClick = (e, column, item) => {
    this.props.itemClick(e, column, item);
  }

  onCheckItem = (e,item) => {
      if(item.get('checked')) {
          item = item.set('checked', false);
      } else {
          item = item.set('checked', true);
      }
      this.props.checkItem(item);
  }

  render() {
    let cellHTML = null;
    const column = this.props.column;
    const item = this.props.item;
    let cssClass = 'yui3-datatable-cell ' + (column.cssClass ? column.cssClass : '');
    switch(column.type) {
         case 'checkbox':
            cellHTML = (
                <td className={cssClass + 'wb-checkbox-col'} style={{width: (column.width? column.width:''), verticalAlign: 'top' }}>
                    <input type="checkbox" className="check-item data-id" checked={item.get('checked') ? true: false} onChange={(e) => this.onCheckItem(e, item)}/>
                </td>
            );
         break;
         default:
            let childComponent = null;
            if(column.customComponent) {
                childComponent = React.cloneElement(column.customComponent, {
                        item: item
                });
            } else {
                childComponent = <div>{item.get(column.field)}</div>;
            }
            cellHTML = (
                <td className={cssClass} style={{width: (column.width ? column.width:''), verticalAlign: 'top'}} onClick={(e) => this.onItemClick(e, column, item)}>
                    {childComponent}
                </td>
            );    
    }
    return (cellHTML);
  }
}