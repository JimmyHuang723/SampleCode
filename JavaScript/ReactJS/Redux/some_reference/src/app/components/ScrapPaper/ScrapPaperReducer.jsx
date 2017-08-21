import {List, Map, fromJS } from 'immutable';

export default (state = fromJS({
    processing: false,
    allComplete: false,
    items: List()
}), action) => {
    let items = state.get('items');
    let payload = action.payload;
    switch(action.type) {
        case 'FETCH_SCRAP_PAPER_ITEMS_SUCCESS':
            state = state.set('items', payload.items);
            state = state.set('processing', false);
        break;
        case 'FETCH_SCRAP_PAPER_ITEMS_START':
            state = state.set('processing', true);
        break;
        case 'FETCH_SCRAP_PAPER_ITEMS_ERROR':
            state = state.set('processing', false);
        break;
        case 'COMPLETE_SCRAP_PAPER_ITEM_START':
            let index = items.findIndex(item => {
                return item.get('id') === payload.item.get('id');
            });
            if (index > -1) {
                items = items.set(index, payload.item);
            }
            state = state.set('items', items);
        break;
        case 'COMPLETE_SCRAP_PAPER_ITEM_SUCCESS':
        break;
        case 'COMPLETE_SCRAP_PAPER_ITEM_ERROR':
        break;
        case 'COMPLETE_ALL_SCRAP_PAPER_ITEM_START':
            state = state.set('allComplete', payload.allComplete);
            state = state.set('items', payload.items);
        break;
        case 'COMPLETE_ALL_SCRAP_PAPER_ITEM_SUCCESS':
        break;
        case 'COMPLETE_ALL_SCRAP_PAPER_ITEM_ERROR':
        break;
        case 'SORT_SCRAP_PAPER_ITEM_START':
            state = state.set('items', payload.items);
        break;
        case 'SORT_SCRAP_PAPER_ITEM_SUCCESS':
        break;
        case 'SORT_SCRAP_PAPER_ITEM_ERROR':
        break;

        case 'ADD_SCRAP_PAPER_ITEM_START':
        break;
        case 'ADD_SCRAP_PAPER_ITEM_SUCCESS':
            state = state.set('items', payload.items);
        break;
        case 'ADD_SCRAP_PAPER_ITEM_ERROR':
        break;

        case 'REMOVE_SCRAP_PAPER_ITEMS_START':
            state = state.set('items', payload.items);
            state = state.set('allComplete', false);
        break;
        case 'REMOVE_SCRAP_PAPER_ITEMS_SUCCESS':
        break;
        case 'REMOVE_SCRAP_PAPER_ITEMS_ERROR':
        break;
    }

    return state;
}