import React, {Component} from 'react';
import { render, unmountComponentAtNode } from 'react-dom';
import {List, Map, fromJS } from 'immutable';
import Spinner from 'react-spinner';
import {arrayMove} from 'react-sortable-hoc';

import { bindActionCreators } from 'redux';
import { Provider, connect } from 'react-redux';
import store from '../../redux/store';

import * as ChecklistSettingsAction from '../../redux/modules/checklistSettings';
import ChecklistItems from './ChecklistItems';
import ChecklistMainCategoryTab from './ChecklistMainCategoryTab';
import ChecklistSubCategoryTab from './ChecklistSubCategoryTab';


class ChecklistSettings extends Component {
    constructor(props) {
        super(props);
    }

    componentDidMount() {
        this.props.action.initChecklistSettings();
    }

    changeCategory = (selectedCategory, activeSubCategories) => {
        this.props.action.changeCategory(selectedCategory, activeSubCategories);
    }

    changeSubCategory = (selectedSubCategory) => {
        this.props.action.changeSubCategory(selectedSubCategory);
    }

    updateChecklistItem = (updatedItem, updatedField, updatedValue) => {
        if(updatedItem.get('id') > 0) {
            this.props.action.updateChecklistItem(updatedItem, updatedField, updatedValue);
        } else if(updatedItem.get('id') == 0) {
            let updatedNewItem = updatedItem.set(updatedField, updatedValue);
            this.props.action.updateNewItem(updatedNewItem);
        }
    }

    checkItem = (item) => {
        this.props.action.checkItem(item);
    }

    checkAllItems = (blnCheckAll) => {
        let items = this.props.checklistItems.map(item => {
             return item.set('checked', blnCheckAll);
         });
        this.props.action.checkAllItems(items);
    }

    addNewSubCategory = (newSubCategoryName) => {
        this.props.action.addNewSubCategory(newSubCategoryName, this.props.activeCategory);
    }

    onRemoveChecklistItems = () => {
        let items = this.props.checklistItems.filter((item) => {
            return item.get('checked');
        });
        // if(items.size > 0) {
        //     this.props.action.removeChecklistItems(items, this.props.activeSubCategory);
        // }

        let subTaskItems = List();
        this.props.checklistItems.map((item) => {
            let subTasks = item.get('tasks');
            if(subTasks) {
                subTasks.map(subTaskItem => {
                    if(subTaskItem.get('checked')) {
                        subTaskItems = subTaskItems.push(subTaskItem);
                    }
                });
            }
        });
        // if(subTaskItems.size > 0) {
        //     this.props.action.removeChecklistSubTaskItems(subTaskItems, this.props.activeSubCategory);
        // }
        this.props.action.removeChecklistItems(items, subTaskItems);
    }

    assignChecklistItem = (itemToAssign, selectedAssignToIds) => {
        if(itemToAssign.get('id') ==0){
            let validationMsg = '';
            if (itemToAssign.get('name').trim() == '') { validationMsg = 'Name\n'; }  
            if (itemToAssign.get('priority') == '') { validationMsg += 'Priority\n'; } 
            if (itemToAssign.get('schedulePeriodCount') == '' || 
                itemToAssign.get('schedulePeriod').trim() == '' || 
                itemToAssign.get('scheduleEventId') == '' ) { 
                    validationMsg += 'when\n'; 
            }
            if (selectedAssignToIds.length == 0) { validationMsg += 'Assign To\n'; }
            if(validationMsg == '') {
                this.props.action.addNewItem(itemToAssign, selectedAssignToIds, this.props.activeSubCategory);    
            } else {
                validationMsg = 'Please set values for following:\n' + validationMsg;
                alert(validationMsg);
            }
        } else {
            this.props.action.assignChecklistItem(itemToAssign, selectedAssignToIds, this.props.activeSubCategory);
        }
        
    }

    loadCheckListItemTask = (checklistItem) => {
        this.props.action.locaChecklistItemTasks(checklistItem);
    }

    addNewTask = (checklistItem, newTask) => {
        this.props.action.addNewTask(checklistItem, newTask);
    }

    updateChecklistSubTaskItem = (checklistSubTaskItem, updatedField, updatedValue) => {
        this.props.action.updateChecklistSubTaskItem(checklistSubTaskItem, updatedField, updatedValue);
    }

    sortSubTaskItems = (checklistItem) => {
        this.props.action.sortSubTaskItems(checklistItem);
    }

    deleteSubTaskItem = (subTaskItem) => {
        this.props.action.deleteSubTaskItem(subTaskItem);
    }

    // // called when user drags and drops item
    // reOrderRows = ({oldIndex, newIndex}) => {
    //     console.log(oldIndex);
    //     console.log(newIndex);
    //     let checklistItems = this.props.checklistItems.toJS();
    //     checklistItems = arrayMove(checklistItems, oldIndex, newIndex);
    //     checklistItems = fromJS(checklistItems);
    //     checklistItems = checklistItems.map((item, index) => item.set('seq', index));
    //     this.props.sortChecklistItems(checklistItems);
    // };
    sortChecklistItems = (oldIndex, newIndex) => {
        let checklistItems = this.props.checklistItems.toJS();
        checklistItems = arrayMove(checklistItems, oldIndex, newIndex);
        checklistItems = fromJS(checklistItems);
        checklistItems = checklistItems.map((item, index) => item.set('seq', index+1));
        this.props.action.sortChecklistItems(checklistItems, this.props.activeSubCategory);
    }
    checkSubTaskItem = (item) => {
        this.props.action.checkSubTaskItem(item);
    }


    render() {
        return (
            <div className="wb-checklist-settings">
                {this.props.processing && <Spinner/>}
                <ChecklistMainCategoryTab categories={this.props.categories} subCategories={this.props.subCategories} changeCategory={this.changeCategory}/>
                <div className="yui3-g wb-checklist-tab-panel">
                    <div className="yui3-g-r wb-checklist-tab-body"> 
                        <div style={{height: '100%', width: '10%', float: 'left'}}>
                            <ChecklistSubCategoryTab activeSubCategories={this.props.activeSubCategories} addNewSubCategory={this.addNewSubCategory} changeSubCategory={this.changeSubCategory} />
                        </div>
                        <div style={{height: '100%', width: '90%', float: 'right'}}>
                            <div className="yui3-u-1">
                            <ChecklistItems 
                                newItem={this.props.newItem}
                                checklistItems={this.props.checklistItems} 
                                events={this.props.events} 
                                assignToOptions = {this.props.assignToOptions}

                                updateChecklistItem={this.updateChecklistItem}
                                checkItem={this.checkItem}
                                checkAllItems={this.checkAllItems}
                                removeChecklistItems={this.onRemoveChecklistItems}
                                openAssignTo={this.onOpenAssignTo}
                                loadCheckListItemTask = {this.loadCheckListItemTask}
                                addNewTask = {this.addNewTask}
                                updateChecklistSubTaskItem = {this.updateChecklistSubTaskItem}
                                checkSubTaskItem = {this.checkSubTaskItem}

                                sortSubTaskItems = {this.sortSubTaskItems}
                                deleteSubTaskItem = {this.deleteSubTaskItem}
                                assignChecklistItem={this.assignChecklistItem}
                                sortChecklistItems = {this.sortChecklistItems}
                             />
                             </div>
                        </div>
                    </div>
                </div>
            </div>
        );
    }
}

let mapStateToProps = (state, prop) => {
    return {
        categories: state.ChecklistSettings.get('categories'),
        subCategories: state.ChecklistSettings.get('subCategories'),
        activeSubCategories: state.ChecklistSettings.get('activeSubCategories'),
        activeCategory: state.ChecklistSettings.get('activeCategory'), 
        activeSubCategory: state.ChecklistSettings.get('activeSubCategory'), 
        events: state.ChecklistSettings.get('events'),
        processing: state.ChecklistSettings.get('processing'),
        checklistItems: state.ChecklistSettings.get('checklistItems'),
        newItem: state.ChecklistSettings.get('newItem'),
        assignToOptions: state.ChecklistSettings.get('assignToOptions'),
        assignToIsOpen:  state.ChecklistSettings.get('assignToIsOpen'),
        itemToAssign:  state.ChecklistSettings.get('itemToAssign'),
    }
}

let mapDispatchToProps = (dispatch) => {
    return {
        action: bindActionCreators(ChecklistSettingsAction, dispatch)
    }
}

const ChecklistSettingsComponent = connect(mapStateToProps, mapDispatchToProps)(ChecklistSettings);

window.WB.react.unmountChecklistSettingsComponent = () => {
    if(document.getElementById('checklistSettings') != null) {
        unmountComponentAtNode(document.getElementById('checklistSettings'));
    }
}

window.WB.react.renderChecklistSettingsComponent= function() {
    render(
      <Provider store={store}>
        <ChecklistSettingsComponent/>
      </Provider>, document.getElementById('checklistSettings')
    );
}