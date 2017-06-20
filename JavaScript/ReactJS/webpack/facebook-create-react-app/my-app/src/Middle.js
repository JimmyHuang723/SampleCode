import React from 'react';
import MessageList from './Middle/MessageList';
import TypeArea from './Middle/TypeArea';
import PropTypes from 'prop-types';

class Middle extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      message_list :  props.data.message_list
    };
    
    // This binding is necessary to make `this` work in the callback
    this.handleClick = this.handleClick.bind(this);
  }

  handleClick() {
    var dummy = { 
                  name: "Jimmy", online: false, 
                  message: "Instagram is one of the poster children for social media site successes. Founded in 2010, the photo sharing site now supports upwards of 90 million active photo-sharing users.As with every social media site, part of the fun is that photos and comments appear instantly so your friends can engage while the moment is hot.  Recently, at PyCon 2013 last month, Instagram engineer", 
                  time : "Wed. 22:20", pic_url : "http://dreamicus.com/data/image/image-07.jpg"
                };
        
    this.setState(function(prevState, props) {
      var message_list = prevState.message_list.slice(); // copy array
      message_list.push(dummy);
      return {
        message_list : message_list
      };
    });
  }

  render() {
    return (
      <div className="col-sm-10 middle-css">
        
        <MessageList message_list={this.state.message_list}/>

        <button type="button" className="btn btn-primary btn-block" onClick={this.handleClick}>Get</button>    
        <br/>
        <TypeArea data={this.props.data.type_area} />   
     	</div>
    );
  }
}



export default Middle;
