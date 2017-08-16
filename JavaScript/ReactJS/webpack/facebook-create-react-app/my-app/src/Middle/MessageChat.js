import React from 'react';
import PropTypes from 'prop-types';
import Lightbox from 'react-images'; // https://github.com/jossmac/react-images
import Tooltip from 'rc-tooltip';
import 'rc-tooltip/assets/bootstrap.css';

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
      image = (
               <Tooltip
                placement={'right'}
                mouseEnterDelay={0}
                mouseLeaveDelay={0.1}
                destroyTooltipOnHide={false}
                trigger={Object.keys( {hover: 1} ) }               
                overlay={<div style={{ height: 50, width: 50 }}>Click to see full size</div>}
                align={{
                  offset: [4, 0],
                }}
                transitionName={"transitionName"}
               >
                 <img className="chat-image-css" src={this.props.message.img} 
                  width={this.props.message.img_w} height={this.props.message.img_h} 
                  onClick={this.onClickImage}  />
               </Tooltip>
               ); 
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
