import React, {Component} from 'react';
import { render, unmountComponentAtNode } from 'react-dom';
import {List, Map, fromJS } from 'immutable';
import Modal from 'react-modal';
import Spinner from 'react-spinner';

import { bindActionCreators } from 'redux';
import { Provider, connect } from 'react-redux';
import * as HotPropertySelectionPopupAction from '../../redux/modules/hotPropertySelectionPopup';
import store from '../../redux/store';

import WBSimpleDataTable from '../WBSimpleDataTable/WBSimpleDataTable';


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

class HotPropertySelectionPopup extends Component {
    constructor(props) {
        super(props);
        this.state = { 
        columns: [
                { field: 'id', title: '', type: 'checkbox' },
                { field: 'address', title: 'Address'},
                { field: 'locationName', title: 'Suburb', width: '100px'},
                { field: 'salesPerson', title: 'SP'},
                { field: 'valuationFrom', title: 'From'},
                { field: 'valuationTo', title: 'To'}
            ]
        };
    }
    
    closeModal = () => {
         this.props.action.closeHotPropertySelection();
    };

    checkItem = (item) => {
        this.props.action.checkHotPorpertySelection(item);
    }

    checkAll = (blnCheckAll) => {
        let items = this.props.properties.map(item => {
            return item.set('checked', blnCheckAll);
        });
        this.props.action.checkAllHotPropertySelection(items);
    }

    sort = (sortedItems) => {
        this.props.action.sortHotPropertySelection(sortedItems);
    }

    itemClick = (item) => {
        console.log(item);
    }

    onSelectProperty = (updatedComparable) => {
        this.props.action.selectProperty(updatedProperty);
    }

    onFilterComparables = (filters) => {
        // filters = filters.set('taskId', this.props.taskId);
        // filters = filters.set('apmPid', this.props.apmPid);
        // this.props.action.filterComparables(filters);
    }

    onSave = () => {
        let propertyIds = [];
        let items = this.props.properties.filter((item) => {
            if(item.get('checked')) {
                propertyIds.push(item.get('id'));
            };
        });
        this.props.action.addSelectedProperites(propertyIds);
    }

    getParent = () => {
        return document.querySelector('#HotPropertiesModalWrapper');
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
                                    <strong title="pod:enquiryNew v1.0 September 2012 enquiry new ©Wiseberry">Add Hot Properties</strong> <em></em>
                                    <em style={{float:'right'}}><a className="wb-close  form-action" title="close" onClick={this.closeModal}><i className="icon-remove-sign txt-white"></i></a></em>
                                </div>
                                <div className="yui3-widget-bd" style={{padding: '0px'}}>
                                    {/*<CMAFilter onFilterComparables={this.onFilterComparables} selectAll={this.props.selectAll} onCheckAll={this.onCheckAll}/>
                                    <hr/>*/}
                                    <div style={{'height': '400px'}}>
                                        {/*<HotPropertySelectionList items={this.props.properties} onSelectProperty={this.onSelectProperty}/>*/}
                                        {this.props.processing && <Spinner/>}    
                                        {!this.props.processing && <WBSimpleDataTable height="100%" width="100.1%" 
                                            columns={this.state.columns} 
                                            data={this.props.properties} 
                                            checkItem={this.checkItem} 
                                            checkAll={this.checkAll}
                                            sort={this.sort}
                                            itemClick={this.itemClick}/>}
                                    </div>
                                   
                                </div>
                                <div className="yui3-widget-ft" id="yui_3_9_0pr3_1_1496900252958_49254">
                                    <a className="btn wb-save btn-primary" title="save" onClick={this.onSave}>
                                        <em></em><span>Save</span>
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
        modalIsOpen: state.HotPropertySelectionPopup.get('modalIsOpen'),
        selectAll: state.HotPropertySelectionPopup.get('selectAll'),
        properties: state.HotPropertySelectionPopup.get('properties'),
        processing: state.HotPropertySelectionPopup.get('processing'),
    }
}
let mapDispatchToProps = (dispatch) => {
    return {
        action: bindActionCreators(HotPropertySelectionPopupAction, dispatch)
    }
}
const HotPropertySelectionPopupComponent = connect(mapStateToProps, mapDispatchToProps)(HotPropertySelectionPopup);
export default HotPropertySelectionPopupComponent;

/*window.WB.react.unmountCMAPopupComponent = () => {
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
}*/