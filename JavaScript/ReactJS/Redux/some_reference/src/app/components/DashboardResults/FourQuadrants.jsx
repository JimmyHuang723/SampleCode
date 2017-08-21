import React, {Component} from 'react';
import Spinner from 'react-spinner';

import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';

import * as FourQuadrantsAction from '../../redux/modules/fourQuadrants';

const FourQuadrantsData = function(props) {
    return (
        <div>
            <div className="yui3-u-5-8">
                <table className="wb-quadrant-summary"> 
                    <tbody>
                        <tr>
                            <td className={(props.data.getIn(['targets', 'pc', 'achived']) >= props.data.getIn(['targets', 'pc', 'target']))?'wb-quadrant-counts wb-quadrant-green':'wb-quadrant-counts wb-quadrant-red'}>PC<br/><br/>{props.data.getIn(['targets', 'pc', 'achived'])}/{props.data.getIn(['targets', 'pc', 'target'])}</td>

                            <td className={(props.data.getIn(['targets', 'la', 'achived']) >= props.data.getIn(['targets', 'la', 'target']))?'wb-quadrant-counts  wb-quadrant-green':'wb-quadrant-counts wb-quadrant-red'}>LA<br/><br/>{props.data.getIn(['targets', 'la', 'achived'])}/{props.data.getIn(['targets', 'la', 'target'])}</td>
                        </tr>
                        <tr>
                            <td className={(props.data.getIn(['targets', 'ba', 'achived']) >= props.data.getIn(['targets', 'ba', 'target']))?'wb-quadrant-counts  wb-quadrant-green':'wb-quadrant-counts wb-quadrant-red'}>BA<br/><br/>{props.data.getIn(['targets', 'ba', 'achived'])}/{props.data.getIn(['targets', 'ba', 'target'])}</td>
                            <td className={(props.data.getIn(['targets', 'mr', 'achived']) >= props.data.getIn(['targets', 'mr', 'target']))?'wb-quadrant-counts  wb-quadrant-green':'wb-quadrant-counts wb-quadrant-red'}>MR<br/><br/>{props.data.getIn(['targets', 'mr', 'achived'])}/{props.data.getIn(['targets', 'mr', 'target'])}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div className="yui3-u-3-8">
                <table className="wb-quadrant-ratio"> 
                    <tbody>
                        <tr><td colSpan="2">RATIOS</td></tr>
                        <tr><td>P:</td><td>{props.data.getIn(['ratios', 'p', 'achived'])} in {props.data.getIn(['ratios', 'p', 'target'])}</td></tr>
                        <tr><td>L:</td><td>{props.data.getIn(['ratios', 'l', 'achived'])} in {props.data.getIn(['ratios', 'l', 'target'])}</td></tr>
                        <tr><td>S:</td><td>{props.data.getIn(['ratios', 's', 'achived'])} in {props.data.getIn(['ratios', 's', 'target'])}</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    );
}

class FourQuadrants extends Component {
  constructor(props) {
    super(props);
  }

  componentDidMount() {
    this.props.action.loadFourQuadrants();
  }

  render() {
    return (
      <div>
        <div className="yui3-g-r wb-header">
            <table className="wb-header-table">
                <tbody>
                <tr>
                    <td style={{textAlign:'center'}}><span className="wb-header-label">4 QUADRANTS</span></td>
                </tr>
                </tbody>
            </table>
        </div>
        <div className="yui3-g-r wb-quadrants">
            {this.props.processing && <Spinner/>}
            {!this.props.processing && <FourQuadrantsData data = {this.props.data}/>}
        </div>
      </div>
    );
  }
}


let mapStateToProps = (state, prop) => {
    return {
        data: state.FourQuadrants.get('data'),
        processing: state.FourQuadrants.get('processing')
    }
}
let mapDispatchToProps = (dispatch) => {
    return {
        action: bindActionCreators(FourQuadrantsAction, dispatch)
    }
}
const CookingsComponent = connect(mapStateToProps, mapDispatchToProps)(FourQuadrants);
export default CookingsComponent;