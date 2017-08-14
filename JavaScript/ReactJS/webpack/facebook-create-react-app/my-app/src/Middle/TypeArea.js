import React from 'react';
import PropTypes from 'prop-types';
import MyUploader from './MyUploader';

class TypeArea extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      inputImage : null,
      textAreaValue : "",
      previewImgW : 0,
      previewImgH : 0
    };

    // This binding is necessary to make `this` work in the callback
    this.handleClick = this.handleClick.bind(this);
    this.onTextChange = this.onTextChange.bind(this);
    this.onInputFileChange = this.onInputFileChange.bind(this);
  }
  
  componentDidMount() {

  }

  onInputFileChange(e) { 
    var typeArea = this;
    console.log("fileInput.change ...");
    const file = e.target.files[0];
    if (file) {
      if (/^image\/\w+$/.test(file.type)) {
        var reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = function () {
          //console.log("dataURL : " + reader.result);

          // Get image size : 
          var image = new Image();
          image.src = reader.result;
          image.onload = function() {
            // access image size here 
            console.log("w : " + this.width + " h : " + this.height);
            // Set preview image size : 
            typeArea.setState(function(prevState, props) {
              return {
                previewImgW : 150 * this.width / this.height,
                previewImgH : 150
              };
            });
          };

          // Set DataURL to State : 
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
    this.props.on_send( 
                        {
                          text : this.state.textAreaValue, 
                          img : this.state.inputImage,
                          img_w : this.state.previewImgW,
                          img_h :  this.state.previewImgH
                        }
                      );
    this.setState({textAreaValue: ""});
    this.setState({inputImage: null});
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
        
        {/*
        <MyUploader/>
        */}

        <div className="fileUpload btn btn-primary">
            <span>Upload a file</span>          
            <input type="file" className="upload" 
               onChange={this.onInputFileChange}
               name="file" accept=".jpg,.jpeg,.png,.gif,.bmp,.tiff"/>
        </div>

        <img className="input-image-css" src={this.state.inputImage} 
             width={this.state.previewImgW} height={this.state.previewImgH} />            

      </div>
    );
  }
}


TypeArea.propTypes = {
 
};



export default TypeArea;
