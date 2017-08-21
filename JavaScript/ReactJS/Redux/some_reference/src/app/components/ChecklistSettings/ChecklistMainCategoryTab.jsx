import React, { Component } from 'react';

export default class ChecklistMainCategoryTab  extends Component {

    constructor(props) {
        super(props);
    }

    changeCategory = (selectedCategory) => {
        let activeSubCategories = this.props.subCategories.filter((subCategory) => {
            return (subCategory.get('parentId') == selectedCategory.get('id') && 
            (subCategory.get('orgId') == WB.env.currentOrganisation.id || subCategory.get('orgId') == '0'))
        });
        this.props.changeCategory(selectedCategory, activeSubCategories);
    }

    render() {
         return (
            <div className="wb-checklist-tab-wrapper">
                {
                    this.props.categories.map((categoryItem, index) => {
                        return (
                            <div 
                                key={`category-item-${index}`} 
                                className={(categoryItem.get('status') == 'active') ? 'wb-checklist-tab wb-checklist-tab-active': 'wb-checklist-tab'}
                                onClick={(e) => this.changeCategory(categoryItem)}>
                                {categoryItem.get('name')}
                            </div>
                        )
                    })
                }
            </div>
         );
    }

}