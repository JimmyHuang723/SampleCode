import React, {Component} from 'react';
import {render, unmountComponentAtNode} from 'react-dom';

import { bindActionCreators } from 'redux';
import { Provider, connect } from 'react-redux';
import store from '../../redux/store';

import * as DashboardResultsAction from '../../redux/modules/dashboardResults';

import FourQuadrants from './FourQuadrants';
import AAABuyers from './AAABuyers';
import Cookings from './Cookings';
import PendingListings from './PendingListings';
import PendingSales from './PendingSales';
import HotProperties from './HotProperties';
import PointsGame from './PointsGame';
import ForecastResults from './ForecastResults';
import ChecklistReport from './ChecklistReport';

function DashBoardResultBody(props) {
    const tabName = props.tabName;
    switch(tabName) {
      case 'fr':
        return <ForecastResults/>;
      case 'ck':
        return <Cookings/>;
      case 'pl':
        return <PendingListings/>;
      case 'ps':
        return <PendingSales/>;
      case 'aaa':
        return <AAABuyers/>;
      case '4q':
        return <FourQuadrants/>;
      case 'hp':
        return <HotProperties/>;
      case 'pg':
        return <PointsGame/>;
      case 'cl':
        return <ChecklistReport/>;
      default:
        return null;
    }
}


class DashboardResults extends Component {
  constructor(props) {
    super(props);
  }

  onTabClick = (item, e) => {
    this.props.action.selectTab(item.get('name'));
  };

  componentDidMount(){
    this.props.action.loadDashbaordResults();
  }

  render() {
    return (
      <div>
        <div className="wb-resutl-tab-wrapper-first">
          {this.props.tabs.map((item, index) => {
            if(item.get('row') == 'first'){
              return <div key={`item-${index}`} className= {(item.get('active')) ? 'wb-results-tab wb-results-tab-active': 'wb-results-tab'} onClick={(e) => this.onTabClick(item, e)}>{item.get('title')}</div>
            }
          })}
        </div>
        <div className="wb-resutl-tab-wrapper-second">
          {this.props.tabs.map((item, index) => {
            if(item.get('hasAccess') && item.get('row') == 'second'){
              return <div key={`item-${index}`} className= {(item.get('active')) ? 'wb-results-tab wb-results-tab-active': 'wb-results-tab'} onClick={(e) => this.onTabClick(item, e)}>{item.get('title')}</div>
            }
          })}
        </div>
        <div className="yui3-g wb-results-panel">
            <div className="yui3-g-r wb-results-body"> 
              <DashBoardResultBody tabName={this.props.activeTab}/>
            </div>
        </div>
      </div>
    );
  }
}




let mapStateToProps = (state, prop) => {
    return {
        tabs: state.DashboardResults.get('tabs'),
        activeTab: state.DashboardResults.get('activeTab'),
    }
}
let mapDispatchToProps = (dispatch) => {
    return {
        action: bindActionCreators(DashboardResultsAction, dispatch)
    }
}
const DashboardResultsComponent = connect(mapStateToProps, mapDispatchToProps)(DashboardResults);
export default DashboardResultsComponent;


window.WB.react.unmountDashboardResultsComponent = () => {
    if(document.getElementById('dashbaordResults') != null) {
        unmountComponentAtNode(document.getElementById('dashbaordResults'));
    }
}

window.WB.react.renderDashboardResultsComponent= function() {
    render(
      <Provider store={store}>
        <DashboardResultsComponent/>
      </Provider>, document.getElementById('dashbaordResults')
    );
}


window.WB.react.renderDashboardResults = {
    updateChecklistAccess: () => {
      if(WB.env.currentRole.name == 'Leader') {
        store.dispatch(DashboardResultsAction.updateChecklistAccess({hasAccess: true}));
      } else {
        store.dispatch(DashboardResultsAction.updateChecklistAccess({hasAccess: false}));
      }
      
    }
}
