import React, {Component} from 'react';
import {List,Map,fromJS} from 'immutable';
import dateFormat from 'dateformat';

const componentStyle = {
    marginRight: '5px',
    float: 'left'
}

export default class CMAItem extends Component {
  constructor(props) {
    super(props);
  }

  handleSelectComparable = () => {
    let updatedComparable = Map();
    if(this.props.item.get('selected')) {
        updatedComparable = this.props.item.set('selected',false);
    }
    else {
        updatedComparable = this.props.item.set('selected',true);
    }
    //calls the parent Component function to update state
    this.props.onSelectComparable(updatedComparable);
  };

  render() {
    let landSize = null;
    let bath = null;
    let bed = null;
    let parking = null;

 

    let img = <img src="http://fakeimg.pl/100x75/cccccc/444444/?text=No%20Image&font=bebas"/>;
    if (this.props.item.getIn(['image','id'])) {
        img = <img src={`https://api-dev.pricefinder.com.au/v1/images/${this.props.item.getIn(['image','id'])}?access_token=${WB.user.apm.tokenKey}&width=100&height=75`}/>;
    }

    if(this.props.item.getIn(['landDetails', 'propertyArea'])>10000) {
        landSize = <span>{parseFloat(this.props.item.getIn(['landDetails', 'propertyArea'])/10000,10)}ha</span>;
    } else {
        landSize = <span>{this.props.item.getIn(['landDetails', 'propertyArea'])}m<sup style={{top: '-15px'}}>2</sup></span>;
    }

    if(this.props.item.getIn(['propertyFeatures','bedrooms'])) {
        bed = <div style={componentStyle}>&nbsp;<i className="fa fa-bed"></i>&nbsp;{this.props.item.getIn(['propertyFeatures','bedrooms'])}</div>;
    } else {
        bed = <div style={componentStyle}>&nbsp;<i className="fa fa-bed"></i>&nbsp;-</div>;
    }

    if(this.props.item.getIn(['propertyFeatures','bathrooms'])) {
        bath = <div style={componentStyle}>&nbsp;<i className="fa fa-tint"></i>&nbsp;{this.props.item.getIn(['propertyFeatures','bathrooms'])}</div>;
    } else {
        bath = <div style={componentStyle}>&nbsp;<i className="fa fa-tint"></i>&nbsp;-</div>;
    }

    if(this.props.item.getIn(['propertyFeatures','carParks'])) {
        parking = <div style={componentStyle}>&nbsp;<i className="fa fa-car"></i>&nbsp;{this.props.item.getIn(['propertyFeatures','carParks'])}</div>;
    } else {
        parking = <div style={componentStyle}>&nbsp;<i className="fa fa-car"></i>&nbsp;-</div>;
    }

   
        
    return (
        <div className={this.props.item.get('selected')? 'wb-cma-item wb-cma-selected': 'wb-cma-item'} onClick ={this.handleSelectComparable}>
            <div className="yui3-g-r form-row">
                <div className="yui3-u-1-5">
                    <input type="checkbox" value={this.props.item.get('id')} className="data-apm-sale" checked={this.props.item.get('selected')} onChange={this.handleSelectComparable}/>
                    &nbsp;{img}
                </div>
                <div className="yui3-u-3-5">
                    <strong>{this.props.item.getIn(['address', 'streetAddress'])}&nbsp;{this.props.item.getIn(['address', 'locality'])}</strong><br/>
                    Sale: {this.props.item.getIn(['price', 'display'])}  on {dateFormat(new Date(this.props.item.getIn(['saleDate', 'value'])), "fullDate")}<br/>
                    Land size: {landSize}<br/>
                </div>
                <div className="yui3-u-1-5">
                    <strong>{this.props.item.get('propertyType')}</strong><br/>
                    {bed}{bath}{parking}
                </div>
            </div>
        </div>
    );
  }
}