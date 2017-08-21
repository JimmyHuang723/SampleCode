import {List, Map, fromJS } from 'immutable';
import axios from 'axios';

const FETCH_CHECKLIST_REPORT_START= 'wbonline/cookings/FETCH_CHECKLIST_REPORT_START';
const FETCH_CHECKLIST_REPORT_SUCCESS= 'wbonline/cookings/FETCH_CHECKLIST_REPORT_SUCCESS';
const FETCH_CHECKLIST_REPORT_ERROR= 'wbonline/cookings/FETCH_CHECKLIST_REPORT_ERROR';
const SORT_CHECKLIST_REPORT= 'wbonline/cookings/SORT_CHECKLIST_REPORT';

export default (state = fromJS({
        data: List(),
        processing: false,
    }), action) => {
        let data = List();
        let payload = action.payload;
        switch(action.type) {
            case FETCH_CHECKLIST_REPORT_START:
                state = state.set('data', List());
                state = state.set('processing', true);
            break;
            case FETCH_CHECKLIST_REPORT_SUCCESS:
                state = state.set('processing', false);
                state = state.set('data', payload.data);
            break;
            case FETCH_CHECKLIST_REPORT_ERROR:
                state = state.set('processing', false);
            break;
            case SORT_CHECKLIST_REPORT:
                state = state.set('data', payload.sortedItems);
            break;
    }
    return  state;
}


export function loadChecklistReport(periodInDays = 7) {
    return (dispatch) => {
        let data = [];
        dispatch({type: FETCH_CHECKLIST_REPORT_START});
        axios.post('/assets/siud/s/dashboard/dashboardResults/checklistReport.php', [{
            criteria:{
                member: WB.user.contact,
                organisation: [WB.env.currentOrganisation.id],
                periodInDays: periodInDays
            }
        }])
        .then(function (response) {
            data = response.data[0].result.data;
            dispatch({type: FETCH_CHECKLIST_REPORT_SUCCESS, payload: {data: fromJS(data)}}); 
        })
        .catch(function (error) {
            dispatch({type: FETCH_CHECKLIST_REPORT_ERROR});
            console.log(error);
        });
    }
}

export function sortChecklistReport(sortedItems) {
    return {
        type: SORT_CHECKLIST_REPORT,
        payload: {sortedItems: sortedItems}
    }
}