import React from 'react';
import PropTypes from 'prop-types';


class TypeArea extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
     
    };

    // This binding is necessary to make `this` work in the callback
    this.handleClick = this.handleClick.bind(this);
  }
  
  handleClick() {
    this.props.on_send(document.getElementById("middle_textarea").value);
    document.getElementById("middle_textarea").value = "";
  }
  
  render() {
    return (
      <div className="type-area">
        <textarea id="middle_textarea" className="form-control" name="typearea" rows="5" placeholder="Type a message..."/>        
        <button type="button" className="btn btn-primary btn-block" onClick={this.handleClick}>Send</button>
      </div>
    );
  }
}


TypeArea.propTypes = {
 
};



export default TypeArea;
