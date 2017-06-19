import React from 'react';
import MessageList from './Middle/MessageList';


class Middle extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
     
    };
    
   
  }

  

  render() {
    return (
      <div className="middle-css">
        
        <MessageList/>
     	</div>
    );
  }
}



export default Middle;
