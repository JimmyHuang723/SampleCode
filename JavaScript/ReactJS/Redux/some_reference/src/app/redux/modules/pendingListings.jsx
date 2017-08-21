import {List, Map, fromJS } from 'immutable';
import axios from 'axios';

const FETCH_PENDING_LISTINGS_START= 'wbonline/pendingListings/FETCH_PENDING_LISTINGS_START';
const FETCH_PENDING_LISTINGS_SUCCESS= 'wbonline/pendingListings/FETCH_PENDING_LISTINGS_SUCCESS';
const FETCH_PENDING_LISTINGS_ERROR= 'wbonline/pendingListings/FETCH_PENDING_LISTINGS_ERROR';
const SORT_PENDING_LISTINGS= 'wbonline/pendingListings/SORT_PENDING_LISTINGS';
const SHOW_PENDING_LISTING_DETAILS= 'wbonline/pendingListings/SHOW_PENDING_LISTING_DETAILS';

export default (state = fromJS({
        data: List(),
        processing: false,
    }), action) => {
        let items = state.get('data');
        let payload = action.payload;
        switch(action.type) {
            case FETCH_PENDING_LISTINGS_START:
                state = state.set('data', List());
                state = state.set('processing', true);
            break;
            case FETCH_PENDING_LISTINGS_SUCCESS:
                state = state.set('processing', false);
                state = state.set('data', payload.data);
            break;
            case FETCH_PENDING_LISTINGS_ERROR:
                state = state.set('processing', false);
            break;
            case SORT_PENDING_LISTINGS:
                state = state.set('data', payload.sortedItems);
            break;
              case SHOW_PENDING_LISTING_DETAILS:
                let index = items.findIndex(item => {
                    return item.get('id') === payload.item.get('id');
                });
                if (index > -1) {
                    items = items.set(index, payload.item);
                }
                state = state.set('data', items);
            break;
    }
    return  state;
}


export function loadPendingListings() {
    return (dispatch) => {
        let data = [];
        dispatch({type: FETCH_PENDING_LISTINGS_START});
        axios.post('/assets/siud/s/dashboard/dashboardResults/pendingListings.php', [{
            criteria:{
                member: WB.user.contact,
                organisation: [WB.env.currentOrganisation.id]
            }
        }])
        .then(function (response) {
            data = response.data[0].result.data;
            dispatch({type: FETCH_PENDING_LISTINGS_SUCCESS, payload: {data: fromJS(data)}}); 
        })
        .catch(function (error) {
            dispatch({type: FETCH_PENDING_LISTINGS_ERROR});
            console.log(error);
        });
    }
}

export function sortPendingListings(sortedItems) {
    return {
        type: SORT_PENDING_LISTINGS,
        payload: {sortedItems: sortedItems}
    }
}

export function showPendingListingDetails(item) {
    return {
        type: SHOW_PENDING_LISTING_DETAILS,
        payload: { item: item }
    }
}