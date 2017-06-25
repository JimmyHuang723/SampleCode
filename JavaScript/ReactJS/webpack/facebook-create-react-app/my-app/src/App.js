import React, { Component } from 'react';
import Left from './Left';
import Middle from './Middle';
import io from "socket.io-client"

class App extends Component {

  constructor(props) {
    super(props);
    this.state = {
      APP_DATA : g_APP_DATA 
    };
    
    // This binding is necessary to make `this` work in the callback
    this.socket_send = this.socket_send.bind(this);
    this.socket_recv = this.socket_recv.bind(this);
  }

  componentDidMount(){

    // Connect socket to server : 
    if(!this.socket){
      this.socket = io.connect("http://13.115.255.206:3700");
      console.log("connect to server");

      this.socket.emit('send', { announcement: "XXX has joined the chat..." });
      this.socket.emit('send', { join: "XXX" });

      this.socket.on('message',(res)=>{
        console.dir("Received : " + JSON.stringify(res));
        this.socket_recv(res);
      });
    }

  }

  socket_recv(res) {
    if(res.hasOwnProperty('message')){      
      var new_message = { 
            type: "chat",
            name: "Jimmy", 
            online: true, 
            message: res.message, 
            time : "Wed. 22:20", 
            pic_url : "http://dreamicus.com/data/image/image-07.jpg"
          };
    }else if(res.hasOwnProperty('announcement')){
      var new_message = { 
            type: "announcement",
            message: res.announcement
          };
    }else if(res.hasOwnProperty('join')){
      
    }else{

    }


    if(res.hasOwnProperty('message') || res.hasOwnProperty('announcement')){
      this.setState(function(prevState, props) {
        //var message_list = prevState.APP_DATA.middle.message_list.slice(); // copy array
        //message_list.push(new_message);

        prevState.APP_DATA.middle.message_list.push(new_message);
        return {
          APP_DATA : prevState.APP_DATA
        };
      });
    }
  }

  socket_send(message) {
    if(this.socket){
      this.socket.emit('send', message);
    }else{
      console.log("error : socket not connected!");
    }
  }

  render() {
    return (
      <div className="container width-height-full">
        <div className="row width-height-full">	
      	  <Left data={this.state.APP_DATA.left} on_send={this.socket_send}  />
      	  <Middle data={this.state.APP_DATA.middle} on_send={this.socket_send}  />
     	</div>
      </div>
    );
  }
}

var g_APP_DATA = {
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
