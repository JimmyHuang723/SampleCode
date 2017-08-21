import React, {Component} from 'react';
import Spinner from 'react-spinner';
import {List, Map, fromJS } from 'immutable';

import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import store from '../../redux/store';

import * as ChecklistSettingsAction from '../../redux/modules/checklistSettings';

export default class ChecklistScheduleColumn extends Component {
    constructor(props) {
        super(props);
        this.state = {
            periodCount: this.props.item.get('schedulePeriodCount') ? this.props.item.get('schedulePeriodCount') : '',
            period: this.props.item.get('schedulePeriod') ? this.props.item.get('schedulePeriod') : '',
            eventId: this.props.item.get('scheduleEventId') ? this.props.item.get('scheduleEventId') : '',
            beforeAfter: this.props.item.get('scheduleBeforeAfter') ? this.props.item.get('scheduleBeforeAfter') : ''
        }
    };

    // componentWillReceiveProps(nextProps) {
    //     if(nextProps.item.get('id') == '0') {
    //         this.setState({
    //             periodCount: nextProps.item.get('schedulePeriodCount') ? nextProps.item.get('schedulePeriodCount') : '',
    //             period: nextProps.item.get('schedulePeriod') ? nextProps.item.get('schedulePeriod') : '',
    //             eventId: nextProps.item.get('scheduleEventId') ? nextProps.item.get('scheduleEventId') : '',
    //             beforeAfter: nextProps.item.get('scheduleBeforeAfter') ? nextProps.item.get('scheduleBeforeAfter') : '',
    //         });
    //     }
    // }

     componentWillReceiveProps(nextProps) {
        let newState = {};
        let isNewItem = (nextProps.item.get('id') == '0');
        if(this.props.item.get('schedulePeriodCount') !=  this.props.item.get('schedulePeriodCount') || isNewItem) {
            newState.periodCount = nextProps.item.get('schedulePeriodCount') ? nextProps.item.get('schedulePeriodCount') : '';
        }
        if(this.props.item.get('schedulePeriod') !=   this.props.item.get('schedulePeriod') || isNewItem) {
            newState.period = nextProps.item.get('schedulePeriod') ? nextProps.item.get('schedulePeriod') : '';
        }
        if(this.props.item.get('scheduleEventId') !=  this.props.item.get('scheduleEventId') || isNewItem) {
            newState.eventId = nextProps.item.get('scheduleEventId') ? nextProps.item.get('scheduleEventId') : '';
        }
        if(this.props.item.get('scheduleBeforeAfter') !=   this.props.item.get('scheduleBeforeAfter') || isNewItem) {
            newState.beforeAfter = nextProps.item.get('scheduleBeforeAfter') ? nextProps.item.get('scheduleBeforeAfter') : '';
        }
        if(Object.getOwnPropertyNames(newState).length >0) {
            this.setState(newState);
        }
     }
  
    onChangeSchedulePeriodCount = (event) => {
        let value = event.target.value;
        if(!isNaN(value)) {
            this.setState({periodCount: value});
            value = parseInt(value, 10);
            this.props.updateChecklistItem(this.props.item, 'schedulePeriodCount', value);
            // if(this.props.item.get('id') > 0) {
            //     this.props.action.updateChecklistItem(this.props.item, 'schedulePeriodCount', value);
            // } else if(this.props.item.get('id') == 0) {
            //     let updatedNewItem = this.props.item.set('schedulePeriodCount', value);
            //     this.props.action.updateNewItem(updatedNewItem);
            // }
        }
    }

    onChangeSchedulePeriod = (event) => {
        let value = event.target.value;
        this.setState({period: value});
        this.props.updateChecklistItem(this.props.item, 'schedulePeriod', value);
        // if(this.props.item.get('id') > 0) {
        //     this.props.action.updateChecklistItem(this.props.item, 'schedulePeriod', value);
        // } else if(this.props.item.get('id') == 0) {
        //     let updatedNewItem = this.props.item.set('schedulePeriod', value);
        //     this.props.action.updateNewItem(updatedNewItem);
        // }
    }

    onChangeScheduleBeforeAfter = (event) => {
        let value = event.target.value;
        this.setState({beforeAfter: value});
        this.props.updateChecklistItem(this.props.item, 'scheduleBeforeAfter', value);
        // if(this.props.item.get('id') > 0) {
        //     this.props.action.updateChecklistItem(this.props.item, 'scheduleBeforeAfter', value);
        //  } else if(this.props.item.get('id') == 0) {
        //     let updatedNewItem = this.props.item.set('schedulePeriod', value);
        //     this.props.action.updateNewItem(updatedNewItem);
        // }
    }

    onChangeScheduleEventId = (event) => {
        let value = event.target.value;
        this.setState({eventId: value});
        this.props.updateChecklistItem(this.props.item, 'scheduleEventId', value);
        // if(this.props.item.get('id') > 0) {
        //     this.props.action.updateChecklistItem(this.props.item, 'scheduleEventId', value);
        // } else if(this.props.item.get('id') == 0) {
        //     let updatedNewItem = this.props.item.set('scheduleEventId', value);
        //     this.props.action.updateNewItem(updatedNewItem);
        // }
    }

    render() {
        return (
            <div style={{whiteSpace: 'nowrap'}}>
                <input type="text" style={{width: '20px'}} value={this.state.periodCount} onChange={this.onChangeSchedulePeriodCount}/>&nbsp;
                <select value={this.state.period} onChange={this.onChangeSchedulePeriod}>
                    <option value=''>Select</option>
                    <option value='minutes'>minutes</option>
                    <option value='hours'>hours</option>
                    <option value='days'>days</option>
                    <option value='weeks'>weeks</option>
                    <option value='months'>months</option>
                </select>&nbsp;
                <select value={this.state.beforeAfter} onChange={this.onChangeScheduleBeforeAfter}>
                    <option value=''>Select</option>
                    <option value='after'>after</option>
                    <option value='before'>before</option>
                </select>&nbsp;
                <select  style={{width: '120px'}} value={this.state.eventId} onChange={this.onChangeScheduleEventId}>
                    <option value='0' >Select..</option>
                    {   
                        this.props.events.map((event, index) => {
                            return <option key={`item-${index}`} value={event.get('id')}>{event.get('name')}</option>
                        })
                    }
                </select>
            </div>
        );
    }
}