import React, {Component} from 'react';
import {List,Map,fromJS} from 'immutable';
import CMAItem from './CMAItem';


export default class CMAList extends Component {
  constructor(props) {
    super(props);
  }
  render() {
    return (
      <div className="wb-cma-list">
        {this.props.items.map((item, index) => (
          <CMAItem key={`item-${index}`} index={index} item={item} onSelectComparable={this.props.onSelectComparable}/>
        ))}
      </div>
    );
  }
}