import React, {Component} from 'react';
import Spinner from 'react-spinner';

import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';

import * as HotPropertiesAction from '../../redux/modules/hotProperties';
import * as HotPropertySelectionPopupAction from '../../redux/modules/hotPropertySelectionPopup';
import WBSimpleDataTable from '../WBSimpleDataTable/WBSimpleDataTable';
import HotPropertySelectionPopupComponent from './HotPropertySelectionPopup';

class HotProperties extends Component {
  constructor(props) {
    super(props);
     this.state = { 
        columns: [
            { field: 'id', title: '', type: 'checkbox' },
            { field: 'address', title: 'Address'},
            { field: 'price', title: 'Price', width: '100px',  cssClass: 'align-center'},
        ]
    };
  }

 componentDidMount() {
    this.props.action.loadHotProperties();
  }

  onRowItemClick = (e, column, item) => {
    let selectedListing = item.toJS();
    WB.my.reactDashboardCommon.showListingDetail(selectedListing);
  }

 onRemove = () => {
     let items = this.props.data.filter((item) => {
         return item.get('checked');
     });
     this.props.action.removeHotProperties(items);
 }

 checkItem = (item) => {
     this.props.action.checkHotPorperty(item);
 }

 checkAll = (blnCheckAll) => {
    let items = this.props.data.map(item => {
        return item.set('checked', blnCheckAll);
    });
    this.props.action.checkAllHotProperties(items);
 }


 sort = (sortedItems) => {
     this.props.action.sortHotProperties(sortedItems);
 }



 onAddClick = () => {
     this.props.actionHotPropertySelection.loadHotPropertySelection();
 }

  render() {
    return (
      <div>
        <div className="yui3-g-r wb-header">
            <table className="wb-header-table">
                <tbody>
                <tr>
                    <td style={{textAlign:'center'}}>
                        <span className="wb-header-label">HOT PROPERTIES</span>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <div className="wb-header-action" style={{position: 'absolute', top: '5px', right: '2px'}}>
            <a className="btn-normal" title=""><span id="yui_3_9_0pr3_1_1497520125005_8349" onClick={this.onAddClick}>ADD</span></a>&nbsp;
            <a className="btn-normal" title=""><span id="yui_3_9_0pr3_1_1497520125005_8349" onClick={this.onRemove}>REMOVE</span></a>
        </div>
        <div className="yui3-g-r wb-hot-properties">
            <div className="yui3-u-1">
                {this.props.processing && <Spinner/>}
                <WBSimpleDataTable height="275px" width="100.1%" 
                    columns={this.state.columns} 
                    data={this.props.data} 
                    checkItem={this.checkItem} 
                    checkAll={this.checkAll}
                    sort={this.sort}
                    itemClick={this.onRowItemClick}/>}
            </div>
        </div>
        <div id="HotPropertiesModalWrapper">
            {this.props.showPropertySelection && <HotPropertySelectionPopupComponent/>}
        </div>
      </div>
    );
  }
}



let mapStateToProps = (state, prop) => {
    return {
        data: state.HotProperties.get('data'),
        processing: state.HotProperties.get('processing'),
        showPropertySelection: state.HotPropertySelectionPopup.get('modalIsOpen')
    }
}
let mapDispatchToProps = (dispatch) => {
    return {
        action: bindActionCreators(HotPropertiesAction, dispatch),
        actionHotPropertySelection: bindActionCreators(HotPropertySelectionPopupAction, dispatch)
    }
}
const HotPropertiesComponent = connect(mapStateToProps, mapDispatchToProps)(HotProperties);
export default HotPropertiesComponent;