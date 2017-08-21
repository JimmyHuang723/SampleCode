import React, {Component} from 'react';
import {List,Map,fromJS} from 'immutable';
import dateFormat from 'dateformat';


export default class CMAFilter extends Component {
  constructor(props) {
    super(props);
    this.state = {
        radius: '',
        startNo: '',
        period: 'm',
        priceFrom: '',
        priceTo: '',
        propertyType: ''
    }
    this.handleChange = this.handleChange.bind(this);
  }

  handleFilterComparables = () => {
    if(this.state.startNo.trim() == '' || this.state.period.trim() == '') {
        alert('Please enter value for start.');
    }
    else if(this.state.radius.trim() == '') {
        alert('Please enter value for radius.');
    } else {
        let filters = Map();
        filters = filters.set('radius', parseFloat(this.state.radius, 10));
        filters = filters.set('priceFrom', parseFloat(this.state.priceFrom, 10));
        filters = filters.set('priceTo', parseFloat(this.state.priceTo, 10));
        filters = filters.set('propertyType', this.state.propertyType);                                    
        filters = filters.set('startNo', parseInt(this.state.startNo, 10));
        filters = filters.set('period', this.state.period);                 
        filters = filters.set('priceFrom', null);    
        filters = filters.set('priceTo', null);    
        if(this.state.priceFrom.trim() != '') {
            filters = filters.set('priceFrom', parseFloat(this.state.priceFrom, 10));    
        }
        if(this.state.priceTo.trim() != '') {
            filters = filters.set('priceTo', parseFloat(this.state.priceTo, 10));    
        }
        this.props.onFilterComparables(filters);
    }
  };

  handleChange(event) {
    const target = event.target;
    const value = target.type === 'checkbox' ? target.checked : target.value;
    const name = target.name;
    this.setState({[name]: value});
  }

  handleCheckAll = (event) => {
    this.props.onCheckAll(event.target.checked);
  }

  render() {
    return (
        <div className="wb-cma-comparable-filter">
            <div className="yui3-g-r wb-variables comparable">
                <table className="wb-cma-filter-controls">
                    <tbody>
                        <tr>
                            <td style={{borderLeft: '7px solid white'}}>
                                <input type="checkbox" className="wb-checkAll" onChange={this.handleCheckAll} checked={this.props.selectAll}/>
                            </td>
                            <td>
                                Radius (km)<br/>
                                <input type="text" className="input-mini wb-radius" name="radius" value={this.state.radius} onChange={this.handleChange} />
                            </td>
                            <td>
                                Start<br/>
                                <input type="text" className="input-micro wb-no" name="startNo" value={this.state.startNo} onChange={this.handleChange} />&nbsp;&nbsp;
                                <select className="wb-period" name="period" value={this.state.period} onChange={this.handleChange} >
                                    <option value="">--Select--</option>
                                    <option value="m">Month(s)</option>
                                    <option value="y">Year(s)</option>
                                </select>
                            </td>
                            <td>
                                Price<br/>
                                <input type="text" className="input-mini wb-pfrom" placeholder="from" name="priceFrom" value={this.state.priceFrom} onChange={this.handleChange} />&nbsp;&nbsp;
                                <input type="text" className="input-mini wb-pto" placeholder="to" name="priceTo" value={this.state.priceTo} onChange={this.handleChange} />
                            </td>
                            <td className="yui3-u-1-6 ">
                                Type<br/>
                                <select className="wb-type" name="propertyType" value={this.state.propertyType} onChange={this.handleChange} >
                                    <option value="">--Select--</option>
                                    <option value="house">House</option>
                                    <option value="unit">Unit</option>
                                    <option value="vacant">Vacant</option>
                                    <option value="commercial">Commercial</option>
                                    <option value="other">Other</option>
                                </select>
                            </td>
                            <td style={{verticalAlign: 'bottom'}}>
                                <a className="btn wb-redo btn-success " title="Filter" onClick={this.handleFilterComparables}><em></em>
                                        <span>Fetch</span>
                                </a>&nbsp;&nbsp;&nbsp;&nbsp;
                                <span className="wb-selfPdf"></span>
                            </td>
                            
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    );
  }
}