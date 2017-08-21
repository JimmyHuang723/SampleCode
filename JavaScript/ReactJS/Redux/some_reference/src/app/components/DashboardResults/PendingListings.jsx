import React, {Component} from 'react';
import Spinner from 'react-spinner';

import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';

import * as PendingListingsAction from '../../redux/modules/pendingListings';
import WBSimpleDataTable from '../WBSimpleDataTable/WBSimpleDataTable';

const CustomShowDetailArrow = (props) => {
    let item = props.item;
    let imgSrc = (item.get('showDetails')) ? '/assets/css/img/ddArrowOpen.png' : '/assets/css/img/ddArrowClose.png';
    let img = null;
    if(item.get('outcome') != '') {
        return <img src={imgSrc} className="wb-show-comment-img" style={{float: 'right', width: '15px', marginTop: '2px', cursor: 'pointer'}}/>;
    } else {
        return null;
    }
    
}

const CustomDetailRow = (props) => {
    let item = props.item;
    return (
        <div>{item.get('outcome')}</div>
    );
}

class PendingListings extends Component {
  constructor(props) {
    super(props);
     this.state = { 
        columns: [
            { field: 'date', title: 'Date', width: '60px', cssClass: 'align-center'},
            { field: 'address', title: 'Address'},
            { field: 'price', title: 'Price', width: '100px', cssClass: 'align-center'},
            { field: 'outcome', title: '', width: '20px', customComponent: <CustomShowDetailArrow/> }
        ]
    };
  }

  sort = (sortedItems) => {
    this.props.action.sortPendingListings(sortedItems);
  }

  componentDidMount() {
    this.props.action.loadPendingListings();
  }

  onRowItemClick = (e, column, item) => {
    if(column.field == 'outcome') {
      if(item.get('showDetails')) {
          item = item.set('showDetails', false);
      } else {
          item = item.set('showDetails', true);
      }
      this.props.action.showPendingListingDetails(item);
    } else {
        let selectedListing = item.toJS();
        WB.my.reactDashboardCommon.showProspectEdit(selectedListing.paId);
    }    
  }

  render() {
    return (
      <div>
        <div className="yui3-g-r wb-header">
            <table className="wb-header-table">
                <tbody>
                <tr>
                    <td style={{textAlign:'center'}}><span className="wb-header-label">PENDING LISTINGS</span></td>
                </tr>
                </tbody>
            </table>
        </div>
        <div className="yui3-g-r wb-aaa-buyers">
            <div className="yui3-u-1">
                {this.props.processing && <Spinner/>}
                {!this.props.processing && <WBSimpleDataTable height="290px" width="100.1%"
                showDetails={this.showDetails} 
                sort={this.sort} columns={this.state.columns} detailRowContent={<CustomDetailRow/>} data={this.props.data}
                itemClick={this.onRowItemClick}/>}
            </div>
        </div>
      </div>
    );
  }
}



let mapStateToProps = (state, prop) => {
    return {
        data: state.PendingListings.get('data'),
        processing: state.PendingListings.get('processing')
    }
}
let mapDispatchToProps = (dispatch) => {
    return {
        action: bindActionCreators(PendingListingsAction, dispatch)
    }
}
const PendingListingsComponent = connect(mapStateToProps, mapDispatchToProps)(PendingListings);
export default PendingListingsComponent;