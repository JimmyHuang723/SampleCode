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
    for (var i = 0 ; i <5; i++) {
          output.push(<MessageChat key={i} 
                                   name={"Jimmy"} 
                                   online={true} 
                                   message={"hihihi"}
                                   time="Wed. 22:20"
                                   pic_url="http://dreamicus.com/data/image/image-07.jpg"
                                   />);
    }
    for (i = 5 ; i <20; i++) {
          output.push(<MessageAnnouncement key={i} message={"Someone has joined..."} />);
    }
    for (i = 20 ; i <100; i++) {
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
