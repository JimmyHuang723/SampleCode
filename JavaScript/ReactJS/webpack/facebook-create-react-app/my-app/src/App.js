import React, { Component } from 'react';
import Left from './Left';
import Middle from './Middle';

class App extends Component {
  render() {
  	// this.props.xxx
    return (
      <div>
      	<Left/>
      	<Middle/>
      </div>
    );
  }
}

export default App;
