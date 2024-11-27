import apiFetch from '@wordpress/api-fetch';
import { ServiceErrorHandler } from './ServiceErrorHandler';
import { AppConfig } from '../config/AppConfig';
import appDebug from "../utils/appDebug";

const DEBUG_CATEGORY = "MenuItemService";

export const MenuItemService = {

  fetchTopLevelItems: async(menuID) => {

    if (menuID) {
      return apiFetch( { path: `${AppConfig.endpointBaseUrl}/menu/${menuID}/items` } ).then( 
        ( menuItems ) => {
          appDebug(DEBUG_CATEGORY, 'returned top level menu items', menuItems);
          return menuItems;
        
        }
      ).catch( 
        (error) => {
          console.warn(error);
          return error;
        }
      );
    }
    
    return Promise.resolve(null);
  
  },

  fetchChildItems: async (menu_id, item_id) => {
    
    appDebug(DEBUG_CATEGORY, 'fetching menu subitems', menu_id, item_id);
    
    if (menu_id) {
      return apiFetch( { path: `${AppConfig.endpointBaseUrl}/menu/${menu_id}/item/${item_id}/children` } );
    }

    return Promise.resolve(null);
  
  },
  saveItems: async (menuID, menuItems) =>  {

    if (!menuID) {
      throw new Error("Missing MenuID in SaveItems");
    }
    
    const result = await apiFetch( 
      { 
        path: `${AppConfig.endpointBaseUrl}/menu/update`,
        method: 'POST', 
        data: { 
          'items': menuItems,
          'menu_id': menuID
        }
      } 
    ).catch((err) => {
      ServiceErrorHandler.handleError(err, { debug_category: DEBUG_CATEGORY });
    });

    ServiceErrorHandler.assertSuccess(result);

    return result;

  },
  refreshItems: async(menuID, menuItems, overrideFields = []) => {

    if (!menuID) {
      throw new Error("Missing menuID in refreshItems");
    }

    appDebug(DEBUG_CATEGORY, 'refreshed menu items', menuItems);

    const result = await apiFetch( 
      { 
        path: `${AppConfig.endpointBaseUrl}/menu/${menuID}/items/refresh`,
        method: 'POST', 
        data: { 'items': menuItems,
                'override_fields': overrideFields 
              } 
      } 
    ).catch((err) => {
      ServiceErrorHandler.handleError(err, { debug_category: DEBUG_CATEGORY });
    });

    ServiceErrorHandler.assertSuccess(result, {assertFieldValues: ['items']});

    return result.items;    
  }

}