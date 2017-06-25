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
   

  }

  componentDidMount(){
   
  }

  componentDidUpdate(){
   
  }

  

  render() {

    return (
      <div className="login-css">
       
        User Name: <input type="text"/>
        &nbsp; &nbsp; 
        <button type="button" className="btn btn-primary" onClick={this.handleClick}>Start</button> 
       
     	</div>
    );

  }
}



export default Login;
