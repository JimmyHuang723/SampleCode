import React, {Component} from 'react';
import {List,Map,fromJS} from 'immutable';

export default class WBSimpleDataTableHeaderRow extends Component {
  constructor(props) {
    super(props);
  }

  onHeaderClick = (e, sortColumn) => {
    this.props.sort(sortColumn);
  }
  onCheckAll = (e) => {
      this.props.checkAll(!this.props.checkAllStatus);
  }

  render() {
    return (
        <thead className="yui3-datatable-columns">
            <tr>   
                {
                    this.props.headerCols.map((column, index) => {
                        if(column.type && column.type == 'checkbox') {
                            return (
                                <th key={`item-${index}`} colSpan="1" rowSpan="1" className="yui3-datatable-header yui3-datatable-col-name yui3-datatable-sortable-column" scope="col" title={column.title}>
                                    <input type="checkbox" checked={this.props.checkAllStatus} onChange={this.onCheckAll}/>
                                </th>
                            );
                        } else {
                            return (
                                <th key={`item-${index}`} colSpan="1" rowSpan="1" className="yui3-datatable-header yui3-datatable-col-name yui3-datatable-sortable-column" scope="col" title={column.title}>
                                    <div className="yui3-datatable-sort-liner" tabIndex="0" unselectable="on" onClick={(e) => this.onHeaderClick(e, column)}>{column.title}<span className="yui3-datatable-sort-indicator"></span></div>
                                </th>
                            );
                        }
                    })
                }
            </tr>
        </thead>
    );
  }
}