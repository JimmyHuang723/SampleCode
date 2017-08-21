import { applyMiddleware, createStore, compose } from 'redux';
import createSagaMiddleware from 'redux-saga';
import thunk from 'redux-thunk';
import { createLogger } from 'redux-logger';
import rootReducer from './reducer';
import rootSaga from './sagas';


// const middleware = applyMiddleware(thunk, createLogger());
const sagaMiddleware = createSagaMiddleware();
const middleware = applyMiddleware(thunk, sagaMiddleware);
const enhancer = compose(
    middleware,
    window.devToolsExtension ? window.devToolsExtension() : f => f
);

const store =  createStore(rootReducer, enhancer);
sagaMiddleware.run(rootSaga);

export default store;
