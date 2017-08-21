import React, { Component } from 'react';
import { List, Map } from 'immutable';

const inputStyle = {
    width: '90%',
    cursor: 'pointer'
}

const buttonStyle  = {
    width: '90%',
    cursor: 'pointer',
    border: '0px',
    backgroundColor: '#fff',
    padding: '0px',
    textAlign: 'left',
    paddingTop: '1px'
}

const deleteButtonStyle = {
    borderRadius: '5px',
    border: '0px',
    fontWeight: 'bold',
    color: '#fff',
    backgroundColor: '#822433',
    height: '18px',
    width: '18px',
    fontSize: '10px',
    padding: '0px',
    cursor: 'pointer',
    float: 'left',
    padingRight: '10px'
}


export default class ChecklistSubTaskItem extends Component {
    constructor(props) {
        super(props);
        this.state = {
            showEditControl: false,
            subTaskName: this.props.subTaskItem.get('name'),
        }
    }
    enableEdit = (event) => {
        this.setState({showEditControl: true});
    }
    disableEdit = (event) => {
        this.setState({showEditControl: false});
    }

    updateSubTaskItem = (event) => {
        let value = event.target.value;
        this.props.updateChecklistSubTaskItem(this.props.subTaskItem, 'name', value);
    }

     onCheckItem = (e, subTaskItem) => {
         // if(subTaskItem.get('checked')) {
         //     subTaskItem = subTaskItem.set('checked', false);
         // } else {
         //     subTaskItem = subTaskItem.set('checked', true);
         // }
         let allSubTasks = this.props.parentItem.get('tasks');
         allSubTasks = allSubTasks.map(item => {
             if(item.get('id') === subTaskItem.get('id')) {
                 if(item.get('checked')) {
                    return item.set('checked', false);
                 } else {
                     return item.set('checked', true);
                 }
             } else {
                 return item;
             }
         });
         let checklistItem = this.props.parentItem.set('tasks', allSubTasks);
         this.props.checkSubTaskItem(checklistItem);
     }
    
    render() {
        return (
            <div style={{cursor: 'pointer', height: '30px'}} >
                {/*<input style={deleteButtonStyle} type="button" onClick={(e) => { this.props.deleteSubTaskItem(this.props.subTaskItem) }} value="X"/>*/}
                <input type="checkbox" className="" 
                 style={{
                     float: 'left',
                     marginRight: '12px',
                     marginTop: '2px'
                 }}
                 checked={this.props.subTaskItem.get('checked') ? true: false} onChange={(e) => this.onCheckItem(e, this.props.subTaskItem)}/>
                <div style={{width: '80%', float: 'left', paddingLeft: '5px'}}>
                    {!this.state.showEditControl && <div onClick={this.enableEdit}> {this.props.subTaskItem.get('name')}</div>}
                    {
                        this.state.showEditControl &&
                            <input 
                            ref={(input) => { if(input) input.focus(); }} 
                            style={inputStyle} 
                            type="text" 
                            value={this.props.subTaskItem.get('name')}
                            onBlur={this.disableEdit}
                            onChange={this.updateSubTaskItem}/>
                    }
                </div>
                <img src="/assets/css/img/hamburger-menu-small.png" className='sub-task-sort-handle' style={{float: 'left', paddingRight: '5px'}} title="Re-order"/>
            </div>
        );
    }
}