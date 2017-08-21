import {List, Map, fromJS } from 'immutable';
import types from "./types";

export default (state = fromJS({
    assignToIsOpen: false,
    categories: List(),
    subCategories: List(),
    activeSubCategories: List(),
    activeCategory: Map(),
    activeSubCategory: Map(),
    events: List(),
    activeEvents: List(),
    processing: false,
    checklistItems: List(),
    newItem: fromJS({
        id: '0', 
        priority: '1', 
        name: '', 
        createdBy: '', 
        assignedBy: '',
        assignTo: '',
        assignToRoles: '',
        schedulePeriodCount: '',
        schedulePeriod: '',
        scheduleEventId: '',
        scheduleBeforeAfter: ''
    }),
    assignToOptions: List(),
    assignItem: Map()
}), action) => {
        let payload = action.payload;
        let subCategories = List();
        let activeSubCategories = List();
        let activeCategory = Map();
        let activeSubCategory = Map();
        let categories = List();
        let checklistItems = state.get('checklistItems');
        let activeEvents = List();
        let events = List();
        let index = -1;
        // let checklistItemTasks = state.get('checklistItemTasks');
        switch(action.type) {
            case types.INIT_CHECKLIST_START:
                state = state.set('processing', true);
                state = state.set('categories', List());
                state = state.set('subCategories', List());
                state = state.set('events', List());

            break;
            case types.INIT_CHECKLIST_SUCCESS:
                state = state.set('processing', false);
                state = state.set('categories', payload.categories);
                state = state.set('events', payload.events);
                state = state.set('subCategories', payload.subCategories);
                state = state.set('assignToOptions', payload.assignToOptions)
            break;
            case types.INIT_CHECKLIST_ERROR:
                state = state.set('processing', false);
            break;
            case types.CHANGE_CHECKLIST_CATEGORY:
                //update categories tab status
                let selectedCategory = payload.selectedCategory;
                categories = state.get('categories').map((category) => {
                    if(category.get('id') == selectedCategory.get('id')) {
                        activeCategory = category.set('status', 'active');
                        return activeCategory;
                    } else {
                        return category.set('status', 'inactive');
                    }
                });

                activeSubCategories = payload.activeSubCategories;
                activeSubCategories = activeSubCategories.map((item)=> item.set('status', 'inactive'));
                
                events = state.get('events');
                activeEvents = events.filter(item => item.get('clec_category') == activeCategory.get('id'));

                state = state.set('activeEvents', activeEvents);
                state = state.set('checklistItems', List());
                state = state.set('categories', categories);
                state = state.set('activeCategory', activeCategory);
                state = state.set('activeSubCategories', activeSubCategories);
            break;
            case types.CHANGE_CHECKLIST_SUB_CATEGORY:
                // change status of active subcategories 
                let selectedSubCategory = payload.selectedSubCategory;
                activeSubCategories = state.get('activeSubCategories');
                activeSubCategories =  activeSubCategories.map((item) => {
                    if(item.get('id') == selectedSubCategory.get('id')) {
                        activeSubCategory = item.set('status', 'active');
                        return activeSubCategory;
                    } else {
                        return item.set('status', 'inactive');
                    }
                });
                let newItem = fromJS({
                    id: '0', 
                    priority: '1', 
                    name: '', 
                    createdBy: '', 
                    assignedBy: '',
                    assignTo: '',
                    schedulePeriodCount: '',
                    schedulePeriod: '',
                    scheduleEventId: '',
                    scheduleBeforeAfter: ''
                });
                state = state.set('newItem', newItem);
                state = state.set('checklistItems', List());
                state = state.set('activeSubCategories', activeSubCategories);
                state = state.set('activeSubCategory', activeSubCategory);
                state = state.set('activeSubCategory', activeSubCategory);
            break;
            case types.LOAD_CHECKLIST_ITEM_START:
                state = state.set('processing', true);
            break;
            case types.LOAD_CHECKLIST_ITEM_SUCCESS:
                state = state.set('checklistItems', payload.checklistItems);
                state = state.set('processing', false);
            break;
            case types.LOAD_CHECKLIST_ITEM_ERROR:
                state = state.set('processing', false);
            break;
            case types.UPDATE_CHECKLIST_ITEM_START:
                state = state.set('processing', true);
            break;
            case types.UPDATE_CHECKLIST_ITEM_SUCCESS:
                state = state.set('processing', false);
            break;
            case types.UPDATE_CHECKLIST_ITEM_ERROR:
                state = state.set('processing', false);
            break;
            case types.UPDATE_CHECKLIST_NEW_ITEM:
                state = state.set('newItem', payload.updatedNewItem);
            break;
            case types.ADD_CHECKLIST_NEW_ITEM_START:
                state = state.set('assignToIsOpen', false);
                state = state.set('processing', true);
            break;
            case types.ADD_CHECKLIST_NEW_ITEM_SUCCESS:
                state = state.set('processing', false);
            break;
            case types.ADD_CHECKLIST_NEW_ITEM_ERROR:
                state = state.set('processing', false);
            break;
            case types.ADD_CHECKLIST_NEW_SUB_CATEGORY_START:
                state = state.set('processing', true);
            break;
            case types.ADD_CHECKLIST_NEW_SUB_CATEGORY_SUCCESS:
                activeSubCategories = state.get('activeSubCategories');
                let newSubCategory = payload.newSubCategory.set('status', 'inactive');
                activeSubCategories = activeSubCategories.push(newSubCategory);

                subCategories = state.get('subCategories');
                subCategories = subCategories.push(newSubCategory);
                
                state = state.set('activeSubCategories', activeSubCategories);
                state = state.set('subCategories', subCategories);
                state = state.set('processing', false);
            break;
            case types.ADD_CHECKLIST_NEW_SUB_CATEGORY_ERROR:
                state = state.set('processing', false);
            break;
            case types.CHECK_CHECKLIST_ALL_ITEMS:
                state = state.set('checklistItems', payload.checkedItems);
            break;
            case types.CHECK_CHECKLIST_ITEM:
                index = checklistItems.findIndex(item => {
                    return item.get('id') === payload.checklistItem.get('id');
                });
                if (index > -1) {
                    checklistItems = checklistItems.set(index, payload.checklistItem);
                }
                state = state.set('checklistItems', checklistItems);
            break;
            case types.REMOVE_CHECKLIST_ITEMS_START:
                state = state.set('processing', true);
            break;
            case types.REMOVE_CHECKLIST_ITEMS_SUCCESS:
                state = state.set('processing', false);
                let deletedItemIds = payload.deletedItemIds;
                let newItemList = List();
                checklistItems.map((item) => {
                    if(deletedItemIds.indexOf(item.get('id')) <0) {
                        newItemList = newItemList.push(item);
                    }
                });
                state = state.set('checklistItems', newItemList);
                state = state.set('processing', false);
            break;
            case types.REMOVE_CHECKLIST_ITEMS_ERROR:
                state = state.set('processing', false);
            break;
            case types.ASSIGN_CHECKLIST_ITEM_START:
                state = state.set('assignToIsOpen', false);
                state = state.set('processing', true);
            break;
            case types.ASSIGN_CHECKLIST_ITEM_SUCCESS:
                index = checklistItems.findIndex(item => {
                    return item.get('id') === payload.checklistItem.get('id');
                });

                if (index > -1) {
                    checklistItems = checklistItems.set(index, payload.checklistItem);
                }
                state = state.set('checklistItems', checklistItems);
                state = state.set('processing', false);
            break;
            case types.ASSIGN_CHECKLIST_ITEM_ERROR:
                state = state.set('processing', false);
            break;
            case types.LOAD_CHECKLIST_ITEM_TASK_START:
            case types.ADD_CHECKLIST_ITEM_TASK_START:
                state = state.set('processing', true);
            break;
            case types.LOAD_CHECKLIST_ITEM_TASK_SUCCESS:
            case types.ADD_CHECKLIST_ITEM_TASK_SUCCESS:
                state = state.set('processing', false);
                index = checklistItems.findIndex(item => {
                    return item.get('id') === payload.checklistItem.get('id');
                });

                if (index > -1) {
                    checklistItems = checklistItems.set(index, payload.checklistItem);
                }
                state = state.set('checklistItems', checklistItems);
                // state = state.set('checklistItems', checklistItems.set('tasks', payload.tasks));
                // checklistItemTasks = checklistItemTasks.set(payload.checklistItemId, payload.tasks);
                // state = state.set('checklistItemTasks', checklistItemTasks);
            break;
            case types.LOAD_CHECKLIST_ITEM_TASK_ERROR:
            case types.ADD_CHECKLIST_ITEM_TASK_ERROR:
                state = state.set('processing', false);
            break;
            case types.UPDATE_CHECKLIST_SUB_TASK_ITEM_START:
                index = checklistItems.findIndex(item => {
                    return item.get('id') === payload.checklistSubTaskItem.get('clItemId');
                });
                if (index > -1) {
                    checklistItems = checklistItems.update(index, (item)=>{
                        let subTasks = item.get('tasks');
                        subTasks = subTasks.map( task => {
                            if(task.get('id') == payload.checklistSubTaskItem.get('id')) {
                                task = payload.checklistSubTaskItem;
                            }
                            return task;
                        });
                        return item.set('tasks', subTasks);
                    });
                    state = state.set('checklistItems', checklistItems);
                }
                state = state.set('processing', true);
            break;
            case types.UPDATE_CHECKLIST_SUB_TASK_ITEM_SUCCESS:
                state = state.set('processing', false);
            break;
            case types.UPDATE_CHECKLIST_SUB_TASK_ITEM_ERROR:
                state = state.set('processing', false);
            break;
            case types.SORT_CHECKLIST_SUB_TASK_ITEMS_START:
                index = checklistItems.findIndex(item => {
                    return item.get('id') === payload.checklistItem.get('id');
                });
                if (index > -1) {
                    checklistItems = checklistItems.set(index, payload.checklistItem);
                }
                state = state.set('checklistItems', checklistItems);
            break;
            case types.SORT_CHECKLIST_SUB_TASK_ITEMS_SUCCESS:
            break;
            case types.SORT_CHECKLIST_SUB_TASK_ITEMS_ERROR:
            break;

            case types.REMOVE_CHECKLIST_SUB_TASK_ITEM_START:
                state = state.set('processing', true);
            break;
            case types.REMOVE_CHECKLIST_SUB_TASK_ITEM_SUCCESS:
                index = checklistItems.findIndex(item => {
                    return item.get('id') === payload.checklistSubTaskItem.get('clItemId');
                });
                if (index > -1) {
                    checklistItems = checklistItems.update(index, (item)=>{
                        let newSubTasks = List();
                        item.get('tasks').map( task => {
                            if(task.get('id') != payload.checklistSubTaskItem.get('id')) {
                                newSubTasks = newSubTasks.push(task);
                            }
                        });
                        return item.set('tasks', newSubTasks);
                    });
                    state = state.set('checklistItems', checklistItems);
                }
                state = state.set('processing', false);
            break;
            case types.REMOVE_CHECKLIST_SUB_TASK_ITEM_ERROR:
                state = state.set('processing', false);
            break;

            case types.SORT_CHECKLIST_ITEMS_START:
                state = state.set('processing', true);
                state = state.set('checklistItems', payload.checklistItems);
            break;
            case types.SORT_CHECKLIST_ITEMS_SUCCESS:
            case types.SORT_CHECKLIST_ITEMS_ERROR:
                state = state.set('processing', false);
            break;

            case types.CHECK_CHECKLIST_SUB_TASK_ITEM:
                 index = checklistItems.findIndex(item => {
                     return item.get('id') === payload.item.get('id');
                 });
                 if (index > -1) {
                     checklistItems = checklistItems.set(index, payload.item);
                 }
                 state = state.set('checklistItems', checklistItems);
            break;

            case types.REMOVE_CHECKLIST_SUB_TASK_ITEMS_START:
                state = state.set('processing', true);
            break;
            case types.REMOVE_CHECKLIST_SUB_TASK_ITEMS_SUCCESS:
                state = state.set('processing', false);
                let deletedSubTaskItemIds = payload.deletedSubTaskItemIds;
                let newCheckListItems = List();
                let newTaskList = List();
                checklistItems.map((item) => {
                    if(item.has('tasks')){
                        newTaskList = List();
                        item.get('tasks').map(subTaskItem => {
                            if(deletedSubTaskItemIds.indexOf(subTaskItem.get('id')) <0) {
                                newTaskList = newTaskList.push(subTaskItem);
                            }
                        });
                        newCheckListItems = newCheckListItems.push(item.set('tasks', newTaskList));
                    } else {
                        newCheckListItems = newCheckListItems.push(item);
                    }
                });
                state = state.set('checklistItems', newCheckListItems);
                state = state.set('processing', false);
            break;
            case types.REMOVE_CHECKLIST_SUB_TASK_ITEMS_ERROR:
                state = state.set('processing', false);
            break;

    }
    return  state;
}