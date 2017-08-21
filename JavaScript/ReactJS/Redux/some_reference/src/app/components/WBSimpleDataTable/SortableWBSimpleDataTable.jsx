import React, {Component} from 'react';
import {List,Map,fromJS} from 'immutable';
import _sortBy from 'lodash/sortBy';
import _findIndex from 'lodash/findIndex';

import WBSimpleDataTableHeaderRow from './WBSimpleDataTableHeaderRow';
import WBSimpleDataTableRow from './WBSimpleDataTableRow';
import WBSimpleDataTableDetailRow from './WBSimpleDataTableDetailRow';
import SortableWBSimpleDataTableRow from './SortableWBSimpleDataTableRow';
import SortableWBSimpleDataTableBody from './SortableWBSimpleDataTableBody';

export default class SortableWBSimpleDataTable extends Component {
  constructor(props) {
    super(props);
    this.state = {
        sortColumn: '',
        sortOrder: '',
        checkAll: false
    };
  }

  sort = (sortColumn) => {
    let sortOrder = 'DESC';
    let dataItems = this.props.data;
    dataItems = dataItems.toJS();

    if(this.state.sortColumn == sortColumn.field && this.state.sortOrder == 'DESC') {
        sortOrder = 'ASC';
    }
    if (sortOrder == 'DESC') {
        dataItems = _sortBy(dataItems, sortColumn.field).reverse();
    } else {
        dataItems = _sortBy(dataItems, sortColumn.field);
    }
    this.props.sort(fromJS(dataItems));
    this.setState({
        sortColumn: sortColumn.field,
        sortOrder: sortOrder
    });
  }

  checkAll = (blnCheckAll) => {
      this.props.checkAll(blnCheckAll);
      this.setState({checkAll: blnCheckAll});
  }

  checkItem = (item) => {
    this.props.checkItem(item);
    this.setState({checkAll: false});
  }
   // called when user drags and drops item
    onSortEnd = ({oldIndex, newIndex}) => {
        this.props.sortRowOrder(oldIndex, newIndex);
    };

    shouldCancelStart = (event) => {
        if(event.target.className!='sort-handle') {
            return true;
        } 
    }

  render() {
    return (
        <div style={{height: this.props.height, width: this.props.width, overflowY: 'auto'}}>
            
                <table cellSpacing="0" style={{width: '100%', maxWidth: '100%'}} className="yui3-datatable-table">
                    <WBSimpleDataTableHeaderRow headerCols={this.props.columns} sort={this.sort} checkAllStatus={this.state.checkAll} checkAll={this.checkAll}/>
                     <SortableWBSimpleDataTableBody 
                        onSortEnd = {this.onSortEnd}
                        columns={this.props.columns} 
                        checkItem={this.checkItem} 
                        itemClick={this.props.itemClick}
                        detailRowContent={this.props.detailRowContent}
                        data={this.props.data}
                        children={this.props.children}
                        shouldCancelStart={this.shouldCancelStart}
                    />
                </table>
        </div>  
    );
  }
}
// Specifies the default values for props:
SortableWBSimpleDataTable.defaultProps = {
  height: '100%',
  width: '100%',
  itemClick: () =>{},
  checkItem: (item) => {},
  checkAll: (blnCheckAll) => {},
  sort: ([]) => {},
  detailRowContent: null
};