import React from 'react';
import PropTypes from 'prop-types';


class TypeArea extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      inputImage : null,
      textAreaValue : ""
    };

    // This binding is necessary to make `this` work in the callback
    this.handleClick = this.handleClick.bind(this);
    this.onTextChange = this.onTextChange.bind(this);
    this.onInputChange = this.onInputChange.bind(this);
  }
  
  componentDidMount() {

  }

  onInputChange(e) { 
    var typeArea = this;
    console.log("fileInput.change ...");
    const file = e.target.files[0];
    if (file) {
      if (/^image\/\w+$/.test(file.type)) {
        var reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = function () {
          //console.log("dataURL : " + reader.result);
          var imageDataURL = reader.result;
          typeArea.setState(function(prevState, props) {
            return {
              inputImage : imageDataURL
            };
          });
        };
        reader.onerror = function (error) {
          console.log('reader error: ', error);
        };

      } else {
        window.alert('Please choose an image file.');
      }
    }else{
      console.log(" files NULL ");  
    }
  }

  handleClick() {
    this.props.on_send(this.state.textAreaValue);
    this.setState({textAreaValue: ""});
  }
  
  onTextChange(event) {
    this.setState({textAreaValue: event.target.value});
  }

  render() {
    return (
      <div className="type-area">
        <textarea value={this.state.textAreaValue} onChange={this.onTextChange}
                  className="form-control" name="typearea" rows="5" placeholder="Type a message..."/>        
        <button type="button" className="btn btn-primary btn-block" onClick={this.handleClick}>Send</button>
        <input type="file" className=""
               onChange={this.onInputChange}
               name="file" accept=".jpg,.jpeg,.png,.gif,.bmp,.tiff"/>
        <img className="input-image-css" src={this.state.inputImage} />       
      </div>
    );
  }
}


TypeArea.propTypes = {
 
};



export default TypeArea;
