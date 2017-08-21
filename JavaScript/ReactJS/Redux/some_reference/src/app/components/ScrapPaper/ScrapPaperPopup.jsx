import React, {Component} from 'react';
import {render, unmountComponentAtNode} from 'react-dom';
import ClickOutside from 'react-clickoutside';
import ScrapPaperComponent from './';

import { Provider } from 'react-redux';
import store from '../../redux/store';

const ScrapPaperPopup = ClickOutside(() => {
    return (
        <div className="wb-scrap-paper-popup-wrapper">
            <ScrapPaperComponent/>
        </div>
    );
});

class ScrapPaperPopupComponent extends Component {
  constructor(props) {
    super(props);
    this.state = {
        showScrapPaperPopup: false
    };
  }

  onScrapPaperClick = () => {
    this.setState({showScrapPaperPopup: !this.state.showScrapPaperPopup});
  }

  onClickOutSide = () => {
      this.setState({showScrapPaperPopup:false});
  }

  render() {
    let scrapPaperHTML = this.state.showScrapPaperPopup && (
        <ScrapPaperPopup onClickOutside={this.onClickOutSide} />
    );
    return (
        <div>
            <img src="/assets/css/img/notePad.png" style={{height:'35px', cursor: 'pointer'}} onClick={this.onScrapPaperClick}/>
            {scrapPaperHTML}
        </div>
    );
  }
}

window.WB.react.unmountScrapPaperPopupComponent = () => {
    if(document.getElementById('scrapPaperPopupWrapper') != null) {
        unmountComponentAtNode(document.getElementById('scrapPaperPopupWrapper'));
    }
}

window.WB.react.renderScrapPaperPopupComponent = function() {
      render(
        <Provider store={store}>
            <ScrapPaperPopupComponent/>
        </Provider>, 
        document.getElementById('scrapPaperPopupWrapper')
    );
}