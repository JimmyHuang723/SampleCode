import React from 'react';
import MessageChat from './MessageChat';


class MessageList extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
     
    };
  }
  
  
  
  render() {
    var output = [];
    for (var i = 0 ; i <100; i++) {
          output.push(<MessageChat/>);
    }

    return (
      <div>
        {output}
      </div>
    );
  }
}



export default MessageList;
