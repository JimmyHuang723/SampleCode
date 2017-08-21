import { fork } from 'redux-saga/effects';
import checklistSaga from './modules/checklistSettings/sagas';

// Here, we register our watcher saga(s) and export as a single generator 
// function (startForeman) as our root Saga.
export default function* startForman() {
  yield fork(checklistSaga);
}