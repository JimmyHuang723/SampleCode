import React, {Component} from 'react';
import { render, unmountComponentAtNode } from 'react-dom';
import {List, Map, fromJS } from 'immutable';
import Spinner from 'react-spinner';


export default class ChecklistAssignToList extends Component {
    constructor(props) {
        super(props);
        this.state ={
            // assignToOptions: this.setAssignTo(this.props)
             assignToOptions: this.props.assignToOptions

        };
    }
    
    assignChecklistItem = () => {
        let assignToIds = [];
        this.state.assignToOptions.map((item) => {
            if(item.get('checked')) {
                assignToIds.push(item.get('id'));
            }
        })
        this.props.assignChecklistItem(this.props.itemToAssign, assignToIds);
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
        // if(this.props.itemToAssign.get('id') > 0) {
        //     this.assignChecklistItem();
        // }
    }

    componentWillReceiveProps(nextProps) {
       this.setState({assignToOptions: this.setAssignTo(nextProps)});
    }

    setAssignTo = (props) => {
        let newItem = props.itemToAssign;
        let assignToOptions = props.assignToOptions;
        let assignTo = newItem.get('assignTo') ? newItem.get('assignTo') : '';
        assignTo = assignTo.split(',');
        assignToOptions = assignToOptions.map((item)=> {
            if(assignTo.indexOf(String(item.get('id'))) > -1) {
                return item.set('checked', true);
            } else {
                 return item.set('checked', false);
            }
        })
        return assignToOptions;
    }

    render() {
         return (   
            <div style={{padding: '0.6em'}}>
                <ul style={{listStyle: 'none', padding: '0px', margin: '0px'}}>
                    {
                        this.state.assignToOptions.map((item, index) => {
                            return (
                                <li key={`item-${index}`} onClick={(e) => this.onAssignChange(e, item)} style={{cursor: 'pointer'}}>
                                    <input type="checkbox" defaultChecked={item.get('checked')} />&nbsp;
                                    <span>{item.get('name')}</span>
                                </li>
                            )
                        })
                    }
                </ul>
                <a style={{marginTop: '10px'}} className="btn wb-save btn-primary" title="save" onClick={this.assignChecklistItem}>
                    <em></em><span>Save</span>
                </a> 
            </div>
        );
    }
}