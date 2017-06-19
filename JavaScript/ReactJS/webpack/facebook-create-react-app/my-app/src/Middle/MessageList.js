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
    for (var i = 0 ; i <10; i++) {
          output.push(<MessageChat key={i} />);
    }
    for (var i = 10 ; i <20; i++) {
          output.push(<MessageAnnouncement key={i} message={"test"} />);
    }
    for (var i = 20 ; i <100; i++) {
          output.push(<MessageChat key={i} />);
    }

    return (
      <div>
        {output}
      </div>
    );
  }
}



export default MessageList;
