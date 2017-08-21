import {List, Map, fromJS } from 'immutable';
import axios from 'axios';

export function loadComparables(taskId) {
    return (dispatch) => {
    
        let title = '';
        let apmPid = 0;
        let radialSales = [];
        let selectedSales = [];
        let comparables = [];
        dispatch({type: 'FETCH_COMPARABLES_START'});
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
            dispatch({type: 'FETCH_COMPARABLES_SUCCESS', payload: {title: title, comparables: radialSales, taskId: taskId, apmPid:  apmPid}}); 
        })
        .catch(function (error) {
            dispatch({type: 'FETCH_COMPARABLES_ERROR'});
            console.log(error);
        });
    }
}

export function selectComparable(updatedComparable) {
    return {type: 'SELECT_COMPARABLE', payload: {updatedComparable: updatedComparable}}; 
}

export function filterComparables(filters) {
    return (dispatch) => {
        dispatch({type: 'FETCH_NEW_COMPARABLES_START'});
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
            dispatch({type: 'FETCH_NEW_COMPARABLES_SUCCESS', payload: {comparables: radialSales }}); 
        })
        .catch(function (error) {
            dispatch({type: 'FETCH_NEW_COMPARABLES_ERROR'});
            console.log(error);
        }); 
    }
    
}

export function saveSelectedComparables(selectedComparables, taskId) {
    return (dispatch) => {
        let sales = selectedComparables.toJS();
        dispatch({type: 'SAVE_SELECTED_COMPARABLES_START'});
        var postData  = {
            sales: sales
            ,task: taskId
        };
        axios.post('/assets/siud/u/lpresComparables.php', [postData])
        .then(function (response) {
            dispatch({type: 'SAVE_SELECTED_COMPARABLES_SUCCESS', payload: {}}); 
        })
        .catch(function (error) {
            dispatch({type: 'SAVE_SELECTED_COMPARABLES_ERROR'});
            console.log(error);
        });
    }
    
}

export function selectAllComparables(blnCheckAll) {
    return {type: 'SELECT_ALL_COMPARABLES', payload: { blnCheckAll: blnCheckAll }};
}

export function closeComparables() {
    return {type: 'CLOSE_COMPARABLES', payload: { }};
}