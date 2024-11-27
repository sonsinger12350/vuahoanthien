import { applyItemAttribute, processNewItems, generateUniqueSerial, findItemIndex, reCountChildren } from "../utils/utils";  
import { MenuItemService } from "../services/MenuItemService";
import appDebug from "../utils/appDebug";
import { AppConfig } from "../config/AppConfig";

/**
 * Set a particular item to be expanded or collapsed. 
 * If setting to collapsed, it will iterate through all children (and their children)
 * to ensure a full collapse of the nested tree.
 * @param {Array<menuItem>} items 
 * @param {menuItem} item 
 * @param {boolean} expanded 
 * @returns 
 */
export const setItemExpandValue = (items, item, expanded) => {
  
  appDebug(AppConfig.debugCategoryDefault, 'setting item to expanded value', item, expanded);

  const applyToChildren = (expanded == false) ? true : false;

  return applyItemAttribute(
    items,
    item, 
    (item, recursingReferencedItem) => {
      return {...item, 'expanded': expanded }
    },
    { 'applyToChildren': applyToChildren }
  );

}

export const setItemExpanded = (items, item) => {
  return setItemExpandValue(items, item, true);
}

export const setItemCollapsed = (items, item) => {
  return setItemExpandValue(items, item, false);
}

export const fetchAndInsertSubItems = async (menuID, items, item, options = {}) => {

  const { skipSetWasFetched } = options;
  const newItems = await MenuItemService.fetchChildItems(menuID, item.ID);

  const updatedItems = processNewItems(items, newItems);
  const updatedIndex = updatedItems.findIndex(search_item => search_item.ID == item.ID);
  
  let updatedItem = {};
  
  if (updatedIndex > -1) {
    updatedItem = updatedItems[updatedIndex];

    if (!skipSetWasFetched) {
      updatedItems[updatedIndex].wasFetched = true;
    }
  }

  appDebug(AppConfig.debugCategoryDefault, 'updated item after insert', updatedItem);

  return Promise.resolve(updatedItems);
}

export const fetchAndExpandItem = async (menuID, items, item, options = {}) => {

  let updatedItems = await fetchAndInsertSubItems(menuID, items, item, options);

  /* If the parent is marked as "delete this entire tree", make sure that is reflected in the new items */
  if (item.deleteTree) {
    updatedItems = applyItemAttribute(updatedItems, item,
      function(referencedItem) {
        return {...referencedItem, markedForDelete: true, deleteTree: true};
      }, 
      { applyToChildren: true }
    );    
  }

  return Promise.resolve(setItemExpanded(updatedItems, item));

}

export const insertPlaceholderItem = (items, options = {}) => {
    
  const { parentItem, newIndex } = options;

  let newItem = {
    'ID': generateUniqueSerial(), 
    'isNewAddition': true, 
    'showEditor': true,
    'depth': 0,
    'title': ''
  }

  let index = newIndex > -1 ? newIndex : 0;

  if (parentItem) {
    index = newIndex > -1 ? newIndex : findItemIndex(items, parentItem) + 1;
    newItem.parentItem = parentItem;
    newItem.menu_item_parent = parentItem.ID;
    newItem.depth = parentItem.depth + 1; 
  }

  let updatedItems = [...items];
  updatedItems.splice(index, 0, newItem);

  if (parentItem) {
    updatedItems = reCountChildren(updatedItems, parentItem);
  }

  return updatedItems;
}