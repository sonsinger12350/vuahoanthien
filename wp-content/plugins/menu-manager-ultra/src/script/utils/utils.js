//import { arrayMoveMutable, arrayMoveImmutable } from 'array-move';
import appDebug from "./appDebug";

const DEBUG_CATEGORY = "utils";

export const processNewItems = function ( items, newItems ) {
  
  appDebug(DEBUG_CATEGORY, 'processing new items', newItems);
  
  let i = 0;
  let updatedItems = [...items];

  for (const item of newItems) {

      let parentIndex = -1;
      
      item.parentItem = null;
      item.markedForDelete = false;
      
      if (!item.list_initialized) {
        item.list_initialized = true;
        const parentId = parseInt(item.menu_item_parent) || 0;
      
        if (parentId > 0) {
          parentIndex = updatedItems.findIndex(item => item.ID == parentId);

          if (parentIndex > -1) {
            item.parentItem = updatedItems[parentIndex];
          }
        }

        item.depth = 0;
        item.childCount = 0;

        updatedItems.push(item);

        if ( item.parentItem != null ) {
          let nextInsertionIndex;
          [nextInsertionIndex, updatedItems] = moveChildrenIntoPlace(updatedItems, item.parentItem);

          //updatedItems = recalculateItemWithNewParent(updatedItems, item, item.parentItem);
        }
        // else {
        //   console.log('about to recalc with new parent');
          
        //   console.log('moving to', item, (parentIndex + updatedItems[parentIndex].childInsertIndex) + 1);
        //   arrayMoveMutable(updatedItems, index, (parentIndex + updatedItems[parentIndex].childInsertIndex) + 1);
        //   updatedItems[parentIndex].childInsertIndex = updatedItems[parentIndex].childInsertIndex + 1;
        // }
      //}
    }
    
    i++;
  }

  appDebug(DEBUG_CATEGORY, 'new items processed', updatedItems);

  return updatedItems;
}

export const moveItem = (items, curIndex, newIndex, newParentItem = false) => {

  let updatedItems = [...items];

  const prevParent = updatedItems[curIndex].parentItem;
  const item = updatedItems[curIndex];

  appDebug(DEBUG_CATEGORY, 'in moveItem', updatedItems, curIndex, newIndex);

  if (newParentItem === null) {
    appDebug(DEBUG_CATEGORY, 'new parent item is null)');
    updatedItems = assignRootParent(updatedItems, updatedItems[curIndex]);
  }
  else {
    
    if (updatedItems[curIndex].parentItem?.ID != newParentItem.ID) {
      appDebug(DEBUG_CATEGORY, 'assigning new parent');
      updatedItems = assignNewParent(updatedItems, updatedItems[curIndex], newParentItem);
    }
  }

  arrayMoveMutable(updatedItems, curIndex, newIndex);
  
  const updatedIndex = findItemIndex(updatedItems, item);

  appDebug(DEBUG_CATEGORY, 'updated index after move', updatedIndex);
  appDebug(DEBUG_CATEGORY, 'after array move', updatedItems);

  let parentItemForReorder = updatedItems[updatedIndex];
  
  if (updatedItems[updatedIndex].parentItem !== null) {
    const parentItemIndex = findItemIndex(updatedItems, updatedItems[updatedIndex].parentItem);
    parentItemForReorder = updatedItems[parentItemIndex];
  }

  let _;
  [_, updatedItems] = moveChildrenIntoPlace(updatedItems, parentItemForReorder);

  if (prevParent) {

    /* maybe TODO: move children into place for prevparent ?*/
    updatedItems = reCountChildren(updatedItems, prevParent);
  }

  appDebug(DEBUG_CATEGORY, 'final version inside moveItems', updatedItems);

  return updatedItems;
  
}

export const reCountChildren = (items, item) => {

  let updatedItems = [...items];

  const childIndexes = findChildIndexes(items, item);
  const itemIndex = findItemIndex(items, item);

  updatedItems[itemIndex].childCount = childIndexes.length;

  if (updatedItems[itemIndex].childCount == 0 ) {
    /*TODO - maybe there's a better way than setting these here*/
    updatedItems[itemIndex].has_fetchable_children = false;
    updatedItems[itemIndex].expanded = false;
  }

  return updatedItems;
}

export const assignNewParent = (items, item, newParentItem) => {

  let updatedItems = [...items];

  const myIndex = findItemIndex(updatedItems, item);
  const newParentIndex = findItemIndex(updatedItems, newParentItem);

  if (newParentIndex > -1) {
    item.depth = newParentItem.depth + 1;
    item.parentItem = newParentItem;
    item.menu_item_parent = newParentItem.ID;
    
    const childIndexes = findChildIndexes(updatedItems, newParentItem);
    updatedItems[newParentIndex].childCount = childIndexes.length;
    updatedItems[myIndex] = item;
  }

  return updatedItems;
}

export const assignRootParent = (items, item, options = {}) => {
  
  let updatedItems = [...items];

  const itemIndex = findItemIndex(updatedItems, item);
  if (itemIndex > -1) {

    const prevParent = updatedItems[itemIndex].parentItem;

    updatedItems[itemIndex].depth = 0;
    updatedItems[itemIndex].menu_item_parent = 0;
    updatedItems[itemIndex].parentItem = null;

    if (prevParent != null) {
      const parentIndex = findItemIndex(updatedItems, prevParent);
      updatedItems[parentIndex].childCount = reCountChildren(updatedItems, prevParent);
    }
  }

  return updatedItems;
}


export const moveChildrenIntoPlace = (items, parentItem, nextInsertionIndex = -1) => {

  const childItems = findChildItems(items, parentItem);
  
  let updatedItems = [...items];
  let i;
  let itemIndex = -1;
  let parentIndex = findItemIndex(items, parentItem);
  
  if (nextInsertionIndex == -1) {
    nextInsertionIndex = parentIndex; 
  }

  appDebug(DEBUG_CATEGORY, 'moving children into place', parentItem, parentIndex, childItems);

  if (childItems.length > 0) {

    parentItem.childCount = childItems.length;

    for (i = 0; i < childItems.length; i++) {
      itemIndex = findItemIndex(updatedItems, childItems[i]);
      if (itemIndex > -1) {
        
        //
        // Don't increment insertion index if the item exists before it in the list, 
        // because the indexes will already shift by one in that case (because the item 
        // essentially "disappears" from its earlier slot).
        //
        if (itemIndex > nextInsertionIndex) {
          nextInsertionIndex++;
        }

        appDebug(DEBUG_CATEGORY, 'child item / parent item', childItems[i], parentItem);

        updatedItems[itemIndex].depth = parentItem.depth + 1;
        updatedItems[itemIndex].parentItem = parentItem;
        updatedItems[itemIndex].menu_item_parent = parentItem.ID;

        appDebug(DEBUG_CATEGORY, 'Moving item ' + childItems[i].ID.toString() + ' as child: index ' + itemIndex.toString() + ' to next insertion point: ' + nextInsertionIndex.toString());
        arrayMoveMutable(updatedItems, itemIndex, nextInsertionIndex);
        
        //nextInsertionIndex++;
        
        let subChildItems = findChildItems(updatedItems, childItems[i]);

        if (subChildItems.length > 0) {
          appDebug(DEBUG_CATEGORY, 'recursing child move');

          [nextInsertionIndex, updatedItems] = moveChildrenIntoPlace(updatedItems, childItems[i], nextInsertionIndex);
        }
      }
    }
    
  } 

  return [nextInsertionIndex, updatedItems];
}

export const findItemIndex = (items, item) => {
  return items.findIndex((search_item) => search_item.ID == item.ID);
}

export const findChildIndexes = (items, item) => {
  return findIndexAll(items, (search_item) => search_item.menu_item_parent == item.ID);
}

export const findChildItems = (items, item) => {
  return items.filter((search_item) => search_item.menu_item_parent == item.ID);
}

export const getParentItem = (items, item) => {

  if (item.menu_item_parent) {
    const parentIndex = items.findIndex((search_item) => search_item.ID == item.menu_item_parent);
    if (parentIndex > -1) {
      return items[parentIndex];
    }
  }

  return null;
}

export const findIndexAll = (items, callback) => {

  // let foundItems = [];
  // let i = -1;

  return items.reduce((acc, item, index) => {
    
    if (callback.call(this, item)) {
      return [
        ...acc,
        index
      ];
    }

    return [...acc];
  }, []);


  // while ((i = items.indexOf(val, i+1)) != -1){
  //     foundItems.push(i);
  // }
  
  // return foundItems;

}

export const applyItemAttribute = (items, referencedItem, callback, options = { applyToChildren: false }, recursingReferencedItem = 0) => {

  const { applyToChildren } = options;
  let updatedItems = [...items];

  if (referencedItem.ID) {

    const referencedIndex = items.findIndex(item => item.ID === referencedItem.ID);

    if (referencedIndex > -1) {

      updatedItems[referencedIndex] = callback.call(this, referencedItem, recursingReferencedItem);

      if (applyToChildren) {

        const childItemIndexes = findIndexAll(updatedItems, (item => (item && (item.menu_item_parent == referencedItem.ID))));

        if (childItemIndexes.length > 0) {
          let i;
          let childItem;

          for(i = 0; i < childItemIndexes.length; i++) {
            childItem = updatedItems[childItemIndexes[i]];
            recursingReferencedItem++;
            updatedItems = applyItemAttribute(updatedItems, childItem, callback, options, recursingReferencedItem);
            recursingReferencedItem--;
          }
        }
      }
    }
  }

  return updatedItems;
}

export const findMenuItemByID = function(items, id) {
  const index = items.findIndex(item => item.ID == id);

  if (index > -1) {
    return items[index];
  }

  return null;
}

export const removeItem = function(items, item) {

  const index = items.findIndex(search_item => search_item.ID == item.ID);

  if (index > -1) {
    const updatedItems = [...items];
    updatedItems.splice(index, 1);
    return updatedItems;
  }

  return items;

}

export const updateItem = function(items, item) {

  const index = items.findIndex(search_item => search_item.ID == item.ID);

  if (index > -1) {
    const updatedItems = [...items];
    updatedItems[index] = item;
    return updatedItems;
  }

  return items;

}

export const removeItemAndUpdateParent = function(items, item) {

  let parentID = null;
  const index = items.findIndex(search_item => search_item.ID == item.ID);

  if (index > -1) {

    if (item.menu_item_parent) {
      parentID = item.menu_item_parent;
    }

    let updatedItems = [...items];
    updatedItems.splice(index, 1);

    if (parentID) {
      const parent = findMenuItemByID(updatedItems, parentID);
      appDebug(DEBUG_CATEGORY, 'in remove and update, parent is', parent, 'parent ID is', parentID);
      updatedItems = reCountChildren(updatedItems, parent);
    }

    return updatedItems;
  }

  return items;

}

function arrayMoveMutable(array, fromIndex, toIndex) {
	const startIndex = fromIndex < 0 ? array.length + fromIndex : fromIndex;

	if (startIndex >= 0 && startIndex < array.length) {
		const endIndex = toIndex < 0 ? array.length + toIndex : toIndex;

		const [item] = array.splice(fromIndex, 1);

    appDebug(DEBUG_CATEGORY, 'moved out item', item);
    array.splice(endIndex, 0, item);
	}
}

export const generateUniqueSerial = () => {  
  return 'xxxx-xxxx'.replace(/x/g, () => {  
    const r = Math.floor(Math.random() * 12)
    return r.toString(12)
  })
}