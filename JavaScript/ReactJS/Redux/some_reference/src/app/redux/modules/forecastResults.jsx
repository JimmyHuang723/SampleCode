import {List, Map, fromJS } from 'immutable';
import axios from 'axios';
import * as DashboardResultsAction from './dashboardResults';

const FETCH_FORECAST_START= 'wbonline/forecastResults/FETCH_FORECAST_START';
const FETCH_FORECAST_SUCCESS= 'wbonline/forecastResults/FETCH_FORECAST_SUCCESS';
const FETCH_FORECAST_ERROR= 'wbonline/forecastResults/FETCH_FORECAST_ERROR';
const ADD_FORECAST_START= 'wbonline/forecastResults/ADD_FORECAST_START';
const ADD_FORECAST_SUCCESS= 'wbonline/forecastResults/ADD_FORECAST_SUCCESS';
const ADD_FORECAST_ERROR= 'wbonline/forecastResults/ADD_FORECAST_ERROR';
const COMPLETE_FORECAST_ITEM_START= 'wbonline/forecastResults/COMPLETE_FORECAST_ITEM_START';
const COMPLETE_FORECAST_ITEM_SUCCESS= 'wbonline/forecastResults/COMPLETE_FORECAST_ITEM_SUCCESS';
const COMPLETE_FORECAST_ITEM_ERROR= 'wbonline/forecastResults/COMPLETE_FORECAST_ITEM_ERROR';

export default (state = fromJS({
        data: List(),
        processing: false,
    }), action) => {
        let data = List();
        let payload = action.payload;
        let items = List();
        switch(action.type) {
            case FETCH_FORECAST_START:
                state = state.set('data', List());
                state = state.set('processing', true);
            break;
            case FETCH_FORECAST_SUCCESS:
                state = state.set('processing', false);
                items = payload.data;
                items = items.map((item) => {
                    if(item.get('completed')) {
                        item = item.set('status', 'complete')
                    } else if(!item.get('completed') && !item.get('status')) {
                        item = item.set('status', 'incomplete')
                    }
                    return item;
                });
                state = state.set('data', items);
            break;
            case FETCH_FORECAST_ERROR:
                state = state.set('processing', false);
            break;
            case ADD_FORECAST_START:
                // state = state.set('processing', true);
            break;
            case ADD_FORECAST_SUCCESS:
                // state = state.set('processing', false);
            break;
            case ADD_FORECAST_ERROR:
                // state = state.set('processing', false);
            break;
            case COMPLETE_FORECAST_ITEM_START:
                
            break;
            case COMPLETE_FORECAST_ITEM_SUCCESS:
                items = state.get('data');
                items = items.map((item) => {
                    if(item.get('id') == payload.updatedItem.get('id')) {
                        item = item.set('status', 'complete');
                    }
                    return item;
                });
                items = items.sort(
                    (a, b) => b.get('status').localeCompare(a.get('status'))
                );
                // let dataItems = items.toJS();
                // dataItems = _sortBy(dataItems, 'status').reverse();
                // items = fromJS(dataItems);
                state = state.set('data', items);
            break;
            case COMPLETE_FORECAST_ITEM_ERROR:
                // state = state.set('processing', false);
            break;
    }
    return  state;
}


export function loadForecastResults() {
    return (dispatch) => {
        let data = [];
        dispatch({type: FETCH_FORECAST_START});
        axios.post('/assets/siud/s/dashboard/dashboardResults/forecastResults.php', [{
            criteria:{
                member: WB.user.contact,
                organisation: WB.env.currentOrganisation,
            }
        }])
        .then(function (response) {
            data = response.data[0].result.data;
            dispatch({type: FETCH_FORECAST_SUCCESS, payload: {data: fromJS(data)}}); 
        })
        .catch(function (error) {
            dispatch({type: FETCH_FORECAST_ERROR});
            console.log(error);
        });
    }
}


export function addForecast(forecastText) {
    return (dispatch) => {
        let data = [];
        dispatch({type: ADD_FORECAST_START});
        axios.post('/assets/siud/i/dashboard/dailyForecast.php', [{
            criteria:{
                action: 'addForecast',
                member: WB.user.contact,
                organisation: [WB.env.currentOrganisation.id],
                text: forecastText
            }
        }])
        .then(function (response) {
            // data = response.data[0].result.data;
            dispatch({type: ADD_FORECAST_SUCCESS, payload: {}}); 
            dispatch(DashboardResultsAction.selectTab('fr'));
            dispatch(loadForecastResults());
            // dispatch({type: FETCH_FORECAST_START});
            // return axios.post('/assets/siud/s/dashboard/dashboardResults/forecastResults.php', [{
            //     criteria:{
            //         member: WB.user.contact,
            //         organisation: WB.env.currentOrganisation,
            //     }
            // }])
        })
        // .then(function (response) {
        //     data = response.data[0].result.data;
        //     dispatch({type: FETCH_FORECAST_SUCCESS, payload: {data: fromJS(data)}}); 
        // })
        .catch(function (error) {
            dispatch({type: ADD_FORECAST_ERROR});
            console.log(error);
        });
    }
}

export function completeForecast(updatedItem) {
    return (dispatch) => {
        // item.status = 'complete';
        dispatch({type: COMPLETE_FORECAST_ITEM_START, payload: {updatedItem: updatedItem}});
        axios.post('/assets/siud/u/systemBoards/dailyForecast.php', [{
            criteria: {
                forecast: [ {id: updatedItem.get('id')}]
            }
        }])
        .then( (response) => {
            dispatch({type: COMPLETE_FORECAST_ITEM_SUCCESS, payload: {updatedItem: updatedItem }});
        })
        .catch((error) => {
            dispatch({type: COMPLETE_FORECAST_ITEM_ERROR});
            console.log(error);
        });
    }
}