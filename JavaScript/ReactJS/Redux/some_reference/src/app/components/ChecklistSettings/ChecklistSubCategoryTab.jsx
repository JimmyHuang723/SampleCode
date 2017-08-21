import React, { Component }  from 'react';
import ChecklistAddNewSubCategory from './ChecklistAddNewSubCategory';

export default class ChecklistSubCategoryTab extends Component {
    constructor(props) {
        super(props);
    }

    render() {
        return (
            <div className="wb-checklist-subcategories">
                <ul>
                    {
                        this.props.activeSubCategories.map((subCategoryItem, index) => {
                            return <li key={`sub-category-item-${index}`} 
                            className={(subCategoryItem.get('status') == 'active') ? 'wb-checklist-subcategory-active': ''}
                            onClick={(e) => this.props.changeSubCategory(subCategoryItem)}>{subCategoryItem.get('name')}</li>
                        })
                    }
                </ul>
                {/*<ChecklistAddNewSubCategory addNewSubCategory={this.props.addNewSubCategory}/>*/}
            </div>
        );
    }
    
}