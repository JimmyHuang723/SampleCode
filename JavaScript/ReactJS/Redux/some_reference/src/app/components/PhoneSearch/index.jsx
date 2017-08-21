import React, {Component} from 'react';
import {render, unmountComponentAtNode} from 'react-dom';
import ClickOutside from 'react-clickoutside';
import axios from 'axios';

const PhoneSearchResult = ClickOutside(({items, onPhoneItemClick}) => {
    return (
        <div className="phone-search-result">
            {items.map((item, index) => (
                <div key={`item-${index}`}>
                    <div  className="phone-search-item" onClick={(e) => onPhoneItemClick(item, e)}>({item.type}) {item.phone} </div>
                    <hr/>
                </div>
            ))}
        </div>
    );
});

export default class PhoneSearch extends Component {
  constructor(props) {
    super(props);
    this.state = {
        searchInProgress: false,
        showResult: false,
        items: []
    };
  }
  
  onClickOutSide = () => {
      this.setState({showResult:false, items: []});
  }

  onPhoneItemClick = (item, e) => {
    this.setState({showResult:false, items: []});
    switch(item.type) {
        case 'B': 
            WB.my.reactDashboardCommon.showBuyerEdit(item.propertyRequestId);
        break;
        case 'P':
            WB.my.reactDashboardCommon.showProspectEdit(item.prospectAddressId);
        break;
        default:
            WB.my.reactDashboardCommon.showContactEdit(item.contact);
    }
  }

  onSearchChange = (event) => {
    let searchText = event.target.value;
    if(searchText.length > 3 && !this.state.searchInProgress) {
        this.setState({searchInProgress: true});
        axios.post('/assets/siud/s/phoneSearch.php', [{
            criteria: {
                organisation: WB.env.currentOrganisation.id,
                search: searchText,
                member: WB.user.contact
            }
        }])
        .then( (response) => {
            this.setState({searchInProgress: false});
            if(response.data[0].result.phones.length >0) {
                this.setState({
                    showResult: true,
                    items: response.data[0].result.phones
                });
             } else {
                 this.setState({
                    showResult: false,
                    items: []
                });
             }
        })
        .catch((error) => {
            this.setState({searchInProgress: false});
            console.log(error);
        });
        // this.setState({showResult: true, items: tmpResult});
    }
  };

  render() {
    let phoneResults = null;
    if(this.state.showResult) {
        phoneResults = <PhoneSearchResult items={this.state.items} onClickOutside={this.onClickOutSide} onPhoneItemClick={this.onPhoneItemClick}/>;
    }
    return (
        <div style={{float: 'right', marginTop: '5px'}}>
            <i className="icon-search" style={{color: 'gray', fontSize: '20px', position: 'absolute', top: '10px', marginLeft: '5px'}}></i>
            <input type="text" placeholder="Phone search" style={{borderRadius: '15px', paddingLeft: '30px'}} onChange={this.onSearchChange}/> 
            {phoneResults}
        </div>
    );
  }
}

window.WB.react.unmountPhoneSearchComponent = () => {
    if(document.getElementById('phoneSearchWrapper') != null) {
        unmountComponentAtNode(document.getElementById('phoneSearchWrapper'));
    }
}

window.WB.react.renderPhoneSearchComponent = function() {
    render(<PhoneSearch/>, document.getElementById('phoneSearchWrapper'));
}