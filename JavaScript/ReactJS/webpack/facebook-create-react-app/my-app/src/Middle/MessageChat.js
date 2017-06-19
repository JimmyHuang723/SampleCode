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
      </div>
    );
  }
}


MessageChat.propTypes = {
  name : PropTypes.string,
  online : PropTypes.bool,
  message : PropTypes.string,
  time : PropTypes.string,
  picture : PropTypes.string,
};



export default MessageChat;
