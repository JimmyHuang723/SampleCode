import React, { Component } from 'react';
import Left from './Left';
import Middle from './Middle';


class App extends Component {
  render() {
    return (
      <div className="container width-height-full">
        <div className="row width-height-full">	
      	  <Left data={APP_DATA.left} />
      	  <Middle data={APP_DATA.middle} />
     	</div>
      </div>
    );
  }
}

var APP_DATA = {
  left : {
    member_list : [
      { name: "Jimmy", online: true},
      { name: "Lucy", online: false},
      { name: "Michael", online: true},
    ]
  }, 
  
  middle : {
    message_list : [
      { 
        type:"chat",
        name: "Jimmy", online: false, 
        message: "Instagram is one of the poster children for social media site successes. Founded in 2010, the photo sharing site now supports upwards of 90 million active photo-sharing users.As with every social media site, part of the fun is that photos and comments appear instantly so your friends can engage while the moment is hot.  Recently, at PyCon 2013 last month, Instagram engineer", 
        time : "Wed. 22:20", pic_url : "http://dreamicus.com/data/image/image-07.jpg"
      },
      { 
        type:"chat", 
        name: "Jack", online: false, 
        message: "Instagram is one of the poster.", 
        time : "Wed. 22:20", pic_url : "https://amazingslider.com/wp-content/uploads/2012/12/dandelion.jpg"
      },
      { 
        type:"chat",
        name: "Lucy", online: false, 
        message: "hey", 
        time : "Wed. 22:20", pic_url : "http://dreamicus.com/data/image/image-07.jpg"
      },
    ],
    type_area : {

    }
  }
};


export default App;
