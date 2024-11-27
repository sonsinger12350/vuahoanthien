import React, { useState, useContext } from 'react';
import { fetchAndExpandItem, setItemExpanded, insertPlaceholderItem } from '../helpers/MenuListHelper';
import { StoreContext } from '../utils/store';
import { applyItemAttribute, updateItem } from '../utils/utils';
import IconButton from './core/IconButton';
import ModalDeleteConfirm from './ModalDeleteConfirm';
import appDebug from "../utils/appDebug";
import { AppConfig } from "../config/AppConfig";

//import PropTypes from 'prop-types';

const MenuItemActions = (props) => {

  const store = useContext(StoreContext);
  const [showDeleteConfirmation, setShowDeleteConfirmation] = useState(false);
  const { item } = props;

  const toggleDeleteItem = (e, item) => {

    const shouldMark = (item.markedForDelete == true) ? false : true;

    if (shouldMark) {

      if (item.childCount > 0 || item.has_fetchable_children > 0) {
        setShowDeleteConfirmation(true);
      }
      else {
        store.setMenuItems(updateItem(store.menuItems, {...item, 'markedForDelete': true}));
        store.markChangedItem(item, store.menuItemChangeTypes.delete);
      }
    }
    else {

      let updatedItems = updateItem(store.menuItems, item);
      let applyToChildren = false;

      if (item.deleteTree) {
        applyToChildren = true;
      }
        
      updatedItems = applyItemAttribute(updatedItems, item,
        function(referencedItem) {
          store.unMarkChangedItem(referencedItem, store.menuItemChangeTypes.delete);
          return {...referencedItem, markedForDelete: false, deleteTree: false}
        }, 
        { applyToChildren: applyToChildren }
      );


      store.setMenuItems(updatedItems);
    }

  }
  
  const handleAddAction = async (e) => {

    let updatedItems;

    if (!item.wasFetched && item.has_fetchable_children) {
      updatedItems = await fetchAndExpandItem(store.menuID, store.menuItems, item);
      updatedItems = insertPlaceholderItem(updatedItems, { parentItem: item });
    }
    else {
      updatedItems = insertPlaceholderItem(store.menuItems, { parentItem: item });
      updatedItems = setItemExpanded(updatedItems, item);
    }

    store.setMenuItems(updatedItems);
    
  }

  const handleEditAction = async (e) => {

    item.showEditor = true;

    store.setMenuItems(updateItem(store.menuItems,item));
    
  }

  const handleDeleteConfirm = (e, deleteAllVal) => {

    const deleteTree = (deleteAllVal > 0) ? true : false;
    const updatedItems = applyItemAttribute(store.menuItems, item,
      function(referencedItem) {
        appDebug(AppConfig.debugCategoryDefault, 'delete confirm for item', referencedItem);
        store.markChangedItem(referencedItem, store.menuItemChangeTypes.delete);
        return {...referencedItem, markedForDelete: true, deleteTree: deleteTree};
      }, 
      { applyToChildren: deleteTree }
    );

    store.setMenuItems(updatedItems);
    setShowDeleteConfirmation(false);

  }

  const handleDeleteCancel = (e) => {
    setShowDeleteConfirmation(false);
  }

  return (
    <>
    <div className="mmu-menu-item-actions">
      <IconButton clickHandler={(e) => handleEditAction(e)} name="edit" icon="edit" disabled={item.markedForDelete ? true : false}>Edit</IconButton>
      <IconButton clickHandler={(e) => { toggleDeleteItem(e, item); }} name={item.markedForDelete ? "restore" : "delete"} icon={item.markedForDelete ? "undo" : "no"}>{item.markedForDelete ? "Restore" : "Remove"}</IconButton>
      <IconButton clickHandler={(e) => handleAddAction(e)} name="add" icon="plus" disabled={item.markedForDelete ? true : false}>Add</IconButton>
    </div>
    <ModalDeleteConfirm isOpen={showDeleteConfirmation} handleCancel={handleDeleteCancel} handleConfirm={handleDeleteConfirm} />
    </>
  );
} 

export default MenuItemActions;