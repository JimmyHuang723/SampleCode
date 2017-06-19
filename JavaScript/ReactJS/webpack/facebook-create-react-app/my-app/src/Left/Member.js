import React from 'react';
import PropTypes from 'prop-types';


class Member extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
     
    };
  }
  
  
  
  render() {
    return (
      <div className="member">
      </div>
    );
  }
}


Member.propTypes = {
  name: PropTypes.string,
  online : PropTypes.bool,
};


export default Member;
