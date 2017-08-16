import React, { Component } from 'react';
import Left from './Left';
import Middle from './Middle';
import Login from './Login';
import io from "socket.io-client"

class App extends Component {

  constructor(props) {
    super(props);
    this.state = {
      APP_DATA : g_APP_DATA,
      user_name : "default user_name",
      page : "login"
    };
    
    // This binding is necessary to make `this` work in the callback
    this.socket_send = this.socket_send.bind(this);
    this.socket_recv = this.socket_recv.bind(this);
    this.on_login = this.on_login.bind(this);
    this.on_click_member = this.on_click_member.bind(this);
  }

  componentDidMount(){


  }

  socket_connect(){
    // Connect socket to server : 
    if(!this.socket){
      this.socket = io.connect("http://jimmyh.hopto.org:3700");
      console.log("connect to server");

      this.socket.emit('send', { announcement: this.state.user_name+" has joined the chat..." });
      this.socket.emit('send', { join: this.state.user_name });

      this.socket.on('message',(res)=>{
        console.dir("Received : " + JSON.stringify(res));
        this.socket_recv(res);
      });
    }
  }

  socket_recv(res) {
    if(res.hasOwnProperty('message')){    
      // Check if user is mentioned : 
      if(res.message.text != undefined && res.message.text.includes("@"+this.state.user_name)){
        var mentioned = true;
      }else{
        var mentioned = false;
      }

      var new_message = { 
            type: "chat",
            name: res.user_name, 
            online: true, 
            message: res.message, 
            time : "Wed. 22:20", 
            pic_url : "http://dreamicus.com/data/image/image-07.jpg",
            mentioned : mentioned
          };

      var imageArrived = (res.message.img != null) ? true:false;
          
    }else if(res.hasOwnProperty('announcement')){
      var new_message = { 
            type: "announcement",
            message: res.announcement
          };
    }else if(res.hasOwnProperty('join')){
      var new_member = { 
            name : res.join,
            online: true
          };
    }else if(res.hasOwnProperty('leave')){
      // nothing
    }else if(res.hasOwnProperty('name_list')){
      var new_members = res.name_list.map((name) => {
        return { 
          name : name,
          online: true
        };
      });
    }else{
      console.log("Error : socket_recv : unknown message");
      return;
    }

    // setState
    if(res.hasOwnProperty('message') || res.hasOwnProperty('announcement')){
      this.setState(function(prevState, props) {
        //var message_list = prevState.APP_DATA.middle.message_list.slice(); // copy array
        //message_list.push(new_message);
  
        // De-activate spinner for image uploading...
        if(imageArrived){
          prevState.APP_DATA.middle.type_area.uploading = false;          
        }

        prevState.APP_DATA.middle.message_list.push(new_message);
        return {
          APP_DATA : prevState.APP_DATA
        };
      });
    }else if( res.hasOwnProperty('join') ){
      this.setState(function(prevState, props) {
        prevState.APP_DATA.left.member_list.push(new_member);
        return {
          APP_DATA : prevState.APP_DATA
        };
      });
    }else if( res.hasOwnProperty('name_list') ){
      this.setState(function(prevState, props) {
        new_members.forEach((member, index) => {
          prevState.APP_DATA.left.member_list.push(member);
        });

        return {
          APP_DATA : prevState.APP_DATA
        };
      });
    }else if( res.hasOwnProperty('leave') ){
      this.setState(function(prevState, props) {
        var indexToRemove = -1;
        prevState.APP_DATA.left.member_list.forEach((member, index) => {
          if(member.name == res.leave){
            indexToRemove = index;
          }  
        });

        if(indexToRemove != -1){
          prevState.APP_DATA.left.member_list.splice(indexToRemove, 1);
        }

        return {
          APP_DATA : prevState.APP_DATA
        };
      });
    }

  }

  socket_send(message) {
    if(this.socket){
      message['user_name'] = this.state.user_name;
      this.socket.emit('send', message);

      // Activate spinner for image uploading...
      if(message.message.img != null){
          this.setState(function(prevState, props) {
            prevState.APP_DATA.middle.type_area.uploading = true;          
            return {
              APP_DATA : prevState.APP_DATA
            };
          });
      }

    }else{
      console.log("error : socket not connected!");
    }
  }

  on_login(data) {
    this.setState(function(prevState, props) {
      console.log("user login : " + data.username);
      return {
        page : "chat",
        user_name : data.username
      };
    });

  }

  on_click_member(name){
    this.middle.typeArea.appendText("@"+name);
  }

  render() {

    if(this.state.page == "chat"){
      if(!this.socket){
        this.socket_connect();
      }

      return (
        <div className="container width-height-full">
          <div className="row width-height-full"> 
            <Left data={this.state.APP_DATA.left} on_send={this.socket_send} 
                  on_click_member={this.on_click_member} />
            <Middle data={this.state.APP_DATA.middle} on_send={this.socket_send}  
                    ref={(self) => { this.middle = self; }}  />
        </div>
        </div>
      );
    }else if(this.state.page == "login"){
      return (
        <div className="container width-height-full">
          <Login on_click={this.on_login} />
        </div>
      );
    }else{
      return null;
    }

  }
}

var g_APP_DATA = {
  left : {
    member_list : [
    /*
      { name: "Jimmy", online: true},
      { name: "Lucy", online: false},
      { name: "Michael", online: true},
    */
    ]
  }, 
  
  middle : {
    message_list : [
    /*
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
    */
    ],
    type_area : {
        uploading: false
    }
  }
};


export default App;
