import {List, Map, fromJS } from 'immutable';
import axios from 'axios';

const DASHBOARD_RESULTS_SELECT_TAB= 'wbonline/dashboardResults/DASHBOARD_RESULTS_SELECT_TAB';
const DASHBOARD_RESULTS_LOAD= 'wbonline/dashboardResults/DASHBOARD_RESULTS_LOAD';
const DASHBOARD_RESULTS_UPDATE_CHECKLIST_ACCESS = 'wbonline/dashboardResults/DASHBOARD_RESULTS_UPDATE_CHECKLIST_ACCESS';

export default (state = fromJS({
        tabs : [
            // {name: 'ck', title: 'CK', active: true, row: 'first'},
            // {name: 'pl', title: 'PL', active: false, row: 'first'},
            // {name: 'ps', title: 'PS', active: false, row: 'first'},
            // {name: 'aaa', title: 'AAA', active: false, row: 'first'},
            // {name: 'fp', title: 'FP', active: false, row: 'first'},
            // {name: '4q', title: '4Q', active: false, row: 'first'},
            {name: 'fr', title: 'FR', active: true, row: 'second', hasAccess: true},
            {name: 'ck', title: 'CK', active: false, row: 'second', hasAccess: true},
            {name: 'cl', title: 'CL', active: false, row: 'second', hasAccess: false},
            {name: 'pl', title: 'PL', active: false, row: 'second', hasAccess: true},
            {name: 'ps', title: 'PS', active: false, row: 'second', hasAccess: true},
            {name: 'aaa', title: 'AAA', active: false, row: 'second', hasAccess: true},
            {name: 'hp', title: 'HP', active: false, row: 'second', hasAccess: true},
            {name: '4q', title: '4Q', active: false, row: 'second', hasAccess: true},
            {name: 'pg', title: 'PG', active: false, row: 'second', hasAccess: true}
        ],
        activeTab: 'fr'
    }), action) => {
        const payload = action.payload;
        let tabs = state.get('tabs');
        switch(action.type) {
            case DASHBOARD_RESULTS_SELECT_TAB:
                state = state.set('activeTab', payload.tabName);
                tabs = tabs.map((tabItem) => {
                    tabItem = tabItem.set('active', false);
                    if(tabItem.get('name') == payload.tabName) {
                        tabItem = tabItem.set('active', true)
                    } 
                    return tabItem;
                })
                state = state.set('tabs', tabs);
            break;
            case DASHBOARD_RESULTS_LOAD:
                
            break;
            case DASHBOARD_RESULTS_UPDATE_CHECKLIST_ACCESS:
                tabs = tabs.map((tabItem) => {
                    if(tabItem.get('name') == 'cl') {
                        tabItem = tabItem.set('hasAccess', payload.hasAccess)
                    } 
                    return tabItem;
                });
                state = state.set('tabs', tabs);
            break;
        }
    return  state;
}

export function loadDashbaordResults() {
    return {
        type: DASHBOARD_RESULTS_LOAD,
        payload: {}
    }
}
export function selectTab(tabName) {
    return {
        type: DASHBOARD_RESULTS_SELECT_TAB,
        payload: { tabName: tabName }
    }
}

export function updateChecklistAccess(objChecklistAccess) {
      return {
        type: DASHBOARD_RESULTS_UPDATE_CHECKLIST_ACCESS,
        payload: objChecklistAccess
    }
}