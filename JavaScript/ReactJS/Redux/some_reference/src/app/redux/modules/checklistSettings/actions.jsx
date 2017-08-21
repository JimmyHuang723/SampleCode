import {List, Map, fromJS } from 'immutable';
import axios from 'axios';
import types from "./types";

export function loadChecklistEvents() {

}

export function loadChecklistGroups() {

}

export function initChecklistSettings() {
    return (dispatch) => {
        let result = [];
        dispatch({type: types.INIT_CHECKLIST_START});
        axios.post('/assets/siud/s/setting/checklist.php', [{
            criteria:{
                member: WB.user.contact,
                organisation: [WB.env.currentOrganisation.id]
            }
        }])
        .then(function (response) {
            result = response.data[0].result;
            let categories = fromJS(result['checklistCategories']);
            let events = fromJS(result['checklistEvents']);
            let subCategories = fromJS(result['checklistSubCategories']);
            let assignToOptions = fromJS(result['checklistAssignToOptions']);
            dispatch({
                type: types.INIT_CHECKLIST_SUCCESS, 
                payload: {
                    categories: categories, 
                    events: events, 
                    subCategories: subCategories,
                    assignToOptions: assignToOptions
                }
            }); 
            let selectedCategory = categories.first();
            let activeSubCategories = subCategories.filter((item) => {
                return (item.get('parentId') == selectedCategory.get('id') && 
                (item.get('orgId') == WB.env.currentOrganisation.id || item.get('orgId') == '0'))
            });
            // let selectedSubCategory = activeSubCategories.first();
            dispatch(changeCategory(selectedCategory, activeSubCategories));
            // dispatch(changeSubCategory(selectedSubCategory));
            // dispatch(loadChecklistItems(selectedSubCategory));
        })
        .catch(function (error) {
            dispatch({type: types.INIT_CHECKLIST_ERROR});
            console.log(error);
        });
    }
}

// export function loadSubCategories(selectedCategory) {
//     return (dispatch) => {
//         let result = [];
//         dispatch({type: types.CHECKLIST_LOAD_SUB_CATEGORY_START});
//         axios.post('/assets/siud/s/setting/checklistSubCategory.php', [{
//             criteria:{
//                 categoryId: selectedCategory.get('id')
//             }
//         }])
//         .then(function (response) {
//             result = response.data[0].result;
//             let subCategories = fromJS(result['data']);
//             dispatch({ type: types.CHECKLIST_LOAD_SUB_CATEGORY_SUCCESS, payload: { subCategories: subCategories }}); 
//             // let selectedCategory = categories.first();
//             // let activeSubCategories = subCategories.filter( item => item.get('parentId') == selectedCategory.get('id'));
//             // // let selectedSubCategory = activeSubCategories.first();
//             // dispatch(changeCategory(selectedCategory, activeSubCategories));
//             // // dispatch(changeSubCategory(selectedSubCategory));
//             // // dispatch(loadChecklistItems(selectedSubCategory));
//         })
//         .catch(function (error) {
//             dispatch({type: types.CHECKLIST_LOAD_SUB_CATEGORY_ERROR});
//             console.log(error);
//         });
//     }
// }

export function changeCategory(selectedCategory, activeSubCategories) {
    return (dispatch) => {
        dispatch({type: types.CHANGE_CHECKLIST_CATEGORY, payload: {selectedCategory: selectedCategory, activeSubCategories: activeSubCategories}});
        if(activeSubCategories.size > 0) {
            dispatch(changeSubCategory(activeSubCategories.first()));
        }
    }
}

export function changeSubCategory(selectedSubCategory) {
    return (dispatch) => {
        dispatch({type: types.CHANGE_CHECKLIST_SUB_CATEGORY, payload: {selectedSubCategory: selectedSubCategory}});
        dispatch(loadChecklistItems(selectedSubCategory));
    }
}

export function loadChecklistItems(selectedSubCategory) {
     return (dispatch) => {
        let result = [];
        dispatch({type: types.LOAD_CHECKLIST_ITEM_START});
        axios.post('/assets/siud/s/setting/checklistItem.php', [{
            criteria: {
                member: WB.user.contact,
                organisation: [WB.env.currentOrganisation.id],
                categoryId: selectedSubCategory.get('id')
            }
        }])
        .then(function (response) {
            result = response.data[0].result;
            let checklistItems = fromJS(result['data']);
            dispatch({
                type: types.LOAD_CHECKLIST_ITEM_SUCCESS, 
                payload: {
                    checklistItems: checklistItems, 
                }
            }); 
        })
        .catch(function (error) {
            dispatch({type: types.LOAD_CHECKLIST_ITEM_ERROR});
            console.log(error);
        });
    }
}

export function updateChecklistItem(checklistItem, field, value) {
    return (dispatch) => {
        let result = [];
        dispatch({type: types.UPDATE_CHECKLIST_ITEM_START});
        axios.post('/assets/siud/u/setting/checklistItem.php', [{
            criteria: {
                checklistItemId: checklistItem.get('id'),
                updatedField: field,
                updatedValue: value,
                member: WB.user.contact
            }
        }])
        .then(function (response) {
            // result = response.data[0].result;
            // let checklistItems = fromJS(result['data']);
            dispatch({type: types.UPDATE_CHECKLIST_ITEM_SUCCESS, payload: {}}); 
        })
        .catch(function (error) {
            dispatch({type: types.UPDATE_CHECKLIST_ITEM_ERROR});
            console.log(error);
        });
    }
}

export function updateNewItem(updatedNewItem) {
    return ({type: types.UPDATE_CHECKLIST_NEW_ITEM, payload: {updatedNewItem: updatedNewItem }});
}

export function addNewItem(newItem, selectedAssignToIds, activeSubCategory) {
    let item = newItem.toJS();
    let activeSubCategoryId = activeSubCategory.get('id');
    return (dispatch) => {
        let result = [];
        dispatch({type: types.ADD_CHECKLIST_NEW_ITEM_START});
        axios.post('/assets/siud/i/setting/checklistItem.php', [{
            criteria: {
                newItem: item,
                assignToRoleIds: selectedAssignToIds,
                activeSubCategoryId: activeSubCategoryId,
                member: WB.user.contact,
                organisation: [WB.env.currentOrganisation.id]
            }
        }])
        .then(function (response) {
            dispatch({type: types.ADD_CHECKLIST_NEW_ITEM_SUCCESS}); 
            dispatch(changeSubCategory(activeSubCategory));
        })
        .catch(function (error) {
            dispatch({type: types.ADD_CHECKLIST_NEW_ITEM_ERROR});
            console.log(error);
        });
    }
}

export function addNewSubCategory(newSubCatName, activeCategory) {
    let activeCategoryId = activeCategory.get('id');
    return (dispatch) => {
        let result = [];
        dispatch({type: types.ADD_CHECKLIST_NEW_SUB_CATEGORY_START});
        axios.post('/assets/siud/i/setting/checklistSubCategory.php', [{
            criteria: {
                activeCategoryId: activeCategoryId,
                subCategoryName: newSubCatName,
                member: WB.user.contact,
                organisation: [WB.env.currentOrganisation.id]
            }
        }])
        .then(function (response) {
            result = response.data[0].result;
            let newSubCategory = fromJS(result['newSubCategory']);
            dispatch({type: types.ADD_CHECKLIST_NEW_SUB_CATEGORY_SUCCESS, payload: {newSubCategory: newSubCategory}}); 
        })
        .catch(function (error) {
            dispatch({type: types.ADD_CHECKLIST_NEW_SUB_CATEGORY_ERROR});
            console.log(error);
        });
    }
}

export function checkAllItems(checkedItems) {
    return {
        type: types.CHECK_CHECKLIST_ALL_ITEMS,
        payload: {checkedItems: checkedItems}
    }
}

export function checkItem(checklistItem) {
    return {
        type: types.CHECK_CHECKLIST_ITEM,
        payload: { checklistItem: checklistItem }
    }
}

export function removeChecklistItems(items, subTaskItems) {
    return {
        type: types.REMOVE_CHECKLIST_ITEMS_REQUEST,
        payload: {items: items, subTaskItems: subTaskItems}
    }
}

// export function removeChecklistItems(items, activeSubCategory) {
//     let ids = [];
//     items.map((item) => {
//         ids.push(item.get('id'));
//     });
//     return (dispatch) => {
//         let data = [];
//         dispatch({type: types.REMOVE_CHECKLIST_ITEMS_START});
//         axios.post('/assets/siud/d/setting/checklistItems.php', [{
//             criteria:{
//                 member: WB.user.contact,
//                 ids: ids
//             }
//         }])
//         .then(function (response) {
//             dispatch({type: types.REMOVE_CHECKLIST_ITEMS_SUCCESS, payload: {deletedItemIds: ids}}); 
//             // dispatch(changeSubCategory(activeSubCategory));
//         })
//         .catch(function (error) {
//             dispatch({type: types.REMOVE_CHECKLIST_ITEMS_ERROR});
//             console.log(error);
//         });
//     }
// }

export function assignChecklistItem(itemToAssign, selectedAssignToIds, selectedSubCategory) {
    let roleIds = selectedAssignToIds.join();
    let checklistItem = itemToAssign.set('assignTo', roleIds);
    let initials = WB.user.contact.nameFirst.substring(0,1) + WB.user.contact.nameLast.substring(0,1);
    checklistItem = checklistItem.set('assignedBy', initials);
     return (dispatch) => {
        let data = [];
        dispatch({type: types.ASSIGN_CHECKLIST_ITEM_START});
        axios.post('/assets/siud/u/setting/assignChecklistItem.php', [{
            criteria:{
                member: WB.user.contact,
                clItemId: itemToAssign.get('id'),
                roleIds: selectedAssignToIds
            }
        }])
        .then(function (response) {
            dispatch({type: types.ASSIGN_CHECKLIST_ITEM_SUCCESS, payload: {checklistItem: checklistItem}}); 
            // dispatch(loadChecklistItems(selectedSubCategory));
        })
        .catch(function (error) {
            dispatch({type: types.ASSIGN_CHECKLIST_ITEM_ERROR});
            console.log(error);
        });
    }
}



export function locaChecklistItemTasks(checklistItem) {
    let checklistItemId = checklistItem.get('id');
    return (dispatch) => {
        let result = [];
        dispatch({type: types.LOAD_CHECKLIST_ITEM_TASK_START});
        axios.post('/assets/siud/s/setting/checklistTasks.php', [{
            criteria:{
                member: WB.user.contact,
                organisation: [WB.env.currentOrganisation.id],
                itemId: checklistItem.get('id')
            }
        }])
        .then(function (response) {
            result = response.data[0].result;
            let tasks = fromJS(result['tasks']);
            checklistItem = checklistItem.set('tasks', tasks);
            dispatch({
                type: types.LOAD_CHECKLIST_ITEM_TASK_SUCCESS, 
                payload: {checklistItem: checklistItem}
            }); 
        })
        .catch(function (error) {
            dispatch({type: types.LOAD_CHECKLIST_ITEM_TASK_ERROR});
            console.log(error);
        });
    }
}

export function addNewTask(checklistItem, newTask) {
    let checklistItemId = checklistItem.get('id');
    return (dispatch) => {
        let result = [];
        dispatch({type: types.ADD_CHECKLIST_ITEM_TASK_START});
        axios.post('/assets/siud/i/setting/checklistTask.php', [{
            criteria:{
                member: WB.user.contact,
                organisation: [WB.env.currentOrganisation.id],
                checklistItemId: checklistItem.get('id'),
                newTask: newTask
            }
        }])
        .then(function (response) {
            result = response.data[0].result;
            let tasks = fromJS(result['tasks']);
            checklistItem = checklistItem.set('tasks', tasks);
            dispatch({
                type: types.ADD_CHECKLIST_ITEM_TASK_SUCCESS, 
                payload: {checklistItem: checklistItem}
            }); 
        })
        .catch(function (error) {
            dispatch({type: types.ADD_CHECKLIST_ITEM_TASK_ERROR});
            console.log(error);
        });
    }
}

export function updateChecklistSubTaskItem(checklistSubTaskItem, field, value) {
    checklistSubTaskItem = checklistSubTaskItem.set(field, value);
    return (dispatch) => {
        let result = [];
        dispatch({type: types.UPDATE_CHECKLIST_SUB_TASK_ITEM_START, payload: {checklistSubTaskItem: checklistSubTaskItem}});
        axios.post('/assets/siud/u/setting/checklistSubTaskItem.php', [{
            criteria: {
                checklistSubTaskId: checklistSubTaskItem.get('id'),
                updatedField: field,
                updatedValue: value,
                member: WB.user.contact
            }
        }])
        .then(function (response) {
            dispatch({type: types.UPDATE_CHECKLIST_SUB_TASK_ITEM_SUCCESS, payload: {}}); 
        })
        .catch(function (error) {
            dispatch({type: types.UPDATE_CHECKLIST_SUB_TASK_ITEM_ERROR});
            console.log(error);
        });
    }
}

export function sortSubTaskItems(checklistItem) {
    let tasks = checklistItem.get('tasks');
    return (dispatch) => {
        dispatch({type: types.SORT_CHECKLIST_SUB_TASK_ITEMS_START, payload: {checklistItem: checklistItem}});
        axios.post('/assets/siud/u/setting/checklistSubTaskItemOrder.php', [{
            criteria: {
                contactId: WB.user.contact.id,
                tasks: tasks.toJS()
            }
        }])
        .then( (response) => {
            dispatch({type: types.SORT_CHECKLIST_SUB_TASK_ITEMS_SUCCESS, payload: {checklistItem: checklistItem}});
        })
        .catch((error) => {
            dispatch({type: types.SORT_CHECKLIST_SUB_TASK_ITEMS_ERROR});
            console.log(error);
        });
    }
   
}

export function deleteSubTaskItem(subTaskItem) {
    let ids = [subTaskItem.get('id')];
    return (dispatch) => {
        let data = [];
        dispatch({type: types.REMOVE_CHECKLIST_SUB_TASK_ITEM_START});
        axios.post('/assets/siud/d/setting/checklistSubTaskItems.php', [{
            criteria:{
                member: WB.user.contact,
                ids: ids
            }
        }])
        .then(function (response) {
            dispatch({type: types.REMOVE_CHECKLIST_SUB_TASK_ITEM_SUCCESS, payload: {checklistSubTaskItem: subTaskItem}}); 
        })
        .catch(function (error) {
            dispatch({type: types.REMOVE_CHECKLIST_SUB_TASK_ITEM_ERROR});
            console.log(error);
        });
    }
}

export function sortChecklistItems(checklistItems, activeSubCategory) {
    return (dispatch) => {
        dispatch({type: types.SORT_CHECKLIST_ITEMS_START, payload: {checklistItems: checklistItems}});
        axios.post('/assets/siud/u/setting/checklistItemOrder.php', [{
            criteria: {
                contactId: WB.user.contact.id,
                checklistItems: checklistItems.toJS()
            }
        }])
        .then( (response) => {
            dispatch({type: types.SORT_CHECKLIST_ITEMS_SUCCESS, payload: {checklistItems: checklistItems}});
            // dispatch(changeSubCategory(activeSubCategory));
        })
        .catch((error) => {
            dispatch({type: types.SORT_CHECKLIST_ITEMS_ERROR});
            console.log(error);
        });
    }
}

export function checkSubTaskItem(item) {
     return {
         type: types.CHECK_CHECKLIST_SUB_TASK_ITEM,
         payload: { item: item }
     }
 }

//  export function removeChecklistSubTaskItems(items, activeSubCategory) {
//      let ids = [];
//      items.map((item) => {
//          ids.push(item.get('id'));
//      });
//      return (dispatch) => {
//          let data = [];
//          dispatch({type: types.REMOVE_CHECKLIST_SUB_TASK_ITEMS_START});
//          axios.post('/assets/siud/d/setting/checklistSubTaskItems.php', [{
//              criteria:{
//                  member: WB.user.contact,
//                  ids: ids
//              }
//          }])
//          .then(function (response) {
//              dispatch({type: types.REMOVE_CHECKLIST_SUB_TASK_ITEMS_SUCCESS, payload: {deletedSubTaskItemIds: ids}}); 
//             //  dispatch(changeSubCategory(activeSubCategory));
//          })
//          .catch(function (error) {
//              dispatch({type: types.REMOVE_CHECKLIST_SUB_TASK_ITEMS_ERROR});
//              console.log(error);
//          });
//      }
//  }