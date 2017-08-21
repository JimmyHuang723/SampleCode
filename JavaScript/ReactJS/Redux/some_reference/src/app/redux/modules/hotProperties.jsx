import {List, Map, fromJS } from 'immutable';
import axios from 'axios';

const FETCH_HOT_PROPERTIES_START= 'wbonline/hotProperties/FETCH_HOT_PROPERTIES_START';
const FETCH_HOT_PROPERTIES_SUCCESS= 'wbonline/hotProperties/FETCH_HOT_PROPERTIES_SUCCESS';
const FETCH_HOT_PROPERTIES_ERROR= 'wbonline/hotProperties/FETCH_HOT_PROPERTIES_ERROR';
const SORT_HOT_PROPERTIES= 'wbonline/hotProperties/SORT_HOT_PROPERTIES';
const CHECK_ALL_HOT_PROPERTIES= 'wbonline/hotProperties/CHECK_ALL_HOT_PROPERTIES';
const CHECK_HOT_PROPERTY= 'wbonline/hotProperties/CHECK_HOT_PROPERTY';
const REMOVE_HOT_PROPERTIES_START= 'wbonline/hotProperties/REMOVE_HOT_PROPERTIES_START';
const REMOVE_HOT_PROPERTIES_SUCCESS= 'wbonline/hotProperties/REMOVE_HOT_PROPERTIES_SUCCESS';
const REMOVE_HOT_PROPERTIES_ERROR= 'wbonline/hotProperties/REMOVE_HOT_PROPERTIES_ERROR';


export default (state = fromJS({
        data: List(),
        processing: false,
    }), action) => {
        let index = 0;
        let items = state.get('data');
        let payload = action.payload;
        switch(action.type) {
            case FETCH_HOT_PROPERTIES_START:
                state = state.set('data', List());
                state = state.set('processing', true);
            break;
            case FETCH_HOT_PROPERTIES_SUCCESS:
                state = state.set('processing', false);
                state = state.set('data', payload.data);
            break;
            case FETCH_HOT_PROPERTIES_ERROR:
                state = state.set('processing', false);
            break;
            case SORT_HOT_PROPERTIES:
                state = state.set('data', payload.sortedItems);
            break;
            case CHECK_ALL_HOT_PROPERTIES:
                state = state.set('data', payload.checkedItems);
            break;
            case CHECK_HOT_PROPERTY:
                index = items.findIndex(item => {
                    return item.get('id') === payload.item.get('id');
                });
                if (index > -1) {
                    items = items.set(index, payload.item);
                }
                state = state.set('data', items);
            break;
            case REMOVE_HOT_PROPERTIES_START:
                state = state.set('processing', true);
            break;
            case REMOVE_HOT_PROPERTIES_SUCCESS:
                let deletedItemIds = payload.deletedItemIds;
                let newItemList = List();
                items.map((item) => {
                    if(deletedItemIds.indexOf(item.get('id')) <0) {
                        newItemList = newItemList.push(item);
                    }
                });
                state = state.set('processing', false);
                state = state.set('data', newItemList);
            break;
            case REMOVE_HOT_PROPERTIES_ERROR:
                state = state.set('processing', false);
            break;
    }
    return  state;
}


export function loadHotProperties() {
    return (dispatch) => {
        let data = [];
        dispatch({type: FETCH_HOT_PROPERTIES_START});
        axios.post('/assets/siud/s/dashboard/dashboardResults/hotProperties.php', [{
            criteria:{
                member: WB.user.contact
            }
        }])
        .then(function (response) {
            data = response.data[0].result.data;
            dispatch({type: FETCH_HOT_PROPERTIES_SUCCESS, payload: {data: fromJS(data)}}); 
        })
        .catch(function (error) {
            dispatch({type: FETCH_HOT_PROPERTIES_ERROR});
            console.log(error);
        });
    }
}

export function sortHotProperties(sortedItems) {
    return {
        type: SORT_HOT_PROPERTIES,
        payload: {sortedItems: sortedItems}
    }
}

export function checkAllHotProperties(checkedItems) {
    return {
        type: CHECK_ALL_HOT_PROPERTIES,
        payload: {checkedItems: checkedItems}
    }
}

export function checkHotPorperty(item) {
    return {
        type: CHECK_HOT_PROPERTY,
        payload: { item: item }
    }
}


export function removeHotProperties(items) {
    let ids = [];
    items.map((item) => {
        ids.push(item.get('id'));
    });
    return (dispatch) => {
        let data = [];
        dispatch({type: REMOVE_HOT_PROPERTIES_START});
        axios.post('/assets/siud/d/dashboard/dashboardResults/hotProperties.php', [{
            criteria:{
                member: WB.user.contact,
                ids: ids
            }
        }])
        .then(function (response) {
            dispatch({type: REMOVE_HOT_PROPERTIES_SUCCESS, payload: {deletedItemIds: ids}}); 
        })
        .catch(function (error) {
            dispatch({type: REMOVE_HOT_PROPERTIES_ERROR});
            console.log(error);
        });
    }
}