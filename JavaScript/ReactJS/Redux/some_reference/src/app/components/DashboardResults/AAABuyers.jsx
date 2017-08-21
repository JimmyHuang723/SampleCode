import React, {Component} from 'react';
import Spinner from 'react-spinner';

import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';

import * as AAABuyersAction from '../../redux/modules/aaaBuyers';
import WBSimpleDataTable from '../WBSimpleDataTable/WBSimpleDataTable';

const CustomPhoneView = (props) => {
    let itemRecord = props.item;
    return (
        <div>
            {itemRecord.get('phone')}
            {itemRecord.get('multiplePhone') && <i className="fa fa-circle wb-dot"></i>}
        </div>
    );
}

class AAABuyers extends Component {
  constructor(props) {
    super(props);
     this.state = { 
        columns: [
            { field: 'name', title: 'Name', width: '130px' },
            { field: 'phone', title: 'Phone', cssClass: 'align-center', customComponent: <CustomPhoneView/> },
            { field: 'price', title: 'Price', cssClass: 'align-center' }
        ]
    };
  }

  sort = (sortedItems) => {
     this.props.action.sortAAABuyers(sortedItems);
  }


 componentDidMount() {
    this.props.action.loadAAABuyers();
 }

 onRowItemClick = (e, column, item) => {
    let selectedBuyer = item.toJS();
    WB.my.reactDashboardCommon.showBuyerEdit(selectedBuyer.id);
 }

  render() {
    return (
      <div>
        <div className="yui3-g-r wb-header">
            <table className="wb-header-table">
                <tbody>
                <tr>
                    <td style={{textAlign:'center'}}><span className="wb-header-label">AAA BUYERS</span></td>
                </tr>
                </tbody>
            </table>
        </div>
        <div className="yui3-g-r wb-aaa-buyers">
            <div className="yui3-u-1">
                {this.props.processing && <Spinner/>}
                {!this.props.processing && <WBSimpleDataTable height="290px" width="100.1%" sort={this.sort}  
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
        data: state.AAABuyers.get('data'),
        processing: state.AAABuyers.get('processing')
    }
}
let mapDispatchToProps = (dispatch) => {
    return {
        action: bindActionCreators(AAABuyersAction, dispatch)
    }
}
const AAABuyersComponent = connect(mapStateToProps, mapDispatchToProps)(AAABuyers);
export default AAABuyersComponent;