/* TODO List

- Deleting all children of top level item doesnt remove expander arrow
- un-removal of child item needs logic (maybe done?)
- NTH: move modal delete confrim outside menu item actions so we dont rernder the modal/portal unnecessarily
- style add menu popup
- PRO: quick move to other item?
- PRO: search?
- finalize styling


Manual Tests

- Create new menu 
- Add item to menu with no items
- Add item to menu with items
- Add sub-item to menu item
- edit top level item
- edit sub item
- delete top level item & sub items
- delete top level item & move sub items (pro only)
- delete item but undelete one or more sub-items (pro only)
- drag single item to nest it beneath another item
- drag item + children to nest it beneath another item
- drag item above another on the same level
- drag item below another on the same level
- drag item to first top level item
- drag item to last top level item
- drag item to last item in a nested tree
*/

import React, { useContext, useState, useEffect } from 'react';
import PropTypes from 'prop-types';
import MenuItem from "./MenuItem";
import { MenuItemService } from '../services/MenuItemService';
import { applyItemAttribute, processNewItems, moveItem, getParentItem, reCountChildren } from "../utils/utils";
import { StoreContext } from '../utils/store';
import SaveBar from './SaveBar';
import IconButton from './core/IconButton';
import {
  DndContext, 
  rectIntersection,
  KeyboardSensor,
  PointerSensor,
  useSensor,
  useSensors,
  DragOverlay,
} from '@dnd-kit/core';
import { MenuItemDragOverlay } from './MenuItemDragOverlay';
import { fetchAndExpandItem, insertPlaceholderItem } from '../helpers/MenuListHelper';
import FieldService from '../services/FieldService';
import appDebug from "../utils/appDebug";
import { AppConfig } from "../config/AppConfig";

const DROP_POSITION_TYPE_NESTED = 'nested';
const DROP_POSITION_TYPE_AFTER = 'after';
const DROP_POSITION_TYPE_TOP = 'top';
const DROP_POSITION_TYPE_BOTTOM = 'bottom';

const MenuItemList = (props) => {
	
  const store = useContext(StoreContext);
  const [activeDragId, setActiveDragId] = useState(null);
  // eslint-disable-next-line no-unused-vars
  const [dragOverId, setDragOverId] = useState(null);
  const [activeDragItem, setActiveDragItem] = useState(null);
  const { menuID } = props;

  /**
   * Set up sensors for DND Kit
   */
  const sensors = useSensors(
    useSensor(PointerSensor, {
    }),
    useSensor(KeyboardSensor, {
    })
  );  

  /**
   * Effect that fires on selection of a menu to show 
   */
  useEffect(
    () => {
      
      (async() => {
        if (menuID != null) {

          appDebug(AppConfig.debugCategoryDefault, 'fetching items', menuID);
          
          const customFields = await FieldService.getFieldSettings();

          store.setActiveCustomFields(customFields);

          appDebug(AppConfig.debugCategoryDefault, 'custom fields', customFields);

          const menuItems = await MenuItemService.fetchTopLevelItems(menuID);

          if (menuItems.length > 0) {
            store.setMenuItems(processNewItems([], menuItems));
          }
          else {
            store.setMenuItems([]);
          }
        }
      })()

    }, [menuID]
  );  	

  const determineDropPositionType = (event) => {

    const { over } = event;
    
    if (over?.id) {
      const matches = over.id.toString().match(/droppable-([A-Za-z]+)-([A-Za-z0-9]+)/);
      if (matches.length > 1) {
        let position = matches[1];
        let id = matches[2];

        appDebug(AppConfig.debugCategoryDefault, 'drop position id is', id);
        appDebug(AppConfig.debugCategoryDefault, 'nested drop id is', store.nestedDragID);

        if (position != DROP_POSITION_TYPE_TOP) {
          /* Check to see if the item was moved over to be nested */
          if (id == store.nestedDragID) {
            appDebug(AppConfig.debugCategoryDefault, 'Nested Drop');
            position = DROP_POSITION_TYPE_NESTED;
          }
        }

        return position;
      }
    }

    return null;

  }

  const handleAddItem = async (e, options = {}) => {

    let updatedItems = store.menuItems;

    updatedItems = insertPlaceholderItem(updatedItems, options);
    store.setMenuItems(updatedItems);
    
  }

  const handleDragEnd = async (event) => {
    const {active, over} = event;
    
    if (!active || !over) {
      return false;
    }

    appDebug(AppConfig.debugCategoryDefault, 'active id', active.id);
    appDebug(AppConfig.debugCategoryDefault, 'over id', over.id);

    const overID = parseInt(over.id.substring(over.id.lastIndexOf('-') + 1));

    appDebug(AppConfig.debugCategoryDefault, 'overID', overID);

    if (active.id != overID) {
      
      const dropPositionType = determineDropPositionType(event);

      let activeIndex = store.menuItems.findIndex(item => item.ID == active.id);
      let overIndex = store.menuItems.findIndex(item => item.ID == overID);

      let newPosition = overIndex;
      let cancelDrag = false;

      let updatedItems = [ ...store.menuItems ];
      let newParentItem = false;

      /**
       * Determine if this item is being dragged as a child item of another item, 
       * which is currently dependent on how far right the dragged item is when it is dropped
       * 
       */
      if (dropPositionType == DROP_POSITION_TYPE_NESTED) {
        
        /**
         * If this item was dragged as a child of another item, make sure
         * that we've already fetched that item's children from the server. If not,
         * do it now.
         */
        appDebug(AppConfig.debugCategoryDefault, 'moving into new parent');

        if (updatedItems[overIndex].has_fetchable_children && !updatedItems[overIndex].wasFetched) {
          appDebug(AppConfig.debugCategoryDefault, 'need to fetch items');
          updatedItems = await fetchAndExpandItem(store.menuID, store.menuItems, store.menuItems[overIndex]);

          /* Recalculate indexes since we just processed new items */
          activeIndex = updatedItems.findIndex(item => item.ID == active.id);
          overIndex = updatedItems.findIndex(item => item.ID == overID);
        }
        
        newParentItem = updatedItems[overIndex];
        
        newPosition = overIndex;

        appDebug(AppConfig.debugCategoryDefault, 'setting menu item parent to', newParentItem.ID);
        
        updatedItems[activeIndex].menu_item_parent = newParentItem.ID;
        updatedItems[overIndex].expanded = true;
        
        updatedItems = reCountChildren(updatedItems, updatedItems[overIndex]);
      }
      else if (dropPositionType == DROP_POSITION_TYPE_AFTER) {

        appDebug(AppConfig.debugCategoryDefault, 'dragging after');

        if ( typeof(updatedItems[overIndex].parentItem) != 'undefined' && updatedItems[overIndex].parentItem != null && typeof(updatedItems[overIndex].parentItem.ID) != 'undefined') {
          newParentItem = updatedItems[overIndex].parentItem;
        }
        else {
          appDebug(AppConfig.debugCategoryDefault, 'dragging after -> setting newParentItem null');
          newParentItem = null;
        }


      }
      else if (dropPositionType == DROP_POSITION_TYPE_TOP) {
        newPosition = 0;
        newParentItem = null;
      }
      else if (dropPositionType == DROP_POSITION_TYPE_BOTTOM) {
        appDebug(AppConfig.debugCategoryDefault, 'dragging to bottom');
        newParentItem = null;
        newPosition = updatedItems.length - 1;
      }      
      else {
        cancelDrag = true;
      }

      if (dropPositionType != DROP_POSITION_TYPE_TOP && (activeIndex > overIndex)) {
        //moving up
        newPosition++;
        
      }

      if (!cancelDrag) {
        appDebug(AppConfig.debugCategoryDefault, 'updatedItems[activeIndex]', updatedItems[activeIndex]);

        appDebug(AppConfig.debugCategoryDefault, 'active index -> new position', activeIndex, newPosition);

        updatedItems = moveItem(updatedItems, activeIndex, newPosition, newParentItem);

        store.setMenuItems(updatedItems);

        store.markChangedItem(updatedItems[newPosition], store.menuItemChangeTypes.position);

        if (newParentItem) {
          store.markChangedItem(newParentItem, store.menuItemChangeTypes.children);
        }

        appDebug(AppConfig.debugCategoryDefault, 'final updateditems', updatedItems);
      }
      
    }

    store.setNestedDragID(null);
  }

  function handleDragOver({ over }) {
    setDragOverId(over?.id ?? null);
  }


  const handleDragStart = (event) => {
    const {active} = event;
    
    appDebug(AppConfig.debugCategoryDefault, "dragStart", event);

    setActiveDragId(active.id);

    appDebug(AppConfig.debugCategoryDefault, 'active id', active.id);

    
    const active_index = store.menuItems.findIndex(item => item.ID === active.id);
    appDebug(AppConfig.debugCategoryDefault, 'active index', active_index);
    
    const item = store.menuItems[active_index];

    const collapsed_items = applyItemAttribute(store.menuItems, item, 
      (item) => {
        return {...item, 'expanded': false }
      },
      { applyToChildren: true }
    );


    store.setMenuItems(collapsed_items);
    
    setActiveDragItem(collapsed_items[active_index]);
  }
  
	return (
    <>
      {store.menuID
			?
			<div className="mmu-list-actions mmu-list-actions--top">
				<div className="mmu-actions-container">
					<IconButton onClick={(e) => handleAddItem(e)} name="add" icon="plus">Add Item</IconButton>
				</div>
			</div>
			: ""}
      <div id="mmu-items-list" className="mmu-items-list">
        <DndContext 
          sensors={sensors}
          collisionDetection={rectIntersection}
          onDragEnd={handleDragEnd}
          onDragStart={handleDragStart}
          onDragOver={handleDragOver}
        >

          {store.menuItems.map(
            (item, index) => {
              
              return (
                <MenuItem key={item.ID} parent={getParentItem(store.menuItems, item)} item={item} index={index} />
              )
            }
          )
          }  
          <DragOverlay>
            {activeDragId ? <MenuItemDragOverlay id={activeDragId} item={activeDragItem} /> : null}
          </DragOverlay>
        </DndContext>
        <SaveBar />
      </div>
      {store.menuItems?.length > 0
			?
      <div className="mmu-list-actions mmu-list-actions--bottom">
				<div className="mmu-actions-container">
					<IconButton onClick={(e) => handleAddItem(e, {newIndex: store.menuItems.length})} name="add" icon="plus">Add Item</IconButton>
				</div>
			</div>
      : ""
      }
    </>
);
}

/* Set up our PropTypes for validation */
MenuItemList.propTypes = {
  menuID: PropTypes.string
}

export default MenuItemList;