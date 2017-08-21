import React, { Component } from 'react';

export default class ChecklistAddNewSubCategory extends Component {

    constructor(props) {
        super(props);
        this.state = {
            newSubCategoryName: ''
        };
    }

    onAddNewSubCategory = (event) => {
        if(this.state.newSubCategoryName.trim() !='') {
            this.props.addNewSubCategory(this.state.newSubCategoryName);
            this.setState({newSubCategoryName: ''});
        }
    }

    onNewSubCatNameChange = (event) => {
        this.setState({newSubCategoryName: event.target.value});
    }

    render() {
        return (
            <ul>
                <li><input type="text" style={{width: '90%'}} value={this.state.newSubCategoryName} onChange={this.onNewSubCatNameChange}/></li>
                <li style={{textAlign: 'center'}}><img src="/assets/css/img/plusButton.png" onClick={this.onAddNewSubCategory} style={{width: '30px'}}/></li>
            </ul>
        );
    }

}