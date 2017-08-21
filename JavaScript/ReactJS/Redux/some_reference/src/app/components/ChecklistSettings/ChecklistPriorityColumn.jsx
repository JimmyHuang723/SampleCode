import React, {Component} from 'react';
import Spinner from 'react-spinner';
import {List, Map, fromJS } from 'immutable';

import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import store from '../../redux/store';

import * as ChecklistSettingsAction from '../../redux/modules/checklistSettings';

export default class ChecklistPriorityColumn extends Component {
    constructor(props) {
        super(props);
        this.state = {
            value: this.props.item.get('priority') ? this.props.item.get('priority') : 1
        }
    };

    // componentWillReceiveProps(nextProps) {
    //     if(nextProps.item.get('id') == '0') {
    //         this.setState({value: nextProps.item.get('priority')});
    //     }
    // }
    componentWillReceiveProps(nextProps) {
        let nextPropPriority = nextProps.item.get('priority') ? nextProps.item.get('priority') : '' ;
        if(this.props.item.get('priority') !=  nextPropPriority || nextProps.item.get('id') == '0') {
            this.setState({value: nextPropPriority});
        }
    }

    onChangePriority = (event) => {
        let value = event.target.value;
        if(!isNaN(value)) {
            this.setState({value: value});
            value = parseInt(value, 10);
            this.props.updateChecklistItem(this.props.item, 'priority', value);
        }
    }
    
    componentDidMount() {
    }

    render() {
        return (
            <div>
                <select style={{width: '35px'}} value={this.state.value} onChange={this.onChangePriority}>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                </select>
            </div>
        );
    }
}