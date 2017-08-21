import React, {Component} from 'react';
import Spinner from 'react-spinner';

import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';

import * as PendingSalesAction from '../../redux/modules/pendingSales';
import WBSimpleDataTable from '../WBSimpleDataTable/WBSimpleDataTable';



class PendingSales extends Component {
  constructor(props) {
    super(props);
     this.state = { 
        columns: [
            { field: 'date', title: 'Date', width: '60px',  cssClass: 'align-center'},
            { field: 'address', title: 'Address', width: '100px'},
            { field: 'priceAgreed', title: 'Price', width: '80px',  cssClass: 'align-center'},
            { field: 'exchDate', title: 'Exch.', width: '60px',  cssClass: 'align-center'}
        ]
    };
  }

  sort = (sortedItems) => {
     this.props.action.sortPendingSales(sortedItems);
  }

 componentDidMount() {
    this.props.action.loadPendingSales();
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
                    <td style={{textAlign:'center'}}><span className="wb-header-label">PENDING SALES</span></td>
                </tr>
                </tbody>
            </table>
        </div>
        <div className="yui3-g-r wb-aaa-buyers">
            <div className="yui3-u-1">
                {this.props.processing && <Spinner/>}
                {!this.props.processing && <WBSimpleDataTable height="290px" width="100.1%" sort={this.sort} 
                columns={this.state.columns} data={this.props.data}
                itemClick={this.onRowItemClick}/>}
            </div>
        </div>
      </div>
    );
  }
}



let mapStateToProps = (state, prop) => {
    return {
        data: state.PendingSales.get('data'),
        processing: state.PendingSales.get('processing')
    }
}
let mapDispatchToProps = (dispatch) => {
    return {
        action: bindActionCreators(PendingSalesAction, dispatch)
    }
}
const PendingSalesComponent = connect(mapStateToProps, mapDispatchToProps)(PendingSales);
export default PendingSalesComponent;