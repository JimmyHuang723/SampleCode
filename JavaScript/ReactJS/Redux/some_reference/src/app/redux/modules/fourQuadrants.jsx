import {List, Map, fromJS } from 'immutable';
import axios from 'axios';

const FETCH_FOUR_QUADRANTS_START= 'wbonline/fourQuadrants/FETCH_FOUR_QUADRANTS_START';
const FETCH_FOUR_QUADRANTS_SUCCESS= 'wbonline/fourQuadrants/FETCH_FOUR_QUADRANTS_SUCCESS';
const FETCH_FOUR_QUADRANTS_ERROR= 'wbonline/fourQuadrants/FETCH_FOUR_QUADRANTS_ERROR';

export default (state = fromJS({
        data: Map(),
        processing: false,
    }), action) => {
        let data = Map();
        let payload = action.payload;
        switch(action.type) {
            case FETCH_FOUR_QUADRANTS_START:
                state = state.set('data', Map());
                state = state.set('processing', true);
            break;
            case FETCH_FOUR_QUADRANTS_SUCCESS:
                state = state.set('processing', false);
                state = state.set('data', payload.data);
            break;
            case FETCH_FOUR_QUADRANTS_ERROR:
                state = state.set('processing', false);
            break;
    }
    return  state;
}


export function loadFourQuadrants() {
    return (dispatch) => {
        let data = {};
        dispatch({type: FETCH_FOUR_QUADRANTS_START});
        axios.post('/assets/siud/s/dashboard/dashboardResults/fourQuadrants.php', [{
            criteria:{
                member: WB.user.contact,
                organisation: [WB.env.currentOrganisation.id]
            }
        }])
        .then(function (response) {
            data = response.data[0].result.data;
            dispatch({type: FETCH_FOUR_QUADRANTS_SUCCESS, payload: {data: fromJS(data)}}); 
        })
        .catch(function (error) {
            dispatch({type: FETCH_FOUR_QUADRANTS_ERROR});
            console.log(error);
        });
    }
}