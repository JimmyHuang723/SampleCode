import React, {Component} from 'react';
import Spinner from 'react-spinner';
import {List, Map, fromJS} from 'immutable';
import ToolTip from 'react-portal-tooltip';

import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import store from '../../redux/store';

import * as ChecklistSettingsAction from '../../redux/modules/checklistSettings';
import ChecklistAssignToList from './ChecklistAssignToList';

const cssStyle = {
    backgroundColor: '#822433',
    color: '#fff',
    padding: '5px',
    borderRadius: '4px',
    cursor: 'pointer'
};

export default class ChecklistAssignToColumn extends Component {
    constructor(props) {
        super(props);
        this.state = {
            showAssigned: false
        }
    };

    showAssignedRoles = () => {
        this.setState({showAssigned: true});
    }

    hideAssignedRoles = () => {
        this.setState({showAssigned: false});
    }


    onAssignChange = (event, selectedItem) => {
        if(selectedItem.get('checked')) {
            selectedItem = selectedItem.set('checked', false);
        } else {
            selectedItem = selectedItem.set('checked', true);
        }
    
        let index = this.state.assignToOptions.findIndex(item => {
            return item.get('id') === selectedItem.get('id');
        });
        if (index > -1) {
            let assignToOptions = this.state.assignToOptions.set(index, selectedItem);
            this.setState({assignToOptions: assignToOptions});
        }
        
    }

    render() {
        let assignText = this.props.item.get('assignTo') ? 'Re-assign' : 'Assign';
        return (
            <div>
                <div id={`assignedRoles-${this.props.item.get('id')}`} onMouseEnter={this.showAssignedRoles} style={cssStyle} onMouseLeave={this.hideAssignedRoles}>
                    {assignText}
                </div>
                <ToolTip active={this.state.showAssigned} position="left" arrow="center" parent={`#assignedRoles-${this.props.item.get('id')}`}>
                   <ChecklistAssignToList 
                        assignToOptions={this.props.assignToOptions}
                        itemToAssign={this.props.item}
                        assignChecklistItem = {this.props.assignChecklistItem}
                    />
                </ToolTip>
            </div>
        );
    }
}