import React, {Component} from 'react';
import {render, unmountComponentAtNode} from 'react-dom';

import { Provider } from 'react-redux';
import store from '../../redux/store';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';

import * as ForecastResultsAction from '../../redux/modules/forecastResults';


class Forecast extends Component {
  constructor(props) {
    super(props);
    this.state = {
        forecast: ''
    };
 
  }

  addForecast = (e) => {
     if(this.state.forecast.trim() != '') {
        this.props.action.addForecast(this.state.forecast);
        this.setState({forecast: ''});
      }
  };

  onAddForecast = (e) => {
      let newForecast = e.target.value;
      this.setState({forecast: newForecast});
  }

  render() {
    
    return (
       <div>
            <div>
                <input type="text" className="input-text" maxLength="25" style={{width: '65%'}} placeholder="Forecast" value={this.state.forecast} onChange={this.onAddForecast}/>&nbsp;&nbsp;
                <a className="btn-normal plus" title="Click to add today's Forecast" onClick={this.addForecast}><div>+</div></a>
            </div>
        </div>
    );
  }
}


let mapStateToProps = (state, prop) => {
    return {
        data: state.ForecastResults.get('data'),
        processing: state.ForecastResults.get('processing')
    }
}
let mapDispatchToProps = (dispatch) => {
    return {
        action: bindActionCreators(ForecastResultsAction, dispatch)
    }
}
const ForecastComponent = connect(mapStateToProps, mapDispatchToProps)(Forecast);
export default ForecastComponent;


window.WB.react.unmountForecastComponent = () => {
    if(document.getElementById('forecastWrapper') != null) {
        unmountComponentAtNode(document.getElementById('forecastWrapper'));
    }
}

window.WB.react.renderForecastComponent = function() {
    render(
        <Provider store={store}>
            <ForecastComponent/>
        </Provider>, 
        document.getElementById('forecastWrapper')
    );
}