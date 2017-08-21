import React, {Component} from 'react';
import {List, Map, fromJS } from 'immutable';
import {render, unmountComponentAtNode} from 'react-dom';
import Spinner from 'react-spinner';
import {SortableContainer, SortableElement, arrayMove} from 'react-sortable-hoc';
import axios from 'axios';

import { bindActionCreators } from 'redux';
import { Provider, connect } from 'react-redux';
import * as ScrapPaperAction from '../../redux/modules/scrapPaper';
import store from '../../redux/store';

import ScrapList from './ScrapList';


const ScrapPaperList = SortableContainer(({items, onItemComplete}) => {
  return (
    <ScrapList items={items} onItemComplete ={onItemComplete}/>
  );
});

class ScrapPaper extends Component {
  constructor(props) {
    super(props);
    this.state = { newItemText: ''};
  }

 
  //called when remove buton is clicked
  onRemove = () => {
    this.props.action.removeItems(this.props.items);
  };

  //called when add button clicked
  onAddNewItem = () => {
    this.addNewItem(this.state.newItemText);
  };

  //called when user hit enter in textbox to add new
  onEnterAddNew = (event) => {
    if(event.key === 'Enter') {
      this.addNewItem(this.state.newItemText);
    }
  }

  //adds new item to items collection 
  addNewItem = (itemText) => {
    if(itemText.trim() != '') {
      this.props.action.addNewItem(itemText, this.props.items);
      this.setState({ newItemText: '' });
    }
  };
  
  //called when text box value changes
  onNewItemTextChange = (event) => {
     this.setState({newItemText: event.target.value});
  };

  //called when check all checkbox is clicked
  onCheckAll = () => {
    this.props.action.completeAllItems(this.props.items, !this.props.allComplete);
  }

  //called when user drags and drops item
  onSortEnd = ({oldIndex, newIndex}) => {
    let items = this.props.items.toJS();
    items = arrayMove(items, oldIndex, newIndex);
    items = fromJS(items);
    items = items.map((item, index) => item.set('seq', index));
    this.props.action.sortItems(items);
  };

  //called when check box next to item is clicked
  onItemComplete = (updatedItem) => {
    this.props.action.completeItem(updatedItem);
  };

 componentDidMount() {
   if (this.props.items && this.props.items.size <=0) {
     this.props.action.loadItems();
   }
 }

  render() {
    return (
      <div className="scrap-paper-wrapper">
        <div className="yui3-g-r wb-header-black">
            <table className="wb-header-table">
              <tbody>
                <tr>
                  <td><span className="wb-header-label">SCRAP PAPER</span></td>
                  <td style={{textAlign:'right'}}> 
                      <a className="btn-normal" title="" onClick={this.onAddNewItem} href="#"><span>ADD</span></a>&nbsp;&nbsp;
                      <a className="btn-normal" title="" onClick={this.onRemove} href="#"><span>REMOVE</span></a>&nbsp;
                  </td>
                </tr>
              </tbody>
            </table>
        </div>
        <div className="yui3-g-r wb-scrap-paper-body"> 
            <div className="yui3-g-r" style={{marginBottom:'5px'}}>
                <input type="checkbox" className="wb-todo-check-all" checked={this.props.allComplete} onChange={this.onCheckAll}/>
                <input type="text" className="wb-new-todo input-text-no-border" value={this.state.newItemText} onChange={this.onNewItemTextChange} onKeyPress={this.onEnterAddNew}/>
            </div>
            <div className="yui3-g-r" style={{height: '70%'}}> 
                <ScrapPaperList items={this.props.items} onSortEnd={this.onSortEnd} onItemComplete={this.onItemComplete} helperClass="sortableHelper"/>
                {this.props.processing && <Spinner/>}
            </div>
        </div>
      </div>
    );
  }
}


let mapStateToProps = (state, prop) => {
    return {
        processing: state.ScrapPaper.get('processing'),
        allComplete: state.ScrapPaper.get('allComplete'),
        items: state.ScrapPaper.get('items')
    }
}

let mapDispatchToProps = (dispatch) => {
    return {
        action: bindActionCreators(ScrapPaperAction, dispatch)
    }
}

const ScrapPaperComponent = connect(mapStateToProps, mapDispatchToProps)(ScrapPaper);
export default ScrapPaperComponent;


window.WB.react.unmountScrapPaperComponent = () => {
    if(document.getElementById('scrapPaperWrapper') != null) {
        unmountComponentAtNode(document.getElementById('scrapPaperWrapper'));
    }
}

window.WB.react.renderScrapPaperComponent = function() {
    render(
        <Provider store={store}>
            <ScrapPaperComponent/>
        </Provider>, 
        document.getElementById('scrapPaperWrapper')
    );
}