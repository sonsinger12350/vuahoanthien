import { React, useState, useContext, useRef, useEffect} from 'react';
import { StoreContext } from '../utils/store';
import { removeItemAndUpdateParent, updateItem } from '../utils/utils';

import { Tab, Tabs, TabList, TabPanel } from "react-tabs";
import 'react-tabs/style/react-tabs.css';
import MenuItemEditorForm from "./MenuItemEditorForm";
import PostsearchInterface from "./PostSearchInterface";
import appDebug from "../utils/appDebug";
import { AppConfig } from "../config/AppConfig";

//import PropTypes from 'prop-types';

const ItemTypes = {
  post: "post_type",
  link: "custom"
}

const MenuItemEditor = (props) => {

  const { item, index, parent } = props;

  const [selectedTabIndex, setSelectedTabIndex] = useState(0);
  const [titleWasEdited, setTitleWasEdited] = useState(false);
  const store = useContext(StoreContext);
  const origItem = useRef(null);

  useEffect(
    () => {
      if (!origItem.current) {
        appDebug(AppConfig.debugCategoryDefault, 'resetting origItem');
        origItem.current = {...item};

        if (item.type == ItemTypes.link) {
          /* @TODO make this more robust */
          setSelectedTabIndex(1);
        }        
      }
    }, []
  );

  const cancelEdit = (e) => {

    store.unMarkChangedItem(item, store.menuItemChangeTypes.edit);

    if (item.isNewAddition) {
      store.setMenuItems(removeItemAndUpdateParent(store.menuItems, item));
    }
    else {
      const revertedItem = {...origItem.current};
      revertedItem.showEditor = false;
      
      store.setMenuItems(updateItem(store.menuItems, revertedItem));
      
    }

  }

  const applyPostFromResults = (searchResults, idToAdd) => {
    const resultsIndex = searchResults.findIndex(post => post.ID == idToAdd);
    const postData = searchResults[resultsIndex];

    const type_index = store.postTypes.findIndex(type => type.name == postData.post_type);
    const type_label = store.postTypes[type_index].labels.singular_name;

    item.object = postData.post_type;
    item.object_id = postData.ID;
    item.object_title = postData.post_title;

    if (!titleWasEdited) {
      item.title = postData.post_title;
    }
    
    item.type = "post_type";
    item.type_label = type_label;

    store.setMenuItems(updateItem(store.menuItems, item));
    store.markChangedItem(item, store.menuItemChangeTypes.edit);

  }

  const handleTabChange = (index, lastIndex, e) => {
    appDebug(AppConfig.debugCategoryDefault, 'tab changed', e, e.target.getAttribute('item_type'));

    setSelectedTabIndex(index);

    const newType = ItemTypes[e.target.getAttribute('item_type')];

    store.setMenuItems(updateItem(store.menuItems, {...item, 'type': newType}));
  }

  const fieldEditEventHandler = (e) => {

    const fieldKey = e.target?.getAttribute('name') ?? null;

    if (fieldKey == 'title') {
      setTitleWasEdited(true);
    }

  }

  return (
    <>
    <div className="mmu-menu-item-editor">
      {/* <div className="mmu-menu-item-summary">
        <div className={"mmu-menu-item-title" + ((item.marked_for_delete) ? " mmu-menu-item-title--delete" : "")}>INDEX: {index} | {item.ID}:{item.title} | parent id: {parent ? parent.ID : "null"} | parent expanded: {(parent && parent.expanded) ? "true": "false"} | expanded: {item.expanded ? "true" : "false"} | depth: {item.depth}</div>
      </div> */}

      <Tabs onSelect={handleTabChange} selectedIndex={selectedTabIndex}>
        <TabList>
          <Tab item_type="post">Content</Tab>
          <Tab item_type="link">Custom Link</Tab>
        </TabList>

        <TabPanel>
          <div className="mmu-menu-item-editor-mode--content">
            
            <div className="mmu-menu-item-editor-section">
              <div className="mmu-menu-item-editor-section-header">
                <h2 className="mmu-menu-item-editor-section-heading">Menu Item Settings</h2>
              </div>
              
              <MenuItemEditorForm item={item} origItem={origItem} fieldEditEventHandler={fieldEditEventHandler}></MenuItemEditorForm>
            </div>
            
            <div className="mmu-menu-item-editor-section">
              <div className="mmu-menu-item-editor-section-header">
                <h2 className="mmu-menu-item-editor-section-heading">Find Content</h2>
              </div>
              <PostsearchInterface item={item} postSelectAction={applyPostFromResults} />
            </div>
          </div>
        </TabPanel>
        <TabPanel>
          <div className="mmu-menu-item-editor-section">
            <h2>Custom Link</h2>
            <MenuItemEditorForm item={item}></MenuItemEditorForm>
          </div>
        </TabPanel>
      </Tabs>
      
      <div className="mmu-menu-item-editor-actions mmu-menu-item-editor-actions--secondary">
        <a className="mmu-button mmu-button--secondary" onClick={(e) => cancelEdit(e)}>Cancel {item.isNewAddition ? "Add" : "Edit"}</a>
      </div>
      

    </div>
    
    </>
  );
} 

export default MenuItemEditor;