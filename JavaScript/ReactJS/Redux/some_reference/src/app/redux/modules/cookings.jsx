import {List, Map, fromJS } from 'immutable';
import axios from 'axios';

const FETCH_COOKINGS_START= 'wbonline/cookings/FETCH_COOKINGS_START';
const FETCH_COOKINGS_SUCCESS= 'wbonline/cookings/FETCH_COOKINGS_SUCCESS';
const FETCH_COOKINGS_ERROR= 'wbonline/cookings/FETCH_COOKINGS_ERROR';
const SORT_COOKINGS= 'wbonline/cookings/SORT_COOKINGS';

export default (state = fromJS({
        data: List(),
        processing: false,
    }), action) => {
        let data = List();
        let payload = action.payload;
        switch(action.type) {
            case FETCH_COOKINGS_START:
                state = state.set('data', List());
                state = state.set('processing', true);
            break;
            case FETCH_COOKINGS_SUCCESS:
                state = state.set('processing', false);
                state = state.set('data', payload.data);
            break;
            case FETCH_COOKINGS_ERROR:
                state = state.set('processing', false);
            break;
            case SORT_COOKINGS:
                state = state.set('data', payload.sortedItems);
            break;
    }
    return  state;
}


export function loadCookings() {
    return (dispatch) => {
        let data = [];
        dispatch({type: FETCH_COOKINGS_START});
        axios.post('/assets/siud/s/dashboard/dashboardResults/cookings.php', [{
            criteria:{
                member: WB.user.contact,
                organisation: [WB.env.currentOrganisation.id]
            }
        }])
        .then(function (response) {
            data = response.data[0].result.data;
            dispatch({type: FETCH_COOKINGS_SUCCESS, payload: {data: fromJS(data)}}); 
        })
        .catch(function (error) {
            dispatch({type: FETCH_COOKINGS_ERROR});
            console.log(error);
        });
    }
}

export function sortCookings(sortedItems) {
    return {
        type: SORT_COOKINGS,
        payload: {sortedItems: sortedItems}
    }
}