import {List, Map, fromJS } from 'immutable';
import axios from 'axios';

const FETCH_SCRAP_PAPER_ITEMS_SUCCESS = 'wbonline/scrapPaper/FETCH_SCRAP_PAPER_ITEMS_SUCCESS';         
const FETCH_SCRAP_PAPER_ITEMS_START = 'wbonline/scrapPaper/FETCH_SCRAP_PAPER_ITEMS_START';      
const FETCH_SCRAP_PAPER_ITEMS_ERROR = 'wbonline/scrapPaper/FETCH_SCRAP_PAPER_ITEMS_ERROR';           
const COMPLETE_SCRAP_PAPER_ITEM_START = 'wbonline/scrapPaper/COMPLETE_SCRAP_PAPER_ITEM_START';          
const COMPLETE_SCRAP_PAPER_ITEM_SUCCESS = 'wbonline/scrapPaper/COMPLETE_SCRAP_PAPER_ITEM_SUCCESS';      
const COMPLETE_SCRAP_PAPER_ITEM_ERROR = 'wbonline/scrapPaper/COMPLETE_SCRAP_PAPER_ITEM_ERROR';       
const COMPLETE_ALL_SCRAP_PAPER_ITEM_START = 'wbonline/scrapPaper/COMPLETE_ALL_SCRAP_PAPER_ITEM_START';          
const COMPLETE_ALL_SCRAP_PAPER_ITEM_SUCCESS = 'wbonline/scrapPaper/COMPLETE_ALL_SCRAP_PAPER_ITEM_SUCCESS';       
const COMPLETE_ALL_SCRAP_PAPER_ITEM_ERROR = 'wbonline/scrapPaper/COMPLETE_ALL_SCRAP_PAPER_ITEM_ERROR';       
const SORT_SCRAP_PAPER_ITEM_START = 'wbonline/scrapPaper/SORT_SCRAP_PAPER_ITEM_START';      
const SORT_SCRAP_PAPER_ITEM_SUCCESS = 'wbonline/scrapPaper/SORT_SCRAP_PAPER_ITEM_SUCCESS';       
const SORT_SCRAP_PAPER_ITEM_ERROR = 'wbonline/scrapPaper/SORT_SCRAP_PAPER_ITEM_ERROR';      
const ADD_SCRAP_PAPER_ITEM_START = 'wbonline/scrapPaper/ADD_SCRAP_PAPER_ITEM_START';
const ADD_SCRAP_PAPER_ITEM_SUCCESS = 'wbonline/scrapPaper/ADD_SCRAP_PAPER_ITEM_SUCCESS';
const ADD_SCRAP_PAPER_ITEM_ERROR = 'wbonline/scrapPaper/ADD_SCRAP_PAPER_ITEM_ERROR';
const REMOVE_SCRAP_PAPER_ITEMS_START = 'wbonline/scrapPaper/REMOVE_SCRAP_PAPER_ITEMS_START';          
const REMOVE_SCRAP_PAPER_ITEMS_SUCCESS = 'wbonline/scrapPaper/REMOVE_SCRAP_PAPER_ITEMS_SUCCESS';       
const REMOVE_SCRAP_PAPER_ITEMS_ERROR = 'wbonline/scrapPaper/REMOVE_SCRAP_PAPER_ITEMS_ERROR';
       

export default (state = fromJS({
    processing: false,
    allComplete: false,
    items: List()
}), action) => {
    let items = state.get('items');
    let payload = action.payload;
    switch(action.type) {
        case FETCH_SCRAP_PAPER_ITEMS_SUCCESS:
            state = state.set('items', payload.items);
            state = state.set('processing', false);
        break;
        case FETCH_SCRAP_PAPER_ITEMS_START:
            state = state.set('processing', true);
        break;
        case FETCH_SCRAP_PAPER_ITEMS_ERROR:
            state = state.set('processing', false);
        break;
        case COMPLETE_SCRAP_PAPER_ITEM_START:
            let index = items.findIndex(item => {
                return item.get('id') === payload.item.get('id');
            });
            if (index > -1) {
                items = items.set(index, payload.item);
            }
            state = state.set('items', items);
        break;
        case COMPLETE_SCRAP_PAPER_ITEM_SUCCESS:
        break;
        case COMPLETE_SCRAP_PAPER_ITEM_ERROR:
        break;
        case COMPLETE_ALL_SCRAP_PAPER_ITEM_START:
            state = state.set('allComplete', payload.allComplete);
            state = state.set('items', payload.items);
        break;
        case COMPLETE_ALL_SCRAP_PAPER_ITEM_SUCCESS:
        break;
        case COMPLETE_ALL_SCRAP_PAPER_ITEM_ERROR:
        break;
        case SORT_SCRAP_PAPER_ITEM_START:
            state = state.set('items', payload.items);
        break;
        case SORT_SCRAP_PAPER_ITEM_SUCCESS:
        break;
        case SORT_SCRAP_PAPER_ITEM_ERROR:
        break;

        case ADD_SCRAP_PAPER_ITEM_START:
        break;
        case ADD_SCRAP_PAPER_ITEM_SUCCESS:
            state = state.set('items', payload.items);
        break;
        case ADD_SCRAP_PAPER_ITEM_ERROR:
        break;

        case REMOVE_SCRAP_PAPER_ITEMS_START:
            state = state.set('items', payload.items);
            state = state.set('allComplete', false);
        break;
        case REMOVE_SCRAP_PAPER_ITEMS_SUCCESS:
        break;
        case REMOVE_SCRAP_PAPER_ITEMS_ERROR:
        break;
    }

    return state;
}


export function loadItems() {
    return (dispatch) => {
        dispatch({type: FETCH_SCRAP_PAPER_ITEMS_START});
        axios.post('/assets/siud/s/scrapPaper.php', [{
            criteria: {
                contactId: WB.user.contact.id
            }
        }])
        .then(function (response) {
            let reloadedItems = fromJS(response.data[0].result.todos);
            dispatch({type: FETCH_SCRAP_PAPER_ITEMS_SUCCESS, payload: {items: reloadedItems }}); 
        })
        .catch(function (error) {
            dispatch({type: FETCH_SCRAP_PAPER_ITEMS_ERROR});
            console.log(error);
        });
    }
    
}

export function completeItem(item, showProcessing = false) {
    return (dispatch) => {
        // item.status = 'complete';
        dispatch({type: COMPLETE_SCRAP_PAPER_ITEM_START, payload: {item: item}});
        axios.post('/assets/siud/u/scrapPaper.php', [{
            criteria: {
            contactId: WB.user.contact.id,
            items: [item.toJS()]
            }
        }])
        .then( (response) => {
            let reloadedItems = fromJS(response.data[0].result.todos);
            dispatch({type: COMPLETE_SCRAP_PAPER_ITEM_SUCCESS, payload: {items: reloadedItems }});
        })
        .catch((error) => {
            dispatch({type: COMPLETE_SCRAP_PAPER_ITEM_ERROR});
            console.log(error);
        });
    }
}

export function completeAllItems(items, allComplete = true) {
    return (dispatch) => {
        let updatedItems = items.map((item) => {
        return (allComplete ? item.set('status','complete'): item.set('status','todo'));
        });
        dispatch({type: COMPLETE_ALL_SCRAP_PAPER_ITEM_START, payload: {items: updatedItems, allComplete: allComplete}});
        axios.post('/assets/siud/u/scrapPaper.php', [{
            criteria: {
                contactId: WB.user.contact.id,
                items: updatedItems.toJS()
            }
        }])
        .then( (response) => {
            let reloadedItems = fromJS(response.data[0].result.todos);
            dispatch({type: COMPLETE_ALL_SCRAP_PAPER_ITEM_SUCCESS, payload: {items: reloadedItems }});
        })
        .catch((error) => {
            dispatch({type: COMPLETE_ALL_SCRAP_PAPER_ITEM_ERROR});
            console.log(error);
        });
    }
}

export function sortItems(items) {
    return (dispatch) => {
        dispatch({type: SORT_SCRAP_PAPER_ITEM_START, payload: {items: items}});
        axios.post('/assets/siud/u/scrapPaper.php', [{
            criteria: {
                contactId: WB.user.contact.id,
                items: items.toJS()
            }
        }])
        .then( (response) => {
            let reloadedItems = fromJS(response.data[0].result.todos);
            dispatch({type: SORT_SCRAP_PAPER_ITEM_SUCCESS, payload: {items: reloadedItems }});
        })
        .catch((error) => {
            dispatch({type: SORT_SCRAP_PAPER_ITEM_ERROR});
            console.log(error);
        });
    }
   
}

export function addNewItem(itemText, items) {
    return (dispatch) => {
        let newItem = Map({id: 0, description: itemText, status: 'todo', seq: 0});
        items = items.unshift(newItem);
        items = items.map((item, index) => item.set('seq', index));
        dispatch({type: ADD_SCRAP_PAPER_ITEM_START});
        axios.post('/assets/siud/u/scrapPaper.php', [{
            criteria: {
                contactId: WB.user.contact.id,
                items: items.toJS()
            }
        }])
        .then( (response) => {
            let reloadedItems = fromJS(response.data[0].result.todos);
            dispatch({type: ADD_SCRAP_PAPER_ITEM_SUCCESS, payload: {items: reloadedItems }});
        })
        .catch((error) => {
            dispatch({type: ADD_SCRAP_PAPER_ITEM_ERROR});
            console.log(error);
        });
    }
}

export function removeItems(items) {
    return (dispatch) => {
        let newItems = List();
        let removeItems = [];
        items.map((item) => {
        if(item.get('status') == 'complete') {
            let updatedItem = item.toJS();
            updatedItem.status = 'removed';
            removeItems.push(updatedItem);
        }
        if(item.get('status') == 'todo') {
            newItems = newItems.push(item);
        }
        });
        dispatch({type: REMOVE_SCRAP_PAPER_ITEMS_START, payload: { items: newItems }});
        axios.post('/assets/siud/u/scrapPaper.php', [{
            criteria: {
                contactId: WB.user.contact.id,
                items: removeItems
            }
        }])
        .then( (response) => {
            let reloadedItems = fromJS(response.data[0].result.todos);
            dispatch({type: REMOVE_SCRAP_PAPER_ITEMS_SUCCESS, payload: {items: reloadedItems }});
        })
        .catch((error) => {
            dispatch({type: REMOVE_SCRAP_PAPER_ITEMS_ERROR});
            console.log(error);
        });
    }
}