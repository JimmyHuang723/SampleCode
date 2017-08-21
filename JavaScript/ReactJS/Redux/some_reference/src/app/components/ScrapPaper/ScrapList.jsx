import React, {Component} from 'react';
import { List, Map } from 'immutable';
import {SortableContainer, SortableElement, arrayMove} from 'react-sortable-hoc';
import ScrapItem from './ScrapItem';


const ScrapPaperListItem = SortableElement(({item, onItemComplete}) => {
   return (
     <ScrapItem item={item} onItemComplete ={onItemComplete}/>
  );
});

export default class ScrapList extends Component {
  constructor(props) {
    super(props);
  }
  render() {
    return (
      <div className="wb-todo-items">
        {this.props.items.map((item, index) => (
          <ScrapPaperListItem key={`item-${index}`} index={index} item={item} onItemComplete={this.props.onItemComplete}/>
        ))}
      </div>
    );
  }
}