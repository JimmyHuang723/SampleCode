import React from 'react';
import Member from './Member';
import PropTypes from 'prop-types';

class MemberList extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
     
    };
  }
  
  
  
  render() {
    var output = [];
    for (var i = 0 ; i <3; i++) {
          output.push(<Member key={i} name={"Lucy"} online={true}/>);
    }

    return (
      <div>
        {output}
      </div>
    );
  }
}



export default MemberList;
