import {List, Map, fromJS } from 'immutable';

export default (state = fromJS({
        title: '',
        modalIsOpen: false,
        taskId: 0,
        selectAll: false,
        comparables: List(),
        processing: false,
        apmPid: 0
    }), action) => {
        let comparables = List();
        let payload = action.payload;
        switch(action.type) {
            case 'FETCH_COMPARABLES_START':
                state = state.set('comparables', List());
                state = state.set('processing', true);
                state = state.set('modalIsOpen', true);
            break;
            case 'FETCH_COMPARABLES_SUCCESS':
                state = state.set('processing', false);
                state = state.set('title', payload.title);
                state = state.set('comparables', payload.comparables);
                state = state.set('taskId', payload.taskId);
                state = state.set('apmPid', payload.apmPid);
            break;
            case 'FETCH_COMPARABLES_ERROR':
                state = state.set('processing', false);
            break;
            case 'CLOSE_COMPARABLES':
                state = state.set('processing', false);
                state = state.set('modalIsOpen', false);
                state = state.set('title', '');
                state = state.set('comparables', List());
                state = state.set('taskId', 0);
                state = state.set('apmPid', 0);
            break;
            case 'SELECT_ALL_COMPARABLES':
                comparables = state.get('comparables');
                comparables = comparables.map(item => item.set('selected',  payload.blnCheckAll))
                state = state.set('selectAll', payload.blnCheckAll);
                state = state.set('comparables', comparables);
            break;
            case 'SELECT_COMPARABLE':
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
            case 'FETCH_NEW_COMPARABLES_START':
                state = state.set('comparables', List());
                state = state.set('processing', true);
            break;
            case 'FETCH_NEW_COMPARABLES_ERROR':
                state = state.set('processing', false);
            break;
            case 'FETCH_NEW_COMPARABLES_SUCCESS':
                state = state.set('processing', false);
                state = state.set('comparables', payload.comparables);
            break;
            case 'SAVE_SELECTED_COMPARABLES_SUCCESS':
                state = state.set('processing', false);
            break;
            case 'SAVE_SELECTED_COMPARABLES_START':
                state = state.set('processing', true);
            break;
            case 'SAVE_SELECTED_COMPARABLES_ERROR':
                state = state.set('processing', false);
            break;
    }
    return  state;
}