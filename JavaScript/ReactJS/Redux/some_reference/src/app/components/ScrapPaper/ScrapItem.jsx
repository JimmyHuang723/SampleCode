import React, {Component} from 'react';
import { Map } from 'immutable';

export default class ScrapItem extends Component {
  constructor(props) {
    super(props);
    this.handleItemComplete = this.handleItemComplete.bind(this);
  }

  handleItemComplete = () => {
    let updateItem = Map();
    if(this.props.item.get('status') == 'complete') {
      updateItem = this.props.item.set('status','todo');
    }
    else if(this.props.item.get('status') == 'todo') {
      updateItem = this.props.item.set('status','complete');
    }
    //calls the parent Component function to update state
    this.props.onItemComplete(updateItem);
  };

  render() {
    return (
      <div>
        <input type="checkbox" className="wb-todo-item-checkbox" checked={this.props.item.get('status') == 'complete'}  onChange={this.handleItemComplete}/>
        <span className={(this.props.item.get('status') == 'complete') ? 'wb-todo-item-deleted' : ''}>{this.props.item.get('description')}</span>
      </div>
    );
  }
}