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

    this.props.member_list.forEach((member, index) => {
      output.push(<Member key={index} name={member.name} online={member.online}/>);
    });

    return (
      <div>
        {output}
      </div>
    );
  }
}



export default MemberList;
