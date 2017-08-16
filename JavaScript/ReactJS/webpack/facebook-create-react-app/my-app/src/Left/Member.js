import React from 'react';
import PropTypes from 'prop-types';

import Tooltip from 'rc-tooltip'; // https://github.com/react-component/tooltip
import 'rc-tooltip/assets/bootstrap.css';

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
            <Tooltip
              placement={'right'}
              mouseEnterDelay={0}
              mouseLeaveDelay={0.1}
              destroyTooltipOnHide={false}
              trigger={Object.keys( {hover: 1} ) }               
              overlay={<div style={{ height: 20, width: 170 }}>Click to mention this person</div>}
              align={{
                offset: [4, 0],
              }}
              transitionName={"transitionName"}
             >
               <div className="member" onClick={this.onClickMember}>
                 {this.props.name}
               </div>
             </Tooltip>   
    );
  }
}


Member.propTypes = {
  name: PropTypes.string,
  online : PropTypes.bool,
};


export default Member;
