import React from 'react';
import MessageChat from './MessageChat';
import MessageAnnouncement from './MessageAnnouncement';
import PropTypes from 'prop-types';

class MessageList extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
     
    };
  }
  
  
  
  render() {
    var output = [];
    for (var i = 0 ; i <5; i++) {
          output.push(<MessageChat key={i} 
                                   name={"Jimmy"} 
                                   online={true} 
                                   message={"Instagram is one of the poster children for social media site successes. Founded in 2010, the photo sharing site now supports upwards of 90 million active photo-sharing users.As with every social media site, part of the fun is that photos and comments appear instantly so your friends can engage while the moment is hot.  Recently, at PyCon 2013 last month, Instagram engineer "}
                                   time="Wed. 22:20"
                                   pic_url="http://dreamicus.com/data/image/image-07.jpg"
                                   />);
    }
    for (var i = 5 ; i <10; i++) {
          output.push(<MessageChat key={i} 
                                   name={"Jack"} 
                                   online={true} 
                                   message={"Instagram is one of the poster."}
                                   time="Mon. 09:35"
                                   pic_url="https://amazingslider.com/wp-content/uploads/2012/12/dandelion.jpg"
                                   />);
    }
    for (i = 10 ; i <20; i++) {
          output.push(<MessageAnnouncement key={i} message={"Someone has joined..."} />);
    }
    for (i = 20 ; i <100; i++) {
          output.push(<MessageChat key={i} />);
    }

    return (
      <div>
        {output}
      </div>
    );
  }
}



export default MessageList;
