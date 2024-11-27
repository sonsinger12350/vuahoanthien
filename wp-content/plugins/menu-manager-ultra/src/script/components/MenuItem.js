import React, {useContext, useEffect, useState} from 'react';
import { useDraggable, useDndMonitor } from '@dnd-kit/core';
import {CSS} from '@dnd-kit/utilities';
import { StoreContext } from '../utils/store';
import MenuItemDropTarget from './MenuItemDropTarget';
import MenuItemDetails from './MenuItemDetails';
import MenuItemEditor from './MenuItemEditor';
import MenuItemActions from './MenuItemActions';
import appDebug from "../utils/appDebug";
import { AppConfig } from "../config/AppConfig";
import { fetchAndExpandItem, setItemCollapsed, setItemExpanded } from "../helpers/MenuListHelper";
import classNames from 'classnames';
import { findMenuItemByID, updateItem } from '../utils/utils';

//import PropTypes from 'prop-types';

const ITEM_NESTED_INDENT_SIZE = 27;

const MenuItem = (props) => {
  const store = useContext(StoreContext);
  const { item, parent } = props;
  const [ isBeingDraggedOver, setIsBeingDraggedOver ] = useState(false);
  const [ dropTargetStyle, setDropTargetStyle ] = useState({
    'marginLeft': (item.depth * ITEM_NESTED_INDENT_SIZE).toString() + 'px'
  });

  const draggableOptions = useDraggable({
    id: item.ID
  });

  const transition = draggableOptions.transition;
  const style = {
    transform: CSS.Translate.toString(draggableOptions.transform),
    transition,
    visibility: (draggableOptions.isDragging) ? 'hidden' : 'visible',
    //opacity: (draggableOptions.isDragging) ? '0.5' : '1',
    marginLeft: (item.depth * ITEM_NESTED_INDENT_SIZE).toString() + 'px'
  };

  /**
   * Toggle expansion and collapse of a given item
   * @param {MenuItem} item 
   */
  const expandCollapseSubItems = async (item) => {

    appDebug(AppConfig.debugCategoryDefault, 'expandCollapse called', item);

    if (item.expanded) {
      store.setMenuItems(setItemCollapsed(store.menuItems, item));
    }
    else {
      if (item.has_fetchable_children && !item.wasFetched) {

        store.setMenuItems(updateItem(store.menuItems, {...item, 'isLoading': true}));

        const updatedItems = await fetchAndExpandItem(store.menuID, store.menuItems, item);

        let updatedItem = findMenuItemByID(updatedItems, item.ID);

        store.setMenuItems(updateItem(updatedItems, {...updatedItem, 'isLoading': false}));

        appDebug(AppConfig.debugCategoryDefault, 'first fetch');
      }
      else {
        store.setMenuItems(setItemExpanded(store.menuItems, item));
      }
      
    }
  }

  const isHidden = () => {
    return (parent && parent.expanded == false) ? true : false;
  }

  useDndMonitor({
    onDragMove: (e) => {

      const { over, delta } = e;
      
      if (over?.id && over?.data?.current?.item) {

        const droppableTargetItem = over.data.current.item;
        const droppableDepth = droppableTargetItem.depth;
        
        let xCompare = delta.x;
        let forceNested = false;

        let nestedOffset = ITEM_NESTED_INDENT_SIZE;

        if (droppableTargetItem.expanded == true) {
          forceNested = true;
        }

        const depthDifference = item.depth - droppableDepth;
        
        if (depthDifference != 0) {
          nestedOffset -= depthDifference * ITEM_NESTED_INDENT_SIZE;
        }

        // console.log('depth diff', depthDifference);
        // console.log('delta', delta.x);
        // console.log("xcompare", xCompare);
        // console.log('nested offset', nestedOffset);

        if (forceNested || (xCompare > nestedOffset)) {
          //console.log("NESTED!", nestedOffset, droppableTargetItem);
          store.setNestedDragID(droppableTargetItem.ID);
        }
        else {
          store.setNestedDragID(null);
        }

      }

    }
  });

  /**
   * When nestedDragId changes -- indicating that a user
   * is dragging an item to be nested beneath another one, 
   * update the styling of the drop target to move it over so it 
   * appears indented under its parent
   */
  useEffect(
    () => {

      let style = {};
      let marginLeft = (item.depth) * ITEM_NESTED_INDENT_SIZE;

      if (store.nestedDragID && store.nestedDragID == item.ID) {
        appDebug(AppConfig.debugCategoryDefault, 'applying nested style');
        marginLeft += ITEM_NESTED_INDENT_SIZE;
      }

      style = {
        ...style, 
        'marginLeft': marginLeft.toString() + 'px',
      }

      setDropTargetStyle(
        style
      )
    }, [store.nestedDragID]
  )

  return (
    <>
    {/* Display the topmost drag target, so that users can drag items to position 0 */}
    {(props.index == 0) ? <MenuItemDropTarget position="top" item={item} /> : ""}
    
    {/* {!isHidden() && (props.index > 0 && item.depth == 0 && store.menuItems[props.index -1]?.depth != item.depth) 
      ? <MenuItemDropTarget position="before" item={item} style={{'margin-left': (item.depth * ITEM_NESTED_INDENT_SIZE).toString() + 'px'}} /> 
      : ""} */}

    <div className={
      classNames(
        "mmu-menu-item-container",
        {"mmu-menu-item-container--hidden": isHidden()},
        {"mmu-menu-item-container--drag-hover": isBeingDraggedOver},
        {'mmu-menu-item-container--with-children': (item.childCount > 0 || item.has_fetchable_children > 0)},
        {'mmu-menu-item-container--without-children': (item.childCount <= 0 && !item.has_fetchable_children)}
      )} ref={draggableOptions.setNodeRef} style={style} >

      <div className="mmu-menu-item-stage">
        <div className="mmu-menu-item-drag-handle" {...draggableOptions.attributes} {...draggableOptions.listeners}>
          <div className="mmu-gripper" aria-label="Move up or down"></div>
        </div>

        <div className="mmu-menu-item-rows">
          {(!item.isNewAddition) ? 
            <div className={"mmu-menu-item-row mmu-menu-item-row--details " + ((item.expanded) ? "mmu-menu-item-row--expanded" : "")} >
            {(item.childCount > 0 || item.has_fetchable_children > 0) ?
                <button className={
                  classNames(
                    'dashicons',
                    {'dashicons-update mmu-icon-button--item-loading': item.isLoading},
                    {'dashicons-arrow-down': (!item.isLoading && item.expanded)}, 
                    {'dashicons-arrow-right': (!item.isLoading && !item.expanded)},
                    {'mmu-icon-button--expand-item': !item.isLoading}
                  )}
                  aria-label="Expand Sub Items" onClick={() => { expandCollapseSubItems(props.item); }}></button>
                : ""
              }
                
              <MenuItemDetails item={item} index={props.index} parent={parent} />    
              {(!item.showEditor) ? <MenuItemActions item={item} /> : "" }
            </div>
            : ""
          }

          {(item.showEditor) ?
            <div className="mmu-menu-item-row mmu-menu-item-row--editor">
              <MenuItemEditor item={item} index={props.index} parent={parent}></MenuItemEditor>
            </div>
            : ""
          }

          {/* <div>
            DRAGGABLE ID: {draggableOptions.active?.id}
            DRAGGABLE OVER: {draggableOptions.over?.id}
            DRAGGING: {draggableOptions.isDragging}

          </div> */}
          
          { /*
          <div className={"mmu-menu-item-row mmu-menu-item-row--drop-target " + ((draggableOptions.active?.id && draggableOptions.active?.id != item.ID) ? " mmu-menu-item-row--drop-target--active" : "")}>
          {(draggableOptions.active?.id && draggableOptions.active?.id != item.ID) ? <MenuItemDropTarget position="nested" item={item} /> : ""}
          </div>
          */ }
        </div>
      </div>
    </div>

    {/* {item.expanded ? <div className='mmu-menu-item-container-spacer'></div> : ""} */}
    
    {(!isHidden() && (draggableOptions.active?.id != item.ID)) ? 
      <MenuItemDropTarget position="after" item={item} style={dropTargetStyle} />
      : ""
    }

    {/* 
    {(draggableOptions.active?.id == item.ID || item.expanded) ? <div className='mmu-menu-item-container-spacer'></div> : "" }
    */}

    { /* ((props.index == (store.menuItems.length - 1)) ) ? 
      <MenuItemDropTarget position="bottom" item={item} />  
      : ""
     */}      

    </>
  )
}

/* Set up our PropTypes for validation */
// SearchResult.propTypes = {
//   result: PropTypes.shape(
//     {
//       _id: PropTypes.string.isRequired,
//       picture: PropTypes.string,
//       name: PropTypes.string.isRequired
//     }
//   )
// }

export default MenuItem;