import React from 'react';
import PropTypes from 'prop-types';


class Member extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
     
    };

    this.onClickMember = this.onClickMember.bind(this);
  }
  
  onClickMember() {
    this.props.on_click_member(this.props.name);
  }
  
  render() {
    return (
      <div className="member" onClick={this.onClickMember}>
        {this.props.name}
      </div>
    );
  }
}


Member.propTypes = {
  name: PropTypes.string,
  online : PropTypes.bool,
};


export default Member;
