import {List, Map, fromJS } from 'immutable';
import axios from 'axios';

const FETCH_PENDING_SALES_START= 'wbonline/pendingSales/FETCH_PENDING_SALES_START';
const FETCH_PENDING_SALES_SUCCESS= 'wbonline/pendingSales/FETCH_PENDING_SALES_SUCCESS';
const FETCH_PENDING_SALES_ERROR= 'wbonline/pendingSales/FETCH_PENDING_SALES_ERROR';
const SORT_PENDING_SALES= 'wbonline/pendingSales/SORT_PENDING_SALES';           

export default (state = fromJS({
        data: List(),
        processing: false,
    }), action) => {
        let data = List();
        let payload = action.payload;
        switch(action.type) {
            case FETCH_PENDING_SALES_START:
                state = state.set('data', List());
                state = state.set('processing', true);
            break;
            case FETCH_PENDING_SALES_SUCCESS:
                state = state.set('processing', false);
                state = state.set('data', payload.data);
            break;
            case FETCH_PENDING_SALES_ERROR:
                state = state.set('processing', false);
            break;
            case SORT_PENDING_SALES:
                state = state.set('data', payload.sortedItems);
            break;
    }
    return  state;
}


export function loadPendingSales() {
    return (dispatch) => {
        let data = [];
        dispatch({type: FETCH_PENDING_SALES_START});
        axios.post('/assets/siud/s/dashboard/dashboardResults/pendingSales.php', [{
            criteria:{
                member: WB.user.contact,
                organisation: [WB.env.currentOrganisation.id]
            }
        }])
        .then(function (response) {
            data = response.data[0].result.data;
            dispatch({type: FETCH_PENDING_SALES_SUCCESS, payload: {data: fromJS(data)}}); 
        })
        .catch(function (error) {
            dispatch({type: FETCH_PENDING_SALES_ERROR});
            console.log(error);
        });
    }
}

export function sortPendingSales(sortedItems) {
    return {
        type: SORT_PENDING_SALES,
        payload: {sortedItems: sortedItems}
    }
}