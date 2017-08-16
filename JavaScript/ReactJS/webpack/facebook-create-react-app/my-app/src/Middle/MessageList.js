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
      if(message.type == "chat"){    
        output.push(<MessageChat 
                       key={index} 
                       name={message.name} 
                       online={message.online} 
                       message={message.message}
                       time={message.time}
                       pic_url={message.pic_url}
                       mentioned={message.mentioned}
                    />);
      }else if(message.type == "announcement"){
        output.push(<MessageAnnouncement key={index} message={message.message} />);
      }else{

      }

    });

    return (
      <div id="messageList" className="message-list">
        {output}
      </div>
    );
  }
}



export default MessageList;
