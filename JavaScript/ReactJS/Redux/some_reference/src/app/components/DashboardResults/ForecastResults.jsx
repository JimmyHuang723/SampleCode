import React, {Component} from 'react';
import Spinner from 'react-spinner';
import { Map } from 'immutable';

import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';

import * as ForecastResultsAction from '../../redux/modules/forecastResults';
import WBSimpleDataTable from '../WBSimpleDataTable/WBSimpleDataTable';

class ForecastItem extends Component {
  constructor(props) {
    super(props);
  }

  handleItemComplete = () => {
    let updateItem = Map();
    if(this.props.item.get('status') == 'complete') {
      updateItem = this.props.item.set('status','incomplete');
    }
    else if(this.props.item.get('status') == 'incomplete') {
      updateItem = this.props.item.set('status','complete');
    }
    //calls the parent Component function to update state
    this.props.onItemComplete(updateItem);
  };

  render() {
    return (
      <div>
        <div className="wb-forecast-item-checkbox">
          { <input type="checkbox" onChange={this.handleItemComplete} checked={(this.props.item.get('status')=='complete')} disabled={(this.props.item.get('status')=='complete')}/>}
        </div>
        <span className={(this.props.item.get('status') == 'complete') ? 'wb-forecast-item-complete' : ''}>{this.props.item.get('description')}</span>
      </div>
    );
  }
}

class ForecastResults extends Component {
  constructor(props) {
    super(props);
    this.state = { 
        columns: [
            { field: 'id', title: 'Id', type: 'hidden' },
            { field: 'description', title: 'Desc'},
        ]
    };
  }

  onItemComplete = (updatedItem) => {
    this.props.action.completeForecast(updatedItem);
  }

  componentDidMount() {
    this.props.action.loadForecastResults();
  }

  render() {
    return (
      <div>
        <div className="yui3-g-r wb-header">
            <table className="wb-header-table">
                <tbody>
                <tr>
                    <td style={{textAlign:'center'}}><span className="wb-header-label">FORECAST RESULTS</span></td>
                </tr>
                </tbody>
            </table>
        </div>
        <div className="yui3-g-r wb-forecast-results">
            <div className="yui3-u-1"  style={{height: '245px', overflowY: 'auto'}} >

                {this.props.processing && <Spinner/>}
                {
                    this.props.data.map((item, index) => {
                        return <ForecastItem key={`item-${index}`} index={index} item={item} onItemComplete={this.onItemComplete}/>
                    })
                }
            </div>
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
const ForecastResultsComponent = connect(mapStateToProps, mapDispatchToProps)(ForecastResults);
export default ForecastResultsComponent;