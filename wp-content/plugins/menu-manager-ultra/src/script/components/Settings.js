import React, { useRef, useEffect, useCallback, useState, useContext } from 'react';
import FieldService from '../services/FieldService';
import { MenuItemService } from '../services/MenuItemService';
import { StoreContext } from '../utils/store';
import StyledButton from './core/StyledButton';
import appDebug from "../utils/appDebug";
import { AppConfig } from "../config/AppConfig";

const Settings = (props) => {

  const { setShowSettings } = props
  const store = useContext(StoreContext);
  const [customFields, setCustomFields] = useState([]);
  const [fieldSettings, setFieldSettings] = useState([]);
  const fieldSettingsFetched = useRef(false);
  const settingsInitialized = useRef(false);

  const fetchCustomFields = useCallback(
    () => {
      return new Promise(
        async (resolve, reject) => {
          resolve(await FieldService.fetchDefinedCustomFields());
        }
      );
        
      // console.log('fields result', result);
      // setCustomFields(result);
    }
  );

  const fetchFieldSettings = () => {
    
    return new Promise(
      async (resolve, reject) => {
        resolve(await FieldService.getFieldSettings());
      }
    );

    // const result = await FieldService.getFieldSettings();
    // fieldSettingsFetched.current = true
    // console.log('saved fields result', result);
    
  };

  const applyFieldSettings = (fieldSettings, customFields) => {

    appDebug(AppConfig.debugCategoryDefault, 'applying field settings', fieldSettings);
    
    if (typeof(customFields) != 'undefined' && customFields.length > 0) {
      let updatedFields = customFields.map(
        (customField) => {

          appDebug(AppConfig.debugCategoryDefault, "customField", customField);

          let selected = 0;
          
          if (Array.isArray(fieldSettings) && fieldSettings.length > 0) {
            const thisFieldSettings = fieldSettings.find(
              setting => setting.field_key == customField.field_key
            );

            if (thisFieldSettings && 
              typeof(thisFieldSettings['enabled']) != 'undefined' &&
              thisFieldSettings['enabled'] == 1) {
                selected = 1;
            }
          }

          return {...customField, 'enabled': selected}
        }
      );

      setCustomFields(updatedFields);

      appDebug(AppConfig.debugCategoryDefault, 'updated fields', updatedFields);
    }
    
  }

  const initializeSettings = () => {
    fetchCustomFields().then(
      (customFields) => {
        appDebug(AppConfig.debugCategoryDefault, 'fields result', customFields);
        
        fetchFieldSettings().then(
          (fieldSettings) => {
            setFieldSettings(fieldSettings);
            applyFieldSettings(fieldSettings, customFields);
          }
        )
        
      }
    )
  }

  useEffect(
    () => {
			if (settingsInitialized.current == false) {
        settingsInitialized.current = true;
        initializeSettings();
      }
    }, []
  );  

  const saveSettings = async () => {

    appDebug(AppConfig.debugCategoryDefault, 'Saving settings', fieldSettings);

    await FieldService.saveFieldSettings(
      fieldSettings
    );

    if (store.menuID != null) {
      const newItems = await MenuItemService.refreshItems(store.menuID, store.menuItems, 'object_fields');

      store.setMenuItems(newItems);
    }

    setShowSettings(false);
  }

  const handleCheckboxChange = (e) => {

    let updatedSettings = fieldSettings;
    
    const fieldInfo = customFields.find(
      info => info.field_key == e.target.name 
    );

    const settingsIndex = updatedSettings.findIndex(
      setting => setting.field_key == e.target.name
    );

    if (settingsIndex > -1) {
      updatedSettings.splice(settingsIndex, 1);
    }

    if (e.target.checked == true) {
      updatedSettings.push({...fieldInfo, enabled: 1});
    }
    else {
      updatedSettings.push({...fieldInfo, enabled: 0});
    }

    setFieldSettings(updatedSettings);
    applyFieldSettings(updatedSettings, customFields);

    appDebug(AppConfig.debugCategoryDefault, 'updated field settings', updatedSettings);

  }

  return (
    <>
      <h2 className="mmu-settings-heading">Settings</h2>
      
        {(window.MMU?.can_use_premium_code) ?
          <>
            <div className="mmu-settings-section">
              <div className="mmu-settings-section-heading">Custom Fields</div>
              <div className="mmu-settings-section-description">Choose which fields to display in addition to the page title when browsing menu items</div>
              <div className="mmu-settings-form">
                <div className="mmu-settings-form-checkbox-group">
                  {customFields.map(
                    (fieldData, index) => {

                      if (typeof(fieldData.field_key) != 'undefined' && fieldData.field_key != null) {
                        const field_input_id = "checkbox-settings-fields-" + fieldData.field_key.toString()
                        
                        return (
                          <>
                            <div className="mmu-settings-option mmu-settings-option-custom-field">
                              <input id={field_input_id} type="checkbox" onChange={handleCheckboxChange} name={fieldData.field_key} checked={fieldData.enabled == true ? true : false} value="1" />
                              <label htmlFor={field_input_id}>
                                {fieldData.field_label}
                              </label>
                            </div>
                            
                          </>
                        )
                      }
                    }
                  )}
                </div>
                
              </div>
            </div>
            <StyledButton onClick={saveSettings} name="save">Save</StyledButton>
        </>
      : 
        <div className="mmu-settings-section">
          <div className="notice notice-info">
            <p>
              <a href={window.MMU.upgrade_url}>Upgrade to Pro today</a> to see custom fields in your menu items!
            </p>
          </div>
        </div>
      }
    </>
  );
}

export default Settings;