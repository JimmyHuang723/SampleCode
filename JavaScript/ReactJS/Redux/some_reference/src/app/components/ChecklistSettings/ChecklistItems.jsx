import React, {Component} from 'react';
import Spinner from 'react-spinner';
import {List, Map, fromJS } from 'immutable';
import {arrayMove} from 'react-sortable-hoc';

import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import store from '../../redux/store';

import * as ChecklistSettingsAction from '../../redux/modules/checklistSettings';

// import * as AAABuyersAction from '../../redux/modules/aaaBuyers';
import WBSimpleDataTable from '../WBSimpleDataTable/WBSimpleDataTable';
import ChecklistAddNewItem from './ChecklistAddNewItem';
import ChecklistScheduleColumn from './ChecklistScheduleColumn';
import ChecklistPriorityColumn from './ChecklistPriorityColumn';
import ChecklistAssignToColumn from './ChecklistAssignToColumn';
import ChecklistTaskColumn  from './ChecklistTaskColumn';
import SortableWBSimpleDataTable from '../WBSimpleDataTable/SortableWBSimpleDataTable';

    const ReorderHandle = function() {
        return (
            <div>
                <img src="/assets/css/img/hamburger-menu-small.png" className="sort-handle" title="Re-order" style={{minWidth:'20px', cursor: 'pointer'}}/>
            </div>
        );
    }
export default class ChecklistItems extends Component {
  constructor(props) {
    super(props);
  }




//   sort = (sortedItems) => {
//      this.props.action.sortAAABuyers(sortedItems);
//   }


 componentDidMount() {
    // this.props.action.loadChecklistItems(this.props.activeSubCategory);
 }

//  onRowItemClick = (e, column, item) => {
//     let selectedBuyer = item.toJS();
//     WB.my.reactDashboardCommon.showBuyerEdit(selectedBuyer.id);
//  }

    

  render() {
    let columns =  [
        { field: 'id', title: '', type: 'checkbox' },
        { field: 'priority', title: 'Priority', width: '30px', customComponent: <ChecklistPriorityColumn updateChecklistItem={this.props.updateChecklistItem}/> },
        {   
            field: 'name', 
            title: 'Task',  
            customComponent: <ChecklistTaskColumn 
                checkSubTaskItem={this.props.checkSubTaskItem} 
                updateChecklistSubTaskItem={this.props.updateChecklistSubTaskItem} 
                addNewTask={this.props.addNewTask} 
                loadCheckListItemTask={this.props.loadCheckListItemTask} 
                updateChecklistItem={this.props.updateChecklistItem}
                sortSubTaskItems={this.props.sortSubTaskItems}
                deleteSubTaskItem={this.props.deleteSubTaskItem}/> 
        },
        { field: 'when', title: 'When', cssClass: 'align-center', width: '150px', customComponent: <ChecklistScheduleColumn events={this.props.events} updateChecklistItem={this.props.updateChecklistItem}/> },
        { field: 'assignedBy', title: 'From', cssClass: 'align-center', width: '30px' },
        { field: 'assignTo', title: 'To', cssClass: 'align-center', width: '100px', customComponent: <ChecklistAssignToColumn assignToOptions = {this.props.assignToOptions} assignChecklistItem={this.props.assignChecklistItem}/> },
        { field: 'seq', title: '', width: '20px', cssClass: 'wb-checkbox-col', customComponent: <ReorderHandle/>}
    ];
    return (
      <div>
        <div className="yui3-g-r wb-header">
            <table className="wb-header-table">
                <tbody>
                <tr>
                    <td style={{textAlign:'center'}}><span className="wb-header-label">Private Treaty</span></td>
                </tr>
                </tbody>
            </table>
            <div style={{top: '5px', right: '5px', position: 'absolute'}} onClick={this.props.removeChecklistItems}>
                <a className="btn-normal wb-delete" title="Delete"><span>DELETE</span></a>
            </div>
        </div>
        <div className="yui3-g-r">
            <div className="yui3-u-1">
                {
                <SortableWBSimpleDataTable height="570px" width="100%" 
                    columns={columns} 
                    checkItem={this.props.checkItem} 
                    checkAll={this.props.checkAllItems}
                    data={this.props.checklistItems}
                    sortRowOrder = {this.props.sortChecklistItems}
                    >
                    <ChecklistAddNewItem newItem={this.props.newItem} 
                        events={this.props.events} 
                        updateChecklistItem={this.props.updateChecklistItem}
                        assignToOptions = {this.props.assignToOptions} 
                        assignChecklistItem={this.props.assignChecklistItem} 
                    />
                </SortableWBSimpleDataTable>}
            </div>
        </div>
      </div>
    );
  }

}