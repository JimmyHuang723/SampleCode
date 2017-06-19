import React from 'react';
import PropTypes from 'prop-types';


class MessageChat extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
     
    };
  }
  
  
  
  render() {
    return (
      <div className="message-chat">
        <img src={this.props.pic_url} className="img-circle" alt="" width="60" height="60"/>
        &nbsp; 
        <span style={{fontWeight: "bold"}} >{this.props.name}</span>
        &nbsp; &nbsp; 
        <span style={{fontStyle: "italic"}}>{this.props.time}</span>
        <br/>
        &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
        {this.props.message}
      </div>
    );
  }
}


MessageChat.propTypes = {
  name : PropTypes.string,
  online : PropTypes.bool,
  message : PropTypes.string,
  time : PropTypes.string,
  pic_url : PropTypes.string,
};



export default MessageChat;
