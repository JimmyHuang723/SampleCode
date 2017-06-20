import React from 'react';
import MessageChat from './MessageChat';
import MessageAnnouncement from './MessageAnnouncement';
import PropTypes from 'prop-types';

class MessageList extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
     
    };
  }
  
  
  
  render() {
    var output = [];

    this.props.message_list.forEach((message, index) => {
      output.push(<MessageChat 
                     key={index} 
                     name={message.name} 
                     online={message.online} 
                     message={message.message}
                     time={message.time}
                     pic_url={message.pic_url}
                  />);
    });

    // hard code : 
    output.push(<MessageAnnouncement key={999} message={"Someone has joined..."} />);
  

    return (
      <div>
        {output}
      </div>
    );
  }
}



export default MessageList;
