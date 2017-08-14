import React from 'react';
import PropTypes from 'prop-types';
import Lightbox from 'react-images'; // https://github.com/jossmac/react-images

class MessageChat extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      displayLightbox : false 
    };

    this.onClickImage = this.onClickImage.bind(this);
    this.closeLightbox = this.closeLightbox.bind(this);
  }
  
  onClickImage() {
    this.setState({displayLightbox : true});
  }

  closeLightbox() {
    this.setState({displayLightbox : false});
  }
  
  render() {
    let image = null;
    if (this.props.message.img){
      image =  <img className="chat-image-css" src={this.props.message.img} 
                width={this.props.message.img_w} height={this.props.message.img_h} 
                onClick={this.onClickImage}  />; 
    }

    return (
      <div className="message-chat">
        <img src={this.props.pic_url} className="img-circle" alt="" width="60" height="60"/>
        &nbsp; 
        <span style={{fontWeight: "bold"}} >{this.props.name}</span>
        &nbsp; &nbsp; 
        <span style={{fontStyle: "italic"}}>{this.props.time}</span>
        <br/>
        &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
        {this.props.message.text}
        {image}

        <Lightbox
          images={[{ src: this.props.message.img }]}
          backdropClosesModal={true}
          isOpen={this.state.displayLightbox}
          onClose={this.closeLightbox}
        />

        <br/><br/>    
      </div>
    );
  }
}


MessageChat.propTypes = {
  name : PropTypes.string,
  online : PropTypes.bool,
  message : PropTypes.string,
  time : PropTypes.string,
  pic_url : PropTypes.string,
};



export default MessageChat;
