import {List, Map, fromJS } from 'immutable';
import axios from 'axios';

const FETCH_POINTS_GAME_START= 'wbonline/pointsGame/FETCH_POINTS_GAME_START';
const FETCH_POINTS_GAME_SUCCESS= 'wbonline/pointsGame/FETCH_POINTS_GAME_SUCCESS';
const FETCH_POINTS_GAME_ERROR= 'wbonline/pointsGame/FETCH_POINTS_GAME_ERROR';
const SORT_POINTS_GAME= 'wbonline/pointsGame/SORT_POINTS_GAME';              

export default (state = fromJS({
        data: List(),
        processing: false,
        daysLeft: 0
    }), action) => {
        let data = List();
        let payload = action.payload;
        switch(action.type) {
            case FETCH_POINTS_GAME_START:
                state = state.set('data', List());
                state = state.set('daysLeft',0);
                state = state.set('processing', true);
            break;
            case FETCH_POINTS_GAME_SUCCESS:
                state = state.set('processing', false);
                state = state.set('daysLeft', payload.daysLeft);
                state = state.set('data', payload.data);
            break;
            case FETCH_POINTS_GAME_ERROR:
                state = state.set('processing', false);
                state = state.set('daysLeft',0);
                state = state.set('data', List());
            break;
            case SORT_POINTS_GAME:
                state = state.set('data', payload.sortedItems);
            break;
    }
    return  state;
}


export function loadPointsGame() {
    return (dispatch) => {
        let data = [];
        let daysLeft = 0;
        dispatch({type: FETCH_POINTS_GAME_START});
        axios.post('/assets/siud/s/dashboard/dashboardResults/pointsGame.php', [{
            criteria:{
                member: WB.user.contact,
                organisation: [WB.env.currentOrganisation.id]
            }
        }])
        .then(function (response) {
            data = response.data[0].result.data;
            daysLeft = response.data[0].result.daysLeft;
            dispatch({type: FETCH_POINTS_GAME_SUCCESS, payload: {data: fromJS(data), daysLeft: daysLeft }}); 
        })
        .catch(function (error) {
            dispatch({type: FETCH_POINTS_GAME_ERROR});
            console.log(error);
        });
    }
}

export function sortPointsGame(sortedItems) {
    return {
        type: SORT_POINTS_GAME,
        payload: {sortedItems: sortedItems}
    }
}