// !! Not in use !! 


// https://www.npmjs.com/package/react-images-uploader
// https://github.com/aleksei0807/react-images-uploader
//Server: https://github.com/aleksei0807/images-upload-middleware

import React, { Component } from 'react';
import ImagesUploader from 'react-images-uploader';
import 'react-images-uploader/styles.css';
import 'react-images-uploader/font.css';
 
export default class MyUploader extends Component {
    render() {
        return (
            <ImagesUploader
                url="http://jimmyh.hopto.org:3701/single"
                optimisticPreviews={true}
                multiple={true}
                onLoadEnd={(err) => {
                    if (err) {
                        console.error(err);
                    }
                }}
                label="Upload a picture"
                />
        );
    }
}
