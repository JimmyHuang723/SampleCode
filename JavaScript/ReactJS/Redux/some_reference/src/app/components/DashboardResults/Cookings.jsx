import React, {Component} from 'react';
import Spinner from 'react-spinner';

import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';

import * as CookingsAction from '../../redux/modules/cookings';
import WBSimpleDataTable from '../WBSimpleDataTable/WBSimpleDataTable';


class Cookings extends Component {
  constructor(props) {
    super(props);
    this.state = { 
        columns: [
            { field: 'date', title: 'Date', width: '60px', cssClass: 'align-center' },
            { field: 'address', title: 'Address' },
            { field: 'sell', title: 'Sell', width: '100px', cssClass: 'align-center' },
            { field: 'buy', title: 'Buy', width: '100px',  cssClass: 'align-center'}
        ]
    };
  }

 sort = (sortedItems) => {
     this.props.action.sortCookings(sortedItems);
 }

 componentDidMount() {
    this.props.action.loadCookings();
 }

 onRowItemClick = (e, column, item) => {
    let selectedListing = item.toJS();
    WB.my.reactDashboardCommon.showListingDetail(selectedListing);
 }

  render() {
    return (
      <div>
        <div className="yui3-g-r wb-header">
            <table className="wb-header-table">
                <tbody>
                <tr>
                    <td style={{textAlign:'center'}}><span className="wb-header-label">COOKINGS</span></td>
                </tr>
                </tbody>
            </table>
        </div>
        <div className="yui3-g-r wb-cookings">
            <div className="yui3-u-1">
                {this.props.processing && <Spinner/>}
                {!this.props.processing && <WBSimpleDataTable height="290px" width="100.1%" 
                sort={this.sort} 
                columns={this.state.columns} 
                data={this.props.data}
                itemClick={this.onRowItemClick}/>}
            </div>
        </div>
      </div>
    );
  }
}



let mapStateToProps = (state, prop) => {
    return {
        data: state.Cookings.get('data'),
        processing: state.Cookings.get('processing')
    }
}
let mapDispatchToProps = (dispatch) => {
    return {
        action: bindActionCreators(CookingsAction, dispatch)
    }
}
const CookingsComponent = connect(mapStateToProps, mapDispatchToProps)(Cookings);
export default CookingsComponent;