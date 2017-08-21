import {List, Map, fromJS } from 'immutable';
import axios from 'axios';
import * as HotPropertyAction from './hotProperties';

const FETCH_HOT_PROPERTY_SELECTION_START = 'wbonline/hotPropertySelectionPopup/FETCH_HOT_PROPERTY_SELECTION_START';
const FETCH_HOT_PROPERTY_SELECTION_SUCCESS = 'wbonline/hotPropertySelectionPopup/FETCH_HOT_PROPERTY_SELECTION_SUCCESS';
const FETCH_HOT_PROPERTY_SELECTION_ERROR = 'wbonline/hotPropertySelectionPopup/FETCH_HOT_PROPERTY_SELECTION_ERROR';
const CLOSE_HOT_PROPERTY_SELECTION = 'wbonline/hotPropertySelectionPopup/CLOSE_HOT_PROPERTY_SELECTION';
const SELECT_ALL_HOT_PROPERTY_SELECTION = 'wbonline/hotPropertySelectionPopup/SELECT_ALL_HOT_PROPERTY_SELECTION';
const SELECT_HOT_PROPERTY_SELECTION = 'wbonline/hotPropertySelectionPopup/SELECT_HOT_PROPERTY_SELECTION';
const SAVE_SELECTED_HOT_PROPERTY_SELECTION_START = 'wbonline/hotPropertySelectionPopup/SAVE_SELECTED_HOT_PROPERTY_SELECTION_START';
const SAVE_SELECTED_HOT_PROPERTY_SELECTION_SUCCESS = 'wbonline/hotPropertySelectionPopup/SAVE_SELECTED_HOT_PROPERTY_SELECTION_SUCCESS';
const SAVE_SELECTED_HOT_PROPERTY_SELECTION_ERROR = 'wbonline/hotPropertySelectionPopup/SAVE_SELECTED_HOT_PROPERTY_SELECTION_ERROR';
const SORT_HOT_PROPERTY_SELECTION = 'wbonline/hotPropertySelectionPopup/SORT_HOT_PROPERTY_SELECTION';
const CHECK_ALL_HOT_PROPERTY_SELECTION = 'wbonline/hotPropertySelectionPopup/CHECK_ALL_HOT_PROPERTY_SELECTION';
const CHECK_HOT_PROPERTY_SELECTION = 'wbonline/hotPropertySelectionPopup/CHECK_HOT_PROPERTY_SELECTION';

export default (state = fromJS({
        modalIsOpen: false,
        selectAll: false,
        properties: List(),
        processing: false
    }), action) => {
        let properties = state.get('properties');
        let payload = action.payload;
        switch(action.type) {
            case FETCH_HOT_PROPERTY_SELECTION_START:
                state = state.set('properties', List());
                state = state.set('processing', true);
                state = state.set('modalIsOpen', true);
            break;
            case FETCH_HOT_PROPERTY_SELECTION_SUCCESS:
                state = state.set('processing', false);
                state = state.set('properties', payload.properties);
            break;
            case FETCH_HOT_PROPERTY_SELECTION_ERROR:
                state = state.set('processing', false);
            break;
            case CLOSE_HOT_PROPERTY_SELECTION:
                state = state.set('processing', false);
                state = state.set('modalIsOpen', false);
                state = state.set('properties', List());
            break;
            case SELECT_ALL_HOT_PROPERTY_SELECTION:
                properties = properties.map(item => item.set('selected',  payload.blnCheckAll))
                state = state.set('selectAll', payload.blnCheckAll);
                state = state.set('properties', properties);
            break;
            case SELECT_HOT_PROPERTY_SELECTION:
                let index = properties.findIndex(item => {
                    return item.get('id') === payload.updatedPropertySelection.get('id');
                });
                if (index > -1) {
                    properties = properties.set(index, payload.updatedPropertySelection);
                }
                if(!payload.updatedPropertySelection.get('selected')) {
                    state = state.set('selectAll', false);
                }
                state = state.set('properties', properties);
            break;
            case SAVE_SELECTED_HOT_PROPERTY_SELECTION_START:
                state = state.set('processing', true);
            break;
            case SAVE_SELECTED_HOT_PROPERTY_SELECTION_SUCCESS:
                state = state.set('processing', false);
                state = state.set('modalIsOpen', false);
            break;
            case SAVE_SELECTED_HOT_PROPERTY_SELECTION_ERROR:
                state = state.set('processing', false);
            break;
            case SORT_HOT_PROPERTY_SELECTION:
                state = state.set('properties', payload.sortedItems);
            break;
            case CHECK_ALL_HOT_PROPERTY_SELECTION:
                state = state.set('properties', payload.checkedItems);
            break;
            case CHECK_HOT_PROPERTY_SELECTION:
                index = properties.findIndex(item => {
                    return item.get('id') === payload.item.get('id');
                });
                if (index > -1) {
                    properties = properties.set(index, payload.item);
                }
                state = state.set('properties', properties);
            break;
    }
    return  state;
}


export function loadHotPropertySelection(taskId) {
    return (dispatch) => {
        let selectedSales = [];
        let properties = [];
        dispatch({type: FETCH_HOT_PROPERTY_SELECTION_START});
        axios.post('/assets/siud/s/dashboard/dashboardResults/hotPropertySelection.php', [{
            criteria:{
                organisation:[WB.env.currentOrganisation.id],
                member:WB.user.contact,
            }
        }])
        .then(function (response) {
            properties = response.data[0].result.properties;
            dispatch({type: FETCH_HOT_PROPERTY_SELECTION_SUCCESS, payload: {properties: fromJS(properties)}}); 
        })
        .catch(function (error) {
            dispatch({type: FETCH_HOT_PROPERTY_SELECTION_ERROR});
            console.log(error);
        });
    }
}

export function selectProperty(updatedPropertySelection) {
    return {type: SELECT_HOT_PROPERTY_SELECTION, payload: {updatedPropertySelection: updatedPropertySelection}}; 
}


export function addSelectedProperites(propertyIds) {
    return (dispatch) => {
        dispatch({type: SAVE_SELECTED_HOT_PROPERTY_SELECTION_START});
        axios.post('/assets/siud/i/dashboard/addHotProperties.php', [{
            criteria:{
                propertyIds: propertyIds,
                member:WB.user.contact,
                action: 'addHotProperties'
            }
        }])
        .then(function (response) {
            dispatch({type: SAVE_SELECTED_HOT_PROPERTY_SELECTION_SUCCESS, payload: {}}); 
            dispatch(HotPropertyAction.loadHotProperties()); 
            
        })
        .catch(function (error) {
            dispatch({type: SAVE_SELECTED_HOT_PROPERTY_SELECTION_ERROR});
            console.log(error);
        });
    }
}

export function selectAllProperties(blnCheckAll) {
    return {type: SELECT_ALL_HOT_PROPERTY_SELECTION, payload: { blnCheckAll: blnCheckAll }};
}

export function closeHotPropertySelection() {
    return {type: CLOSE_HOT_PROPERTY_SELECTION, payload: { }};
}

export function sortHotPropertySelection(sortedItems) {
    return {
        type: SORT_HOT_PROPERTY_SELECTION,
        payload: {sortedItems: sortedItems}
    }
}

export function checkAllHotPropertySelection(checkedItems) {
    return {
        type: CHECK_ALL_HOT_PROPERTY_SELECTION,
        payload: {checkedItems: checkedItems}
    }
}

export function checkHotPorpertySelection(item) {
    return {
        type: CHECK_HOT_PROPERTY_SELECTION,
        payload: { item: item }
    }
}