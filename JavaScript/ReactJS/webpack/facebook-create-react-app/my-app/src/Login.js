import React from 'react';
import PropTypes from 'prop-types';



class Login extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
     
    };
    
    // This binding is necessary to make `this` work in the callback
    this.handleClick = this.handleClick.bind(this);
  }

 
  handleClick() {
    this.props.on_click({ username : document.getElementById("input_username").value });
  }

  componentDidMount(){

    // Press enter key on input: 
    document.getElementById("input_username").addEventListener("keyup", function(event) {
      event.preventDefault();
      if (event.keyCode == 13) {
        document.getElementById("btn_login").click();
      }
    }); 
  }

  componentDidUpdate(){
   
  }

  

  render() {

    return (
      <div className="login-css">
       
        

      
          <input id="input_username" type="text" className="input-lg login-input-css" placeholder="Enter your name"/>
          <br/> <br/> 
          <button id="btn_login" type="button" className="btn btn-primary btn-lg btn-block" onClick={this.handleClick}>Start</button> 
       

       

     	</div>
    );

  }
}



export default Login;
