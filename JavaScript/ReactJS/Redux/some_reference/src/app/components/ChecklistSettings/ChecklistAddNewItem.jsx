import React, {Component} from 'react';
import Spinner from 'react-spinner';
import {List, Map, fromJS } from 'immutable';

import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import store from '../../redux/store';

import * as ChecklistSettingsAction from '../../redux/modules/checklistSettings';

import ChecklistScheduleColumn from './ChecklistScheduleColumn';
import ChecklistPriorityColumn from './ChecklistPriorityColumn';
import ChecklistAssignToColumn from './ChecklistAssignToColumn';

export default class ChecklistAddNewItem extends Component {
    constructor(props) {
        super(props);
        this.state = {
            taskName: this.props.newItem.get('name')
        }
    };

    componentWillReceiveProps(nextProps) {
        this.setState({taskName: nextProps.newItem.get('name')});
    }
    
    onChangeItemName = (event) => {
        let value = event.target.value;
        this.setState({taskName: value});
        this.props.updateChecklistItem(this.props.newItem, 'name', value);
    }

    render() {
        let ChecklistPriorityColumnCloned = React.cloneElement(<ChecklistPriorityColumn updateChecklistItem={this.props.updateChecklistItem}/> , {item: this.props.newItem});
        let ChecklistScheduleColumnCloned = React.cloneElement(<ChecklistScheduleColumn events={this.props.events} updateChecklistItem={this.props.updateChecklistItem}/>, {item: this.props.newItem});
        let ChecklistAssignToColumnCloned = React.cloneElement(<ChecklistAssignToColumn assignToOptions = {this.props.assignToOptions} assignChecklistItem={this.props.assignChecklistItem} openAssignTo={this.props.openAssignTo}/>, {item: this.props.newItem});
        return (
                <tr className="yui3-datatable-even">
                    <td className="yui3-datatable-cell wb-checkbox-col">
                       &nbsp;
                    </td>
                    <td className="yui3-datatable-cell " style={{width: '30px'}}>
                        <div>
                            {ChecklistPriorityColumnCloned}
                         </div>
                    </td>
                    <td className="yui3-datatable-cell" >
                        <div><input type="text" style={{width: '96%'}} value={this.state.taskName} onChange={this.onChangeItemName}/></div>
                    </td>
                    <td className="yui3-datatable-cell align-center" style={{width: '150px'}}>
                        <div>
                            {ChecklistScheduleColumnCloned}
                        </div>
                    </td>
                    <td className="yui3-datatable-cell align-center" style={{width: '30px'}}>
                        <div>&nbsp;</div>
                    </td>
                    <td className="yui3-datatable-cell align-center" style={{width: '100px'}}>
                        <div>
                            {ChecklistAssignToColumnCloned}
                        </div>
                    </td>
                    <td className="yui3-datatable-cell wb-checkbox-col">
                       &nbsp;
                    </td>
                </tr>
        );
    }
}