import React from 'react';
import PropTypes from 'prop-types';


class TypeArea extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
     
    };
  }
  
  
  
  render() {
    return (
      <div className="type-area">
        <textarea className="form-control" name="typearea" rows="5" placeholder="Type a message..."/>        
        <button type="button" className="btn btn-primary btn-block">Send</button>
      </div>
    );
  }
}


TypeArea.propTypes = {
 
};



export default TypeArea;
