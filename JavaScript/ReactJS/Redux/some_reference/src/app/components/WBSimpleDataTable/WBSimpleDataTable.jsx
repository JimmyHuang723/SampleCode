import React, {Component} from 'react';
import {List,Map,fromJS} from 'immutable';
import _sortBy from 'lodash/sortBy';
import _findIndex from 'lodash/findIndex';

import WBSimpleDataTableHeaderRow from './WBSimpleDataTableHeaderRow';
import WBSimpleDataTableRow from './WBSimpleDataTableRow';
import WBSimpleDataTableDetailRow from './WBSimpleDataTableDetailRow';


export default class WBSimpleDataTable extends Component {
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

  render() {
    return (
        <div style={{height: this.props.height, width: this.props.width, overflowY: 'auto'}}>
            
                <table cellSpacing="0" style={{width: '100%', maxWidth: '100%'}} className="yui3-datatable-table">
                    <WBSimpleDataTableHeaderRow headerCols={this.props.columns} sort={this.sort} checkAllStatus={this.state.checkAll} checkAll={this.checkAll}/>
                    <tbody className="yui3-datatable-data">
                        {this.props.children}
                        {
                            this.props.data.map((item, index) => (
                                    [
                                        <WBSimpleDataTableRow key={`item-${index}`} columns={this.props.columns} item={item} checkItem={this.checkItem} itemClick={this.props.itemClick}/>,    
                                        (
                                            this.props.detailRowContent && item.get('showDetails') && 
                                            <WBSimpleDataTableDetailRow colSpan={this.props.columns.length} detailRowContent={this.props.detailRowContent} item={item}/>
                                        )
                                    ]
                                ))
                            
                        }
                        <tr className="dummy-tr">
                            {
                                this.props.columns.map((column, index) => {
                                    if(column.type && column.type == 'checkbox') {
                                        return <td className="wb-checkbox-col" key={`item-${index}`}></td>;
                                    }
                                    else if(!column.type || (column.type && column.type != 'hidden')) {
                                        return <td key={`item-${index}`}></td>;    
                                    } 
                                })
                            }
                        </tr>
                    </tbody>
                </table>
            
        </div>  
    );
  }
}
// Specifies the default values for props:
WBSimpleDataTable.defaultProps = {
  height: '100%',
  width: '100%',
  itemClick: () =>{},
  checkItem: (item) => {},
  checkAll: (blnCheckAll) => {},
  sort: ([]) => {},
  detailRowContent: null
};