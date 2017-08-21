import {List, Map, fromJS } from 'immutable';
import axios from 'axios';

export function loadItems() {
    return (dispatch) => {
        dispatch({type: 'FETCH_SCRAP_PAPER_ITEMS_START'});
        axios.post('/assets/siud/s/scrapPaper.php', [{
            criteria: {
                contactId: WB.user.contact.id
            }
        }])
        .then(function (response) {
            let reloadedItems = fromJS(response.data[0].result.todos);
            dispatch({type: 'FETCH_SCRAP_PAPER_ITEMS_SUCCESS', payload: {items: reloadedItems }}); 
        })
        .catch(function (error) {
            dispatch({type: 'FETCH_SCRAP_PAPER_ITEMS_ERROR'});
            console.log(error);
        });
    }
    
}

export function completeItem(item, showProcessing = false) {
    return (dispatch) => {
        // item.status = 'complete';
        dispatch({type: 'COMPLETE_SCRAP_PAPER_ITEM_START', payload: {item: item}});
        axios.post('/assets/siud/u/scrapPaper.php', [{
            criteria: {
            contactId: WB.user.contact.id,
            items: [item.toJS()]
            }
        }])
        .then( (response) => {
            let reloadedItems = fromJS(response.data[0].result.todos);
            dispatch({type: 'COMPLETE_SCRAP_PAPER_ITEM_SUCCESS', payload: {items: reloadedItems }});
        })
        .catch((error) => {
            dispatch({type: 'COMPLETE_SCRAP_PAPER_ITEM_ERROR'});
            console.log(error);
        });
    }
}

export function completeAllItems(items, allComplete = true) {
    return (dispatch) => {
        let updatedItems = items.map((item) => {
        return (allComplete ? item.set('status','complete'): item.set('status','todo'));
        });
        dispatch({type: 'COMPLETE_ALL_SCRAP_PAPER_ITEM_START', payload: {items: updatedItems, allComplete: allComplete}});
        axios.post('/assets/siud/u/scrapPaper.php', [{
            criteria: {
                contactId: WB.user.contact.id,
                items: updatedItems.toJS()
            }
        }])
        .then( (response) => {
            let reloadedItems = fromJS(response.data[0].result.todos);
            dispatch({type: 'COMPLETE_ALL_SCRAP_PAPER_ITEM__SUCCESS', payload: {items: reloadedItems }});
        })
        .catch((error) => {
            dispatch({type: 'COMPLETE_ALL_SCRAP_PAPER_ITEM_ERROR'});
            console.log(error);
        });
    }
}

export function sortItems(items) {
    return (dispatch) => {
        dispatch({type: 'SORT_SCRAP_PAPER_ITEM_START', payload: {items: items}});
        axios.post('/assets/siud/u/scrapPaper.php', [{
            criteria: {
                contactId: WB.user.contact.id,
                items: items.toJS()
            }
        }])
        .then( (response) => {
            let reloadedItems = fromJS(response.data[0].result.todos);
            dispatch({type: 'SORT_SCRAP_PAPER_ITEM_SUCCESS', payload: {items: reloadedItems }});
        })
        .catch((error) => {
            dispatch({type: 'SORT_SCRAP_PAPER_ITEM_ERROR'});
            console.log(error);
        });
    }
   
}

export function addNewItem(itemText, items) {
    return (dispatch) => {
        let newItem = Map({id: 0, description: itemText, status: 'todo', seq: 0});
        items = items.unshift(newItem);
        items = items.map((item, index) => item.set('seq', index));
        dispatch({type: 'ADD_SCRAP_PAPER_ITEM_START'});
        axios.post('/assets/siud/u/scrapPaper.php', [{
            criteria: {
                contactId: WB.user.contact.id,
                items: items.toJS()
            }
        }])
        .then( (response) => {
            let reloadedItems = fromJS(response.data[0].result.todos);
            dispatch({type: 'ADD_SCRAP_PAPER_ITEM_SUCCESS', payload: {items: reloadedItems }});
        })
        .catch((error) => {
            dispatch({type: 'ADD_SCRAP_PAPER_ITEM_ERROR'});
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
        dispatch({type: 'REMOVE_SCRAP_PAPER_ITEMS_START', payload: { items: newItems }});
        axios.post('/assets/siud/u/scrapPaper.php', [{
            criteria: {
                contactId: WB.user.contact.id,
                items: removeItems
            }
        }])
        .then( (response) => {
            let reloadedItems = fromJS(response.data[0].result.todos);
            dispatch({type: 'REMOVE_SCRAP_PAPER_ITEMS_SUCCESS', payload: {items: reloadedItems }});
        })
        .catch((error) => {
            dispatch({type: 'REMOVE_SCRAP_PAPER_ITEMS_ERROR'});
            console.log(error);
        });
    }
}