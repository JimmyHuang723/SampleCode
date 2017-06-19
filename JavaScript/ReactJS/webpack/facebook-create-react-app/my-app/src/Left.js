import React from 'react';
import MemberList from './Left/MemberList';


class Left extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
     
    };
  }
  
  
  
  render() {
    return (
      <div className="left-css">
        <MemberList/>
      </div>
    );
  }
}



export default Left;
