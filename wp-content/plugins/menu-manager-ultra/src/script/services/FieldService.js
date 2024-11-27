import apiFetch from '@wordpress/api-fetch';
import appDebug from "../utils/appDebug";
import { AppConfig } from "../config/AppConfig";

/* Importing regenerator-runtima allows us to use async/await */
import regeneratorRuntime from "regenerator-runtime"; // eslint-disable-line no-unused-vars
import { ServiceErrorHandler } from './ServiceErrorHandler';

const DEBUG_CATEGORY = "FieldItemService";

const FieldService = {

  /**
   * Fetch custom fields list 
   * 
   */
  fetchDefinedCustomFields: async() => {

    const response = await apiFetch(
      { 
        path: `${AppConfig.endpointBaseUrl}/fields/custom/list`,
        method: 'GET'
      } )
    .catch((err) => {

      ServiceErrorHandler.handleError(err);
      
    });

    ServiceErrorHandler.assertSuccess(response, {assertFieldValues: ['fields']});

    return response.fields;
  },

  getFieldSettings: async() => {

    const response = await apiFetch(
      { 
        path: `${AppConfig.endpointBaseUrl}/fields/custom/settings`,
        method: 'GET'
      } )
    .catch((err) => {

      ServiceErrorHandler.handleError(err);
      
    });

    ServiceErrorHandler.assertSuccess(response, {assertFieldValues: ['fields']});

    return response.fields;
  },

  /**
   * Save custom field settings
   * 
   * @param {Array} fieldSettings 
   * @returns true on success
   */
  saveFieldSettings: async (fieldSettings) =>  {

    appDebug(AppConfig.debugCategoryDefault, 'saveFieldSettings', fieldSettings);

    const result = await apiFetch( 
      { 
        path: `${AppConfig.endpointBaseUrl}/fields/custom/settings/save`,
        method: 'POST', 
        data: { 'fields': fieldSettings } 
      } 
    ).catch((err) => {
      ServiceErrorHandler.handleError(err, { debug_category: DEBUG_CATEGORY });
    });

    ServiceErrorHandler.assertSuccess(result);

    return result;

  }

}

export default FieldService;                            