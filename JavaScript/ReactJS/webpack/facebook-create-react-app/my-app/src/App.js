import React, { Component } from 'react';
import Left from './Left';
import Middle from './Middle';


class App extends Component {
  render() {
  	// this.props.xxx
    return (
      <div className="container width-height-full">
        <div className="row width-height-full">	
      	  <Left />
      	  <Middle />
     	</div>
      </div>
    );
  }
}

export default App;
