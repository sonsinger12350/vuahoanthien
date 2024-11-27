import React, { useContext, useRef, useCallback, useEffect } from 'react';
import appDebug from "../utils/appDebug";
import { AppConfig } from "../config/AppConfig";
import { StoreContext } from '../utils/store';
import { updateItem } from '../utils/utils';
import md5 from "md5";

const Fieldsets = {
  main: 'Main',
  advanced: 'Advanced'
}

const MenuItemEditorForm = (props) => {

  const store = useContext(StoreContext);
  
  const { item, origItem, fieldEditEventHandler } = props;

  const editableFields = useRef([
    { 'key': 'object_id', 'label': 'Page/Post ID', 'input_type': 'none', 'item_type': 'post_type', fieldset: Fieldsets.main },
    { 'key': 'object_title', 'label': 'Page/Post Title', 'input_type': 'none', 'item_type': 'post_type', fieldset: Fieldsets.main },
    { 'key': 'title', 'label': 'Menu Item Title', 'input_type': 'text', fieldset: Fieldsets.main },
    { 'key': 'description', 'label': 'Description', 'input_type': 'text', fieldset: Fieldsets.advanced  },
    { 'key': 'title_attr', 'label': 'Title Attribute', 'input_type': 'text', fieldset: Fieldsets.advanced },
    { 'key': 'target', 'label': 'Open in New Window', 'input_type': 'checkbox', 'value_unset': "", 'value': '_blank', fieldset: Fieldsets.advanced },
    { 'key': 'classes', 'label': 'CSS Classes', 'input_type': 'text', 'editable_data_type': 'string', fieldset: Fieldsets.advanced },
    { 'key': 'url', 'label': 'URL', 'input_type': 'text', 'editable_data_type': 'string', fieldset: Fieldsets.main, 'item_type': 'custom' }
  ]);

  useEffect(
    () => {
      if (!store.isSaving) {
        checkEditsAgainstOriginalItem();
      }
    }, [store.menuItems, origItem]
  );

  const collectItemFieldValues = (item) => {

    let ret = {};

    editableFields.current.map(
      (fieldData) => {
        ret[fieldData.key] = getItemFieldValue(item, fieldData);
      }
    );

    return ret;

  }

  const checkEditsAgainstOriginalItem = () => {

    if (origItem && origItem.current != null) {

      const origValues = collectItemFieldValues(origItem.current);
      const curValues = collectItemFieldValues(item);

      const origHash = md5(JSON.stringify(origValues));
      const curHash = md5(JSON.stringify(curValues));
      
      if (origHash == curHash) {
        appDebug(AppConfig.debugCategoryDefault, "item fully reverted");
        store.unMarkChangedItem(item, store.menuItemChangeTypes.edit);
      }
      else {
        store.markChangedItem(item, store.menuItemChangeTypes.edit);
      }
    }
  }

  const getFieldsetFields = useCallback((fieldset) => {

    const fields = editableFields.current.filter(
      fieldData => (fieldData.fieldset == Fieldsets[fieldset]) && (!fieldData.item_type || fieldData.item_type == item.type)
    );

    return fields;
  }, [editableFields.current, item.type]);
  
  const getEditableFieldValue = (item, fieldData) => {

    let retValue = getItemFieldValue(item, fieldData);

    if (fieldData.editable_data_type == 'string' && Array.isArray(retValue)) {
      retValue = retValue.join(" ");
    }
    else if (fieldData.input_type == 'checkbox') {
      retValue = fieldData.value;
    }
    
    return retValue;

  }

  const getItemFieldValue = (item, fieldData) => {

    const fieldKey = fieldData.key;

    if (fieldKey) {
      const ret = typeof(item[fieldKey]) != 'undefined' ? item[fieldKey] : null;
      appDebug(AppConfig.debugCategoryDefault, "Item field value for ", fieldKey, ret);
      return ret;
    }

    return null;

  }

  const handleEditableFieldKeyDown = (e) => {
    
    return fieldEditEventHandler(e);
  }

  const handleEditableFieldChange = (e) => {
    
    const fieldKey = e.target?.getAttribute('name') ?? null;

    if (!fieldKey) {
      appDebug(AppConfig.debugCategoryDefault, "Invalid or missing field key when editing");
    }
    else {
      const fieldDataIndex = editableFields.current.findIndex(fieldData => fieldData.key == fieldKey);
      
      if (fieldDataIndex <= -1) {
        appDebug(AppConfig.debugCategoryDefault, `Could not find key ${fieldKey} in editableFields`);
      }
      else {
        const fieldData = editableFields.current[fieldDataIndex];

        if (fieldData.storage_type == 'array') {
          const newValues = e.target?.value?.toString().split(" "); 
          item[fieldKey] = newValues;
        }
        else {
          let value;
          
          if (fieldData.input_type == 'checkbox') {
            if (!e.target.checked) {
              value = typeof(fieldData.value_unset) != 'undefined' ? fieldData.value_unset : null;
            }
            else {
              value = fieldData.value;
            }
          }
          else {
            value = e.target.value;
          }
          
          item[fieldKey] = value;
        }
  
        store.setMenuItems((updateItem(store.menuItems, item)));
        store.markChangedItem(item, store.menuItemChangeTypes.edit);
        appDebug(AppConfig.debugCategoryDefault, 'updated items after field change', store.menuItems);
      }
    }
  }

  return (
  <div className="mmu-editor-form">
    <div className="mmu-editor-form-fields">
      {Object.keys(Fieldsets).map (
        (fieldsetKey) => {

          let retVal = [];
          
          if (fieldsetKey != 'main') retVal.push();
          
          retVal.push(
            getFieldsetFields(fieldsetKey).map(
              (fieldData) => {

                const itemFieldValue = getItemFieldValue(item, fieldData);
                const editableFieldValue = getEditableFieldValue(item, fieldData);
                const checked = (fieldData.input_type == 'checkbox' && itemFieldValue == editableFieldValue) ? true : false;

                return (
                  <div key={fieldData.key} className="mmu-editor-field">
                    <label htmlFor={"mmu-input-" + fieldData.key}>{fieldData.label}</label>
                    {fieldData.input_type != 'none'
                    ?
                    <input id={"mmu-input-" + fieldData.key} type={fieldData.input_type} name={fieldData.key} defaultChecked={checked} onKeyDown={handleEditableFieldKeyDown} onChange={handleEditableFieldChange} value={editableFieldValue} />
                    : 
                    <span className="mmu-editor-field-value-static">{getEditableFieldValue(item, fieldData)}</span>
                    }
                  </div>
                )
              }
            )
          );

          return fieldsetKey != 'main' ? (<details><summary>{Fieldsets[fieldsetKey]}</summary><div className="mmu-editor-form-fields">{retVal}</div></details>) : retVal;
        }
      )}
    </div>
  </div>
  );
}

export default MenuItemEditorForm;