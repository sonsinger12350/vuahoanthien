import React, {} from 'react';
import Tooltip from 'rc-tooltip';
import 'rc-tooltip/assets/bootstrap.css';

//import PropTypes from 'prop-types';

const MenuItemDetails = (props) => {

  const { item, index, parent } = props;

  return (
  <div className="mmu-menu-item-details">
    <div className="mmu-menu-item-summary">
      <div className={"mmu-menu-item-meta mmu-menu-item-title " + ((item.markedForDelete) ? " mmu-menu-item-title--delete" : "")}>
        {item.title}
      </div>
      <div className="mmu-menu-item-meta mmu-menu-item-url">
      
      <Tooltip
          placement="right"
          mouseEnterDelay={0}
          mouseLeaveDelay={0.1}
          trigger={(window.MMU?.can_use_premium_code) ? ['click'] : ['none']}
          overlay={<div><div className="mmu-menu-item-tooltop-options"><div><a href={item.goto_link} target="_blank">View</a></div>{item.edit_link && <div><a target="_blank" href={item.edit_link}>Edit</a></div>}</div></div>}
        >
          <div>
            {item.display_link}
          </div>
        </Tooltip>
        { (window.MMU?.debug) ? 
        <div>
        INDEX: {index} | {item.ID}:{item.title} | parent id: {parent ? parent.ID : "null"} | parent expanded: {(parent && parent.expanded) ? "true": "false"} | expanded: {item.expanded ? "true" : "false"} | depth: {item.depth}
        </div>
         : "" }
      </div>
      {Array.isArray(item.object_fields) && item.object_fields.map(
        (fieldInfo) => {
          /* @TODO: make this check a little cleaner and/or support other variable types */
          if (
            typeof(fieldInfo.field_label) != 'undefined' 
            && fieldInfo.field_label != ''
            && typeof(fieldInfo.field_value) != 'undefined' 
            && fieldInfo.field_value != null 
            && fieldInfo.field_value != ''
            && typeof(fieldInfo.field_value) != 'object'
            && !Array.isArray(fieldInfo.field_value)) {
            return (
              <div key={fieldInfo.field_key} className={"mmu-menu-item-meta mmu-menu-item-" + fieldInfo.field_key}>
                <span className="mmu-menu-item-meta-label">{fieldInfo.field_label}:&nbsp;</span>
                <span className="mmu-menu-item-meta-value">{fieldInfo.field_value}</span>
              </div>
            );
          }
        })
      }
      
    </div>
  </div>
  );
} 

export default MenuItemDetails;