import React, { Component } from 'react';
import {List, Map, fromJS} from 'immutable';
import {SortableContainer, SortableElement, arrayMove} from 'react-sortable-hoc';
import ChecklistSubTaskItem from './ChecklistSubTaskItem';

const SortableChecklistSubTaskItem = SortableElement(({deleteSubTaskItem, subTaskItem, updateChecklistSubTaskItem, checkSubTaskItem, parentItem}) => {
   return (
     <ChecklistSubTaskItem deleteSubTaskItem={deleteSubTaskItem} subTaskItem={subTaskItem} updateChecklistSubTaskItem={updateChecklistSubTaskItem} checkSubTaskItem={checkSubTaskItem} parentItem={parentItem}/>
  );
});

const SortableSubTaskList = SortableContainer(({deleteSubTaskItem, updateChecklistSubTaskItem, checkSubTaskItem, parentItem, itemTasks}) => {
    return (
            <div>
            {
                itemTasks.map((item, index) => {
                    return (
                            <SortableChecklistSubTaskItem 
                                key={`task-item-${index}`} 
                                index={index}
                                subTaskItem={item} 
                                updateChecklistSubTaskItem={updateChecklistSubTaskItem}
                                checkSubTaskItem={checkSubTaskItem}
                                parentItem = {parentItem}
                                deleteSubTaskItem={deleteSubTaskItem}
                            />
                        )
                })
            }
            </div>
    )

});

export default class ChecklistTaskList extends Component {
    constructor(props) {
        super(props);
        this.state = {
            newTask: '',
            items: ['item1', 'item2', 'item3', 'item4'],
        };
        
    }
   
    addNewTask = (event) => {
        let newTask = event.target.value;
        this.setState({newTask: newTask});
    }

    //called when user hit enter in textbox to add new
    onEnterAddNew = (event) => {
        if(event.key === 'Enter') {
             this.props.addNewTask(this.props.item, this.state.newTask);
             this.setState({newTask: ''});
        }
    }

   
    // called when user drags and drops item
    onSortEnd = ({oldIndex, newIndex}) => {
        let taskItems = this.props.item.get('tasks').toJS();
        taskItems = arrayMove(taskItems, oldIndex, newIndex);
        taskItems = fromJS(taskItems);
        taskItems = taskItems.map((item, index) => item.set('seq', index+1));
        let checklistItem = this.props.item.set('tasks', taskItems);
        this.props.sortSubTaskItems(checklistItem);
    };

    shouldCancelStart = (event) => {
        if(event.target.className!='sub-task-sort-handle') {
            return true;
        } 
    }

    render() {
        return (
            <div style={{ paddingLeft: '10px', paddingBottom: '10px'}}>
                {this.props.item.get('tasks') && 
                    <SortableSubTaskList
                        onSortEnd = {this.onSortEnd}
                        updateChecklistSubTaskItem={this.props.updateChecklistSubTaskItem}
                        checkSubTaskItem={this.props.checkSubTaskItem}
                        parentItem = {this.props.item}
                        itemTasks = {this.props.item.get('tasks')}
                        deleteSubTaskItem = {this.props.deleteSubTaskItem}
                        shouldCancelStart = {this.shouldCancelStart}
                    />
                }
                <div>
                    <input style={{width: '90%'}} type="text" placeholder="Add new task" value={this.state.newTask} onChange={this.addNewTask} onKeyPress={this.onEnterAddNew}/>
                </div>
            </div>
        );
  }
}
