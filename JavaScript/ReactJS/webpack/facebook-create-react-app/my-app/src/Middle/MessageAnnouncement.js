import React from 'react';
import PropTypes from 'prop-types';


class MessageAnnouncement extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
     
    };
  }
  
  
  
  render() {
    return (
      <div className="message-announcement">
          {this.props.message}
      </div>
    );
  }
}

MessageAnnouncement.propTypes = {
  message : PropTypes.string,

};

export default MessageAnnouncement;
