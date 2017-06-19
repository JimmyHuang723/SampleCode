import React from 'react';
import MessageList from './Middle/MessageList';
import TypeArea from './Middle/TypeArea';
import PropTypes from 'prop-types';

class Middle extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
     
    };
    
   
  }

  

  render() {
    return (
      <div className="col-sm-10 middle-css">
        <MessageList/>
        <TypeArea/>        
     	</div>
    );
  }
}



export default Middle;
