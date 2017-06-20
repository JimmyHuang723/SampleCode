import React, { Component } from 'react';
import Left from './Left';
import Middle from './Middle';


class App extends Component {
  render() {
  	// this.props.xxx
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
      { name: "Micahael", online: true},
    ]
  }, 
  
  middle : {
    message_list : [
      { name: "Jimmy", online: false, message: "hey", time : "Wed. 22:20", pic_url : "http://dreamicus.com/data/image/image-07.jpg"},
      { name: "Jack", online: false, message: "hey", time : "Wed. 22:20", pic_url : "https://amazingslider.com/wp-content/uploads/2012/12/dandelion.jpg"},
      { name: "Lucy", online: false, message: "hey", time : "Wed. 22:20", pic_url : "http://dreamicus.com/data/image/image-07.jpg"},
    ],
    type_area : {

    }
  }
};


export default App;
