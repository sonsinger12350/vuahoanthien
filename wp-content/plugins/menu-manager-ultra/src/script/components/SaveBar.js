import { StoreContext } from '../utils/store';
import React, { useContext, useEffect, useState } from 'react';
import { MenuItemService } from '../services/MenuItemService';
import StyledButton from "./core/StyledButton";
import appDebug from "../utils/appDebug";
import { AppConfig } from "../config/AppConfig";

const SaveBar = () => {

  const store = useContext(StoreContext);
  const [isSaved, setIsSaved] = useState(false);
  const [showMessage, setShowMessage] = useState(false)
  const [hideMessage, setHideMessage] = useState(false);
  const [message, setMessage] = useState(null);

  useEffect(() => {
    
    if (hideMessage) {
      const timer = setTimeout(() => {
        setShowMessage(false);
        setIsSaved(false);
      }, 2500);
      
      return () => {
        setShowMessage(false);
        setIsSaved(false);
        clearTimeout(timer);
      }
    }
    
  }, [hideMessage]);

  useEffect(() => {

    if (store.isAnyItemChanged) {
      setMessage('You have unsaved changes');
      setIsSaved(false);
      setShowMessage(true);
    }
    else {
      setShowMessage(false);
      setIsSaved(true);
    }
    
  }, [store.isAnyItemChanged]);


  const performSave = async (event) => { // eslint-disable-line no-unused-vars

    store.setIsSaving(true);
    setMessage('Saving...');
    setShowMessage(true);

    const result = await MenuItemService.saveItems(store.menuID, store.menuItems);
    appDebug(AppConfig.debugCategoryDefault, 'Save result', result);
    const updatedItems = result.items.map(
      (item) => {
        return {...item, isNewAddition: false, showEditor: false};
      }
    );

    store.clearChangedItems();
    store.setMenuItems(updatedItems, {skipCheckForChanged: true});  
    setMessage("Menu Items Saved");
    setHideMessage(true);
    store.setIsSaving(false);
    setIsSaved(true);
  }

  return (
    <div className={"mmu-save-bar " + ((store.isAnyItemChanged || showMessage) ? "mmu-save-bar--active" : "")}>
      {showMessage ? <div className="mmu-save-bar-message">{message}</div> : ""}
      {store.isAnyItemChanged || showMessage ?
      <> 
        { !isSaved ?
        <StyledButton onClick={performSave} name="save">
          { store.isSaving ? <span className="dashicons dashicons-update mmu-icon-button--item-loading"></span>: "Save" }
        </StyledButton>
        : "" } 
      </>
      : ""}
    </div>
  );
}

export default SaveBar;