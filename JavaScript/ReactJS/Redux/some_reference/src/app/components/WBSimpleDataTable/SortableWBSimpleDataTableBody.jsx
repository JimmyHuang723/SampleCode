import React, { Component } from 'react';
import {SortableContainer} from 'react-sortable-hoc';
import SortableWBSimpleDataTableRow from './SortableWBSimpleDataTableRow';


const SortableWBSimpleDataTableBody = SortableContainer((
    {
        sort,
        columns,
        data,
        itemClick,
        checkAll,
        checkItem,
        height,
        width,
        detailRowContent,
        children
        
    }) => {
    return (
        <WBSimpleDataTableBodyComponent
            sort={sort}
            columns={columns}
            data={data}
            itemClick={itemClick}
            checkAll={checkAll}
            checkItem={checkItem}
            height={height}
            width={width}
            detailRowContent={detailRowContent}
            children={children}
        />
    )
});


class WBSimpleDataTableBodyComponent extends Component {
    constructor(props) {
        super(props)
    }

    render() {
       return (
            <tbody className="yui3-datatable-data">
                {this.props.children}
                {
                    this.props.data.map((item, index) => (
                            [
                                <SortableWBSimpleDataTableRow key={`item-${index}`} index={index} columns={this.props.columns} item={item} checkItem={this.props.checkItem} itemClick={this.props.itemClick}/>,    
                                (
                                    this.props.detailRowContent && item.get('showDetails') && 
                                    <WBSimpleDataTableDetailRow colSpan={this.props.columns.length} detailRowContent={this.props.detailRowContent} item={item}/>
                                )
                            ]
                        ))
                    
                }
                <tr className="dummy-tr">
                    {
                        this.props.columns.map((column, index) => {
                            if(column.type && column.type == 'checkbox') {
                                return <td className="wb-checkbox-col" key={`item-${index}`}></td>;
                            }
                            else if(!column.type || (column.type && column.type != 'hidden')) {
                                let cssClass = 'yui3-datatable-cell ' + (column.cssClass ? column.cssClass : '');
                                return <td key={`item-${index}`} 
                                style={{width: (column.width? column.width:'')}}
                                className={cssClass}></td>;    
                            } 
                        })
                    }
                </tr>
            </tbody>
       );
    }
    
}


export default SortableWBSimpleDataTableBody;