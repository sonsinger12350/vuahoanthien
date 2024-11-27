import apiFetch from '@wordpress/api-fetch';
import { AppConfig } from "../config/AppConfig";

/* Importing regenerator-runtima allows us to use async/await */
import regeneratorRuntime from "regenerator-runtime"; // eslint-disable-line no-unused-vars
import { ServiceErrorHandler } from './ServiceErrorHandler';

const DEBUG_CATEGORY = "MenuService";

export const MenuService = {

  addMenu: async (menuName) =>  {

    const result = await apiFetch( 
      { 
        path: `${AppConfig.endpointBaseUrl}/menu/add`,
        method: 'POST', 
        data: { 'menu_name': menuName } 
      } 
    );
    
    if (!result || !result.status || result.status == 'error') {
      
      let message = 'Error adding menu item: ';

      if (result && result.message) {
        message += result.message.toString();
      }

      throw new Error(message);
    }
    
    return result.menu_id;
    
  },

  listMenus: async () =>  {

    const result = await apiFetch( 
      { 
        path: `${AppConfig.endpointBaseUrl}/menus/list`,
        method: 'GET' 
      } 
    ).catch((err) => {
      ServiceErrorHandler.handleError(err, { debug_category: DEBUG_CATEGORY });
    });

    ServiceErrorHandler.assertSuccess(result, { assertFieldValues: ['menus'] });

    return result.menus;

  }

}