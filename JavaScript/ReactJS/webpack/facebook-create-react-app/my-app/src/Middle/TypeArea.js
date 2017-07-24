import React from 'react';
import PropTypes from 'prop-types';


class TypeArea extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
     
    };

    // This binding is necessary to make `this` work in the callback
    this.handleClick = this.handleClick.bind(this);
  }
  
  componentDidMount() {

  }

  onInputChange(e) { 
    console.log("fileInput.change ...");
    const file = e.target.files[0];
    if (file) {
      if (/^image\/\w+$/.test(file.type)) {
        var reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = function () {
          //console.log("dataURL : " + reader.result);
          var imageDataURL = reader.result;
          document.getElementById('inputImage').setAttribute( 'src', imageDataURL );
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
    this.props.on_send(document.getElementById("middle_textarea").value);
    document.getElementById("middle_textarea").value = "";
  }
  
  render() {
    return (
      <div className="type-area">
        <textarea id="middle_textarea" className="form-control" name="typearea" rows="5" placeholder="Type a message..."/>        
        <button type="button" className="btn btn-primary btn-block" onClick={this.handleClick}>Send</button>
        <input type="file" className=""
               onChange={this.onInputChange}
               name="file" accept=".jpg,.jpeg,.png,.gif,.bmp,.tiff"/>
        <img className="input-image-css" id="inputImage" />       
      </div>
    );
  }
}


TypeArea.propTypes = {
 
};



export default TypeArea;
