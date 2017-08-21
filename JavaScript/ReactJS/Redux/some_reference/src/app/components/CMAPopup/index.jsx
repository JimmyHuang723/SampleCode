import React, {Component} from 'react';
import { render, unmountComponentAtNode } from 'react-dom';
import {List, Map, fromJS } from 'immutable';
import Modal from 'react-modal';
import Spinner from 'react-spinner';

import { bindActionCreators } from 'redux';
import { Provider, connect } from 'react-redux';
import * as CMAPopupAction from '../../redux/modules/cmaPopup';
import store from '../../redux/store';

import CMAList from './CMAList';
import CMAFilter from './CMAFilter';

const customStyles = {
  overlay : {
    position          : 'fixed',
    top               : 0,
    left              : 0,
    right             : 0,
    bottom            : 0,
    backgroundColor   : 'rgba(49, 49, 51, 0.5)',
    zIndex            : 1008
  },
  content : {
    top                   : '50%',
    left                  : '50%',
    right                 : 'auto',
    bottom                : 'auto',
    marginRight           : '-50%',
    transform             : 'translate(-50%, -50%)',
    background            : 'none',
    border                : '0px'
  }
};

class CMAPopup extends Component {
    constructor(props) {
        super(props);
    }
    
    closeModal = () => {
        this.props.action.closeComparables();
    };

    onSelectComparable = (updatedComparable) => {
        this.props.action.selectComparable(updatedComparable);
    }

    onFilterComparables = (filters) => {
        filters = filters.set('taskId', this.props.taskId);
        filters = filters.set('apmPid', this.props.apmPid);
        this.props.action.filterComparables(filters);
    }

    onSave = () => {
        let selectedComparables = List();
        this.props.comparables.map(item => {
            if(item.get('selected')) {
               selectedComparables = selectedComparables.push(item);
            }
        })
        this.props.action.saveSelectedComparables(selectedComparables, this.props.taskId);
    }

    followUpPresentation = (type) => {
        let presentationData = this.props.presentationData.toJS();
        let taskId = this.props.taskId;
        WB.my.reactDashboardCommon.showPresentationFollowUpDraft(presentationData, type, taskId);
    }
    
    onCheckAll = (blnCheckAll) => {

        this.props.action.selectAllComparables(blnCheckAll);
    }

    getParent = () => {
        return document.querySelector('#cmaModalWrapper');
    }
    render() {
         return (
             
                <Modal
                    isOpen={this.props.modalIsOpen}
                    onRequestClose={this.closeModal}
                    style={customStyles}
                    contentLabel="Example Modal"
                    parentSelector={this.getParent}
                >
                    <div className="yui3-widget yui3-widget-positioned yui3-widget-stacked wb-cma-popup yui3-dd-draggable" style={{'width': '800px'}}>
                        
                        <div className="yui3-overlay-content yui3-widget-stdmod">
                                <div className="yui3-widget-hd">
                                    <strong title="pod:enquiryNew v1.0 September 2012 enquiry new ©Wiseberry">{this.props.title}</strong> <em></em>
                                    <em style={{float:'right'}}><a className="wb-close  form-action" title="close" onClick={this.closeModal}><i className="icon-remove-sign txt-white"></i></a></em>
                                </div>
                                <div className="yui3-widget-bd">
                                    <CMAFilter onFilterComparables={this.onFilterComparables} selectAll={this.props.selectAll} onCheckAll={this.onCheckAll}/>
                                    <hr/>
                                    <div style={{'height': '400px', 'overflow': 'auto'}}>
                                        <CMAList items={this.props.comparables} onSelectComparable={this.onSelectComparable}/>
                                    </div>
                                    {this.props.processing && <Spinner/>}
                                </div>
                                <div className="yui3-widget-ft">
                                    <span className="wb-selfPdf" style={{float: 'left', paddingRight: '10px'}}>
                                        <a href={this.props.propertyReportURL} target="_blank"><i className="fa fa-2x fa-file-pdf-o"></i>&nbsp;Property Report</a>
                                    </span>
                                    <span className="wb-cma" style={{float: 'left'}}>
                                        <a href={this.props.cmaPrintURL} target="_blank"><i className="fa fa-2x fa-print"></i>&nbsp;CMA</a>
                                    </span>
                                    {
                                        this.props.presentationData.get('notListedEmailDraft') && 
                                        <a className="btn wb-follow-up-not-listed btn-primary" title="Follow-up not listed" onClick={(e) => this.followUpPresentation('not-listed')}>
                                            <em></em><span>Follow-up not listed</span>
                                        </a> 
                                    }
                                    &nbsp;
                                    {
                                        this.props.presentationData.get('listedEmailDraft') && 
                                        <a className="btn wb-follow-up-listed btn-primary" title="Follow-up listed" onClick={(e) => this.followUpPresentation('listed')}>
                                            <em></em><span>Follow-up listed</span>
                                        </a>
                                    }
                                    &nbsp;
                                    <a className="btn wb-save btn-primary" title="save" onClick={this.onSave}>
                                        <em></em><span>Save & Send to App</span>
                                    </a> 
                                </div>
                        </div>
                        
                    </div>
                </Modal>
                
        );
    }
}

let mapStateToProps = (state, prop) => {
    return {
        title: state.CMAPopup.get('title'),
        modalIsOpen: state.CMAPopup.get('modalIsOpen'),
        taskId: state.CMAPopup.get('taskId'),
        selectAll: state.CMAPopup.get('selectAll'),
        comparables: state.CMAPopup.get('comparables'),
        processing: state.CMAPopup.get('processing'),
        apmPid: state.CMAPopup.get('apmPid'),
        propertyReportURL: state.CMAPopup.get('propertyReportURL'),
        cmaPrintURL: state.CMAPopup.get('cmaPrintURL'),
        presentationData: state.CMAPopup.get('presentationData')
    }
}
let mapDispatchToProps = (dispatch) => {
    return {
        action: bindActionCreators(CMAPopupAction, dispatch)
    }
}
const CMAPopupComponent = connect(mapStateToProps, mapDispatchToProps)(CMAPopup);
export default CMAPopupComponent;

window.WB.react.unmountCMAPopupComponent = () => {
    if(document.getElementById('cmaModalWrapper') != null) {
        unmountComponentAtNode(document.getElementById('cmaModalWrapper'));
    }
}

window.WB.react.renderCMAPopupComponent = () => {
    render(
        <Provider store={store}>
            <CMAPopupComponent/>
        </Provider>, 
        document.getElementById('cmaModalWrapper')
    );
}

window.WB.react.loadCMAPopup = (data) => {
    store.dispatch(CMAPopupAction.loadComparables(data.taskId));
}

window.WB.react.loadFollowUpPresentationData = (taskId) => {
    store.dispatch(CMAPopupAction.loadFollowUpPresentationData(taskId));
}