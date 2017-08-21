import React, {Component} from 'react';
import Spinner from 'react-spinner';

import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';

import * as PointsGameAction from '../../redux/modules/pointsGame';
import WBSimpleDataTable from '../WBSimpleDataTable/WBSimpleDataTable';

class PointsGame extends Component {
  constructor(props) {
    super(props);
     this.state = { 
        columns: [
            { field: 'sp', title: 'SP'},
            { field: 'points', title: 'Points', width: '40px',  cssClass: 'align-center'},
        ]
    };
  }

  sort = (sortedItems) => {
     this.props.action.sortPointsGame(sortedItems);
  }

 componentDidMount() {
    this.props.action.loadPointsGame();
 }

  render() {
    return (
      <div>
        <div className="yui3-g-r wb-header">
            <table className="wb-header-table">
                <tbody>
                <tr>
                    <td style={{textAlign:'center'}}><span className="wb-header-label">POINTS GAME</span><span style={{float:'right'}}>Days Left: {this.props.daysLeft}</span></td>
                </tr>
                
                </tbody>
            </table>
        </div>
        <div className="yui3-g-r wb-aaa-buyers">
            <div className="yui3-u-1">
                {this.props.processing && <Spinner/>}
                {!this.props.processing && <WBSimpleDataTable height="290px" width="100.1%" sort={this.sort} columns={this.state.columns} data={this.props.data}/>}
            </div>
        </div>
      </div>
    );
  }
}



let mapStateToProps = (state, prop) => {
    return {
        daysLeft: state.PointsGame.get('daysLeft'),
        data: state.PointsGame.get('data'),
        processing: state.PointsGame.get('processing')
    }
}
let mapDispatchToProps = (dispatch) => {
    return {
        action: bindActionCreators(PointsGameAction, dispatch)
    }
}
const PointsGameComponent = connect(mapStateToProps, mapDispatchToProps)(PointsGame);
export default PointsGameComponent;