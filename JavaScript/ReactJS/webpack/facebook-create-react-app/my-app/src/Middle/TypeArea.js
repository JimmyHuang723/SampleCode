import React from 'react';
import PropTypes from 'prop-types';
import Spinner from 'react-spinner';
import 'react-spinner/react-spinner.css';

class TypeArea extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      inputImage : null,
      textAreaValue : "",
      previewImgW : 0,
      previewImgH : 0,
      textAreaPlaceholder : "Type a message..."
    };

    // This binding is necessary to make `this` work in the callback
    this.handleClick = this.handleClick.bind(this);
    this.onTextChange = this.onTextChange.bind(this);
    this.onInputFileChange = this.onInputFileChange.bind(this);
    this.appendText = this.appendText.bind(this);
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
              let defaultImgH = 80;
              return {
                previewImgW : defaultImgH * this.width / this.height,
                previewImgH : defaultImgH
              };
            });
          };

          // Set DataURL to State : 
          var imageDataURL = reader.result;
          typeArea.setState(function(prevState, props) {
            return {
              inputImage : imageDataURL,
              textAreaPlaceholder : ""
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
 
    let uploading = this.state.inputImage ? true:false;

    this.setState(
      { textAreaValue: "",
        inputImage: null,
        textAreaPlaceholder: "Type a message..."
      }
    );    
    
  }
  
  onTextChange(event) {
    this.setState({textAreaValue: event.target.value});
  }

  appendText(text) {
    this.setState(function(prevState, props) {
           
      return {
        textAreaValue : prevState.textAreaValue + text
      };
    });  
  }

  render() {

    return (
      <div className="type-area">
        
        <img className="type-area-image-css" src={this.state.inputImage} 
             width={this.state.previewImgW} height={this.state.previewImgH} />  

        { this.props.data.uploading && <Spinner/>}
        <textarea value={this.state.textAreaValue} onChange={this.onTextChange}
                  className="form-control" name="typearea" rows="5" 
                  placeholder={this.state.textAreaPlaceholder}  />        
        <button type="button" className="btn btn-primary btn-block" onClick={this.handleClick}>Send</button>
        
       
        <div className="fileUpload btn btn-primary">
            <span>Upload an image</span>          
            <input type="file" className="upload" 
               onChange={this.onInputFileChange}
               name="file" accept=".jpg,.jpeg,.png,.gif,.bmp,.tiff"/>
        </div>
          
        

      </div>
    );
  }
}


TypeArea.propTypes = {
   
};



export default TypeArea;
