import React from 'react';
import MemberList from './Left/MemberList';
import PropTypes from 'prop-types';

class Left extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
     
    };
  }
  
  
  
  render() {
    return (
      <div className="col-sm-2 left-css">
        <div className="" style={{height: "33%"}}/>
        <div className="" style={{height: "33%"}}>
          <MemberList/>
        </div>  
        <div className="" style={{height: "33%"}}/>
      </div>
    );
  }
}



export default Left;
