import {List, Map, fromJS } from 'immutable';
import axios from 'axios';

const FETCH_AAA_BUYERS_START= 'wbonline/aaaBuyers/FETCH_AAA_BUYERS_START';
const FETCH_AAA_BUYERS_SUCCESS= 'wbonline/aaaBuyers/FETCH_AAA_BUYERS_SUCCESS';
const FETCH_AAA_BUYERS_ERROR= 'wbonline/aaaBuyers/FETCH_AAA_BUYERS_ERROR';
const SORT_AAA_BUYERS= 'wbonline/aaaBuyers/SORT_AAA_BUYERS';

export default (state = fromJS({
        data: List(),
        processing: false,
    }), action) => {
        let data = List();
        let payload = action.payload;
        switch(action.type) {
            case FETCH_AAA_BUYERS_START:
                state = state.set('data', List());
                state = state.set('processing', true);
            break;
            case FETCH_AAA_BUYERS_SUCCESS:
                state = state.set('processing', false);
                state = state.set('data', payload.data);
            break;
            case FETCH_AAA_BUYERS_ERROR:
                state = state.set('processing', false);
            break;
            case SORT_AAA_BUYERS:
                state = state.set('data', payload.sortedItems);
            break;
    }
    return  state;
}


export function loadAAABuyers() {
    return (dispatch) => {
        let data = [];
        dispatch({type: FETCH_AAA_BUYERS_START});
        axios.post('/assets/siud/s/dashboard/dashboardResults/aaaBuyers.php', [{
            criteria:{
                member: WB.user.contact,
                organisation: [WB.env.currentOrganisation.id]
            }
        }])
        .then(function (response) {
            data = response.data[0].result.data;
            dispatch({type: FETCH_AAA_BUYERS_SUCCESS, payload: {data: fromJS(data)}}); 
        })
        .catch(function (error) {
            dispatch({type: FETCH_AAA_BUYERS_ERROR});
            console.log(error);
        });
    }
}

export function sortAAABuyers(sortedItems) {
    return {
        type: SORT_AAA_BUYERS,
        payload: {sortedItems: sortedItems}
    }
}