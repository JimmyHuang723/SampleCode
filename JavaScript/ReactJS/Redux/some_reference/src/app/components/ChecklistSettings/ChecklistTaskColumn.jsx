import React, { Component } from 'react';
import ChecklistTaskList from './ChecklistTaskList';
import {arrayMove} from 'react-sortable-hoc';
import {fromJS} from 'immutable';

const inputStyle = {
    width: '90%',
    cursor: 'pointer'
}

const addTaskStyle = {
    textAlign: 'center',
    width: '18px',
    backgroundColor: '#822433',
    borderRadius: '20px',
    color: '#fff',
    cursor: 'pointer'
}

export default class ChecklistTaskColumn extends Component {
    constructor(props) {
        super(props);
        this.state = {
            showEditControl: false,
            itemName: this.props.item.get('name'),
            showTasks: (this.props.item.get('tasks').size > 0)
        }
     }

     updateTaskName = (event) => {
        let value = event.target.value;
        this.setState({itemName: value});
        this.props.updateChecklistItem(this.props.item, 'name', value);
     }

     enableEdit = () => {
        this.setState({showEditControl: true});
     }

     disableEdit = () => {
        this.setState({showEditControl: false});
     }

     showHideTask = () => {
         if(this.state.showTasks) {
             this.setState({ showTasks: false });
         } else {
            if(!this.props.item.get('tasks')) {
                this.props.loadCheckListItemTask(this.props.item);
            }  
            this.setState({ showTasks: true });
         }
     }

    componentWillReceiveProps(nextProps) {
        let nextPropTaskName = nextProps.item.get('name') ? nextProps.item.get('name') : '' ;
        if(this.props.item.get('name') !=  nextPropTaskName) {
            this.setState({itemName: nextPropTaskName, showTasks: false});
        }
    }
     
     render() {
         return (
             <div>
                 <div style={{width: '100%', minWidth: '350px', height: '30px', cursor: 'pointer'}}>
                    <div style={{float: 'left', width: '90%'}}>
                        {!this.state.showEditControl && <div onClick={this.enableEdit}>{this.state.itemName}</div>}
                        {
                            this.state.showEditControl &&
                                <input 
                                ref={(input) => { if(input) input.focus(); }} 
                                style={inputStyle} 
                                type="text" 
                                value={this.state.itemName} 
                                onBlur={this.disableEdit}
                                onChange={this.updateTaskName}/>
                        }
                    </div>
                    <div style={{float: 'right', width: '20px', margin: 'auto 0'}}>
                        <div style={addTaskStyle} onClick={this.showHideTask}>+</div>
                    </div>
                </div>
                 {this.state.showTasks && <ChecklistTaskList 
                 sortSubTaskItems={this.props.sortSubTaskItems} 
                 updateChecklistSubTaskItem={this.props.updateChecklistSubTaskItem}
                 checkSubTaskItem={this.props.checkSubTaskItem}
                 addNewTask={this.props.addNewTask} 
                 item={this.props.item}
                 deleteSubTaskItem = {this.props.deleteSubTaskItem} />}
            </div>
         );
     }
}