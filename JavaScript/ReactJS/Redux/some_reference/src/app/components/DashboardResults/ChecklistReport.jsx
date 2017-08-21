import React, {Component} from 'react';
import Spinner from 'react-spinner';

import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';

import * as ChecklistReportAction from '../../redux/modules/checklistReport';
import WBSimpleDataTable from '../WBSimpleDataTable/WBSimpleDataTable';

const CompletePercent = function(props) {
    return (
        <div style={{color: 'green'}}>
            {props.item.get('completePercent')}
        </div>
    );
}

const InCompletePercent = function(props) {
    return (
        <div style={{color: 'red'}}>
            {props.item.get('inCompletePercent')}
        </div>
    );
}
class ChecklistReport extends Component {
  constructor(props) {
    super(props);
     this.state = { 
        columns: [
            { field: 'sp', title: 'SP', width: '100px', cssClass: 'align-center' },
            { field: 'completePercent', title: 'Complete', cssClass: 'align-center', customComponent: <CompletePercent/>},
            { field: 'inCompletePercent', title: 'Incomplete', cssClass: 'align-center', customComponent: <InCompletePercent/> }
        ]
    };
  }

  sort = (sortedItems) => {
     this.props.action.sortChecklistReport(sortedItems);
  }


 componentDidMount() {
    this.props.action.loadChecklistReport();
 }

 onRowItemClick = (e, column, item) => {
    // let selectedBuyer = item.toJS();
    // WB.my.reactDashboardCommon.showBuyerEdit(selectedBuyer.id);
 }

 filterChecklistReport = (e) => {
     let periodInDays = e.target.value;
     this.props.action.loadChecklistReport(periodInDays);
 }

 render() {
    return (
      <div>
        <div className="yui3-g-r wb-header">
            <table className="wb-header-table">
                <tbody>
                <tr>
                    <td style={{textAlign:'center'}}><span className="wb-header-label">CHECKLISTS</span></td>
                </tr>
                </tbody>
            </table>
        </div>
        <div className="wb-header-action" style={{position: 'absolute', top: '5px', right: '2px'}}>
            <select style={{height: '20px'}} defaultValue="7" onChange={this.filterChecklistReport}>
                <option value="0">All</option>
                <option value="7" selected>Last 7 days</option>
                <option value="14">Last 2 weeks</option>
                <option value="21">Last 3 weeks</option>
                <option value="28">Last 4 weeks</option>
            </select>
        </div>
        <div className="yui3-g-r wb-checklist-report">
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
        data: state.ChecklistReport.get('data'),
        processing: state.ChecklistReport.get('processing')
    }
}

let mapDispatchToProps = (dispatch) => {
    return {
        action: bindActionCreators(ChecklistReportAction, dispatch)
    }
}

const ChecklistReportComponent = connect(mapStateToProps, mapDispatchToProps)(ChecklistReport);
export default ChecklistReportComponent;