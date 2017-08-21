import {List, Map, fromJS } from 'immutable';
import { takeLatest, put, call } from 'redux-saga/effects';
import 'regenerator-runtime/runtime';
import * as ChecklistSettingsAction from './actions';
import types from './types';
import * as API from './api';

function* removeChecklists(action) {
    try {
        let ids = [];
        action.payload.items.map((item) => {
            ids.push(item.get('id'));
        });

        let subTaskIds = [];
        action.payload.subTaskItems.map((item) => {
            subTaskIds.push(item.get('id'));
        });
        yield put({type: types.REMOVE_CHECKLIST_ITEMS_START});
        yield put({type: types.REMOVE_CHECKLIST_SUB_TASK_ITEMS_START});
        const subTaskRemoveResponse = yield call(API.removeChecklistSubTaskItemsWithSaga, subTaskIds);
        yield put({type: types.REMOVE_CHECKLIST_SUB_TASK_ITEMS_SUCCESS, payload: {deletedSubTaskItemIds: subTaskIds}}); 
        const mainTaskRemoveResponse = yield call(API.removeChecklistItemsWithSaga, ids);
        yield put({type: types.REMOVE_CHECKLIST_ITEMS_SUCCESS, payload: {deletedItemIds: ids} });
    } catch(e) {
        yield put({type: types.REMOVE_CHECKLIST_ITEMS_ERROR});
    }
}

function* checklistSaga() {
  yield takeLatest(types.REMOVE_CHECKLIST_ITEMS_REQUEST, removeChecklists);
}

export default checklistSaga;