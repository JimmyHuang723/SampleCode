import {List, Map, fromJS } from 'immutable';
import axios from 'axios';

const FETCH_COMPARABLES_START= 'wbonline/cmaPopup/FETCH_COMPARABLES_START';
const FETCH_COMPARABLES_SUCCESS= 'wbonline/cmaPopup/FETCH_COMPARABLES_SUCCESS';
const FETCH_COMPARABLES_ERROR= 'wbonline/cmaPopup/FETCH_COMPARABLES_ERROR';
const CLOSE_COMPARABLES= 'wbonline/cmaPopup/CLOSE_COMPARABLES';
const SELECT_ALL_COMPARABLES= 'wbonline/cmaPopup/SELECT_ALL_COMPARABLES';
const SELECT_COMPARABLE= 'wbonline/cmaPopup/SELECT_COMPARABLE';
const FETCH_NEW_COMPARABLES_START= 'wbonline/cmaPopup/FETCH_NEW_COMPARABLES_START';
const FETCH_NEW_COMPARABLES_ERROR= 'wbonline/cmaPopup/FETCH_NEW_COMPARABLES_ERROR';
const FETCH_NEW_COMPARABLES_SUCCESS= 'wbonline/cmaPopup/FETCH_NEW_COMPARABLES_SUCCESS';
const SAVE_SELECTED_COMPARABLES_SUCCESS= 'wbonline/cmaPopup/SAVE_SELECTED_COMPARABLES_SUCCESS';
const SAVE_SELECTED_COMPARABLES_START= 'wbonline/cmaPopup/SAVE_SELECTED_COMPARABLES_START';
const SAVE_SELECTED_COMPARABLES_ERROR= 'wbonline/cmaPopup/SAVE_SELECTED_COMPARABLES_ERROR';
const FETCH_COMPARABLES_PRESENTATION_DATA_START= 'wbonline/cmaPopup/FETCH_COMPARABLES_PRESENTATION_DATA_START';
const FETCH_COMPARABLES_PRESENTATION_DATA_SUCCESS= 'wbonline/cmaPopup/FETCH_COMPARABLES_PRESENTATION_DATA_SUCCESS';
const FETCH_COMPARABLES_PRESENTATION_DATA_ERROR= 'wbonline/cmaPopup/FETCH_COMPARABLES_PRESENTATION_DATA_ERROR';

export default (state = fromJS({
        title: '',
        modalIsOpen: false,
        taskId: 0,
        selectAll: false,
        comparables: List(),
        processing: false,
        apmPid: 0,
        compId: 0,
        propertyReportURL: '',
        cmaPrintURL: '',
        presentationData: Map()
    }), action) => {
        let comparables = List();
        let payload = action.payload;
        let propertyReportURL = '';
        let cmaPrintURL = '';
        switch(action.type) {
            case FETCH_COMPARABLES_START:
                state = state.set('presentationData', Map());
                state = state.set('comparables', List());
                state = state.set('processing', true);
                state = state.set('modalIsOpen', true);
            break;
            case FETCH_COMPARABLES_SUCCESS:
                let propertyReportURL ='https://api.pricefinder.com.au/v1/properties/' + 
                    payload.apmPid + '/pdf?attach=true&access_token='+WB.user.apm.tokenKey;
                let cmaPrintURL = '/assets/db/app/print/cma.php?id=' + 
                    payload.compId + '&cid=' + WB.user.contact.id + 
                    '&oid=' + WB.env.currentOrganisation.id + '&token=' + WB.user.apm.tokenKey;
                state = state.set('processing', false);
                state = state.set('title', payload.title);
                state = state.set('comparables', payload.comparables);
                state = state.set('taskId', payload.taskId);
                state = state.set('apmPid', payload.apmPid);
                state = state.set('compId', payload.compId);
                state = state.set('propertyReportURL', propertyReportURL);
                state = state.set('cmaPrintURL', cmaPrintURL);
            break;
            case FETCH_COMPARABLES_ERROR:
                state = state.set('processing', false);
            break;
            case CLOSE_COMPARABLES:
                state = state.set('processing', false);
                state = state.set('modalIsOpen', false);
                state = state.set('title', '');
                state = state.set('comparables', List());
                state = state.set('taskId', 0);
                state = state.set('apmPid', 0);
                state = state.set('compId', 0);
                state = state.set('propertyReportURL', '');
                state = state.set('cmaPrintURL', '');
            break;
            case SELECT_ALL_COMPARABLES:
                comparables = state.get('comparables');
                comparables = comparables.map(item => item.set('selected',  payload.blnCheckAll))
                state = state.set('selectAll', payload.blnCheckAll);
                state = state.set('comparables', comparables);
            break;
            case SELECT_COMPARABLE:
                comparables = state.get('comparables');
                let index = comparables.findIndex(item => {
                    return item.get('id') === payload.updatedComparable.get('id');
                });
                if (index > -1) {
                    comparables = comparables.set(index, payload.updatedComparable);
                }
                if(!payload.updatedComparable.get('selected')) {
                    state = state.set('selectAll', false);
                }
                state = state.set('comparables', comparables);
            break;
            case FETCH_NEW_COMPARABLES_START:
                state = state.set('comparables', List());
                state = state.set('processing', true);
            break;
            case FETCH_NEW_COMPARABLES_ERROR:
                state = state.set('processing', false);
            break;
            case FETCH_NEW_COMPARABLES_SUCCESS:
                state = state.set('processing', false);
                state = state.set('comparables', payload.comparables);
            break;
            case SAVE_SELECTED_COMPARABLES_SUCCESS:
                state = state.set('processing', false);
            break;
            case SAVE_SELECTED_COMPARABLES_START:
                state = state.set('processing', true);
            break;
            case SAVE_SELECTED_COMPARABLES_ERROR:
                state = state.set('processing', false);
            break;
            case FETCH_COMPARABLES_PRESENTATION_DATA_START:
                state = state.set('presentationData', Map());
                state = state.set('processing', true);
            break;
            case FETCH_COMPARABLES_PRESENTATION_DATA_SUCCESS:
                state = state.set('processing', false);
                state = state.set('presentationData', payload.presentationData);
            break;
            case FETCH_COMPARABLES_PRESENTATION_DATA_ERROR:
                state = state.set('processing', false);
            break;


    }
    return  state;
}

export function loadFollowUpPresentationData(taskId) {
     return (dispatch) => {
        dispatch({type: FETCH_COMPARABLES_PRESENTATION_DATA_START, payload: {}}); 
        axios.post('/assets/siud/s/lpresFollowUpData.php', [{
                criteria:{
                    task: taskId,
                    member:WB.user.contact
                }
        }])
        .then(function(response){
            let followUpPresData = response.data[0].result.followUpPresData;
            let presentationData = Map();
            if (followUpPresData.length>0) {
                presentationData = fromJS(followUpPresData[0])
            }
            dispatch({type: FETCH_COMPARABLES_PRESENTATION_DATA_SUCCESS, payload: {presentationData: presentationData}}); 
        })
        .catch(function (error) {
            dispatch({type: FETCH_COMPARABLES_PRESENTATION_DATA_ERROR});
            console.log(error);
        });
     };
}

export function loadComparables(taskId) {
    return (dispatch) => {
        let title = '';
        let apmPid = 0;
        let compId = 0;
        let radialSales = [];
        let selectedSales = [];
        let comparables = [];
        dispatch({type: FETCH_COMPARABLES_START});
        axios.post('/assets/siud/s/lpresComparables.php', [{
            criteria:{
                organisation:[WB.env.currentOrganisation.id],
                member:WB.user.contact,
                task: taskId
            }
        }])
        .then(function (response) {
            comparables = response.data[0].result.comparables;
            if (comparables.length>0) {
                radialSales = JSON.parse(comparables[0].radialSales);
                selectedSales = JSON.parse(comparables[0].selectedSales);
                title = comparables[0].address;
                apmPid = comparables[0].apmProperty;
                compId = comparables[0].id;
            }
            radialSales = fromJS(radialSales);
            selectedSales = fromJS(selectedSales);
            radialSales = radialSales.map(radialItem => {
                let index = selectedSales.findIndex(item => {
                    return item.get('id') === radialItem.get('id');
                });
                if(index>-1) {
                return radialItem.set('selected', true);
                } else {
                return radialItem.set('selected', false);
                }
            });
            dispatch({type: FETCH_COMPARABLES_SUCCESS, payload: {title: title, comparables: radialSales, taskId: taskId, apmPid:  apmPid, compId: compId}}); 
            dispatch(loadFollowUpPresentationData(taskId));
        })
        .catch(function (error) {
            dispatch({type: FETCH_COMPARABLES_ERROR});
            console.log(error);
        });
    }
}

export function selectComparable(updatedComparable) {
    return {type: SELECT_COMPARABLE, payload: {updatedComparable: updatedComparable}}; 
}

export function filterComparables(filters) {
    return (dispatch) => {
        dispatch({type: FETCH_NEW_COMPARABLES_START});
        var postData  ={
            criteria:{
                organisation:[WB.env.currentOrganisation.id]
                ,member:WB.user.contact
                ,task: filters.get('taskId')
                ,apmPid: filters.get('apmPid')
                ,radius: filters.get('radius')
                ,pfrom: filters.get('priceFrom')
                ,pto: filters.get('priceTo')
                ,type: filters.get('propertyType')
                ,token: WB.user.apm.tokenKey
                ,dateFrom: filters.get('startNo') + filters.get('period')
            }
        };
        axios.post('/assets/siud/s/filterComparables.php', [postData])
        .then(function (response) {
            let comparables = response.data[0].result.comparables;
            let radialSales = [];
            let selectedSales = [];
            if (comparables.length>0) {
                radialSales = JSON.parse(comparables[0].radialSales);
                selectedSales = JSON.parse(comparables[0].selectedSales);
            }
            radialSales = fromJS(radialSales);
            selectedSales = fromJS(selectedSales);
            radialSales = radialSales.map(radialItem => {
                let index = selectedSales.findIndex(item => {
                    return item.get('id') === radialItem.get('id');
                });
                if(index>-1) {
                return radialItem.set('selected', true);
                } else {
                return radialItem.set('selected', false);
                }
            });
            dispatch({type: FETCH_NEW_COMPARABLES_SUCCESS, payload: {comparables: radialSales }}); 
        })
        .catch(function (error) {
            dispatch({type: FETCH_NEW_COMPARABLES_ERROR});
            console.log(error);
        }); 
    }
    
}

export function saveSelectedComparables(selectedComparables, taskId) {
    return (dispatch) => {
        let sales = selectedComparables.toJS();
        dispatch({type: SAVE_SELECTED_COMPARABLES_START});
        var postData  = {
            sales: sales
            ,task: taskId
        };
        axios.post('/assets/siud/u/lpresComparables.php', [postData])
        .then(function (response) {
            dispatch({type: SAVE_SELECTED_COMPARABLES_SUCCESS, payload: {}}); 
        })
        .catch(function (error) {
            dispatch({type: SAVE_SELECTED_COMPARABLES_ERROR});
            console.log(error);
        });
    }
    
}

export function selectAllComparables(blnCheckAll) {
    return {type: SELECT_ALL_COMPARABLES, payload: { blnCheckAll: blnCheckAll }};
}

export function closeComparables() {
    return {type: CLOSE_COMPARABLES, payload: { }};
}