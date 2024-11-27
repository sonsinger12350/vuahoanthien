import React, { useState, useMemo } from 'react';
export const StoreContext = React.createContext(null);

const DEBUG_CATEGORY = 'Store';

const Store = ({ children }) => {

  const initialState = {
    menuItems: [],
    menuID: null,
    addingItemToID: null,
    changedItems: [],
    isAnyItemChanged: false,
    postTypes: [],
    activeCustomFields: [],
    nestedDragID: null,
    isSaving: false
  };

  const [currentState, setCurrentState] = useState(initialState);


  const storeFuncs = useMemo( () => {

    return {
      getValue(prop) {
        return currentState[prop];
      },
      setValue(prop, value) {
        let stateChange = {}
        stateChange[prop] = value;

        return setCurrentState(
          currentState => ({...currentState, ...stateChange})
        );

      },
      setMenuItems: (items, options = {}) => {
        
        const { skipCheckForChanged = false } = options;
        storeFuncs.setValue('menuItems', items);

        if ( !skipCheckForChanged ) {
          checkForChangedItems();
        }
      },
      setMenuID: (menuID) => {
        storeFuncs.setValue('menuID', menuID);
        storeFuncs.clearChangedItems();
      },
      menuItemChangeTypes: {
        'any': 'any',
        'delete': 'delete', 
        'edit': 'edit',
        'position': 'position',
        'children': 'children'
      },

      markChangedItem: (item, changeType) => {

        let updatedItems = storeFuncs.getValue('changedItems');

        if (updatedItems.findIndex(changedItem => changedItem.ID == item.ID && changedItem.changeType == changeType) <= -1) {
          updatedItems.push({ID: item.ID, changeType: changeType})
          storeFuncs.setValue('changedItems', updatedItems);
          checkForChangedItems();
        }

        return updatedItems;
      },
      unMarkChangedItem: (item, changeType) => {

        let updatedItems = storeFuncs.getValue('changedItems');
        
        const index = updatedItems.findIndex(changedItem => changedItem.ID == item.ID && changedItem.changeType == changeType);
        
        if (index > -1) {
          updatedItems.splice(index, 1);
          storeFuncs.setValue('changedItems', updatedItems);
          checkForChangedItems();
        }

        return updatedItems;

      },
      clearChangedItems() {
        storeFuncs.setValue('changedItems', []);
        storeFuncs.setValue('isAnyItemChanged', false);
      },
      reset: () => {
        storeFuncs.clearChangedItems();
      }

    }
  });

  const storeProxyHelper = useMemo( () => {
    return {
      getTargetFunctionResult(target, prop, receiver) {

        if (typeof(target[prop]) != 'function') {
          throw new Error('Function doesn\'t exist on target object');
        }

        return new Proxy(
          target[prop], 
          {
            apply: (obj, thisArg, argumentsList) => {
              return Reflect.apply(obj, thisArg, argumentsList);
            }
          }
          
        )
      },
      shouldTriggerSetter(prop) {
        return prop.substr(0, 3) == 'set';
      },
      magicSetter(target, prop, receiver) {
        const propName = prop.substr(3, 1).toLowerCase() + prop.substring(4);
        if (typeof(target[prop]) == 'undefined') {
              
          if (propName in initialState) {
            target[prop] = new Proxy(new Function(), {
              apply: (obj, thisArg, argumentsList) => {
                thisArg[propName] = argumentsList[0];
              }
            }); 

            return target[prop];
          }
        }     
      }
    }
  });

  const storeHandler = useMemo( () => {
    return {
      get(target, prop, receiver) {

        if (typeof(target[prop]) == 'function') {
          return storeProxyHelper.getTargetFunctionResult(target, prop, receiver);
        }

        if (prop in initialState) {
          return storeFuncs.getValue(prop);
        }

        if (storeProxyHelper.shouldTriggerSetter(prop)) {
          return storeProxyHelper.magicSetter(target, prop, receiver);
        }
        
        return Reflect.get(target, prop, receiver);
              
      },
      set(obj, prop, value) {
        if (prop in initialState) {
          storeFuncs.setValue(prop, value);
          return true;
        }
        else {
          throw new Error("trying to set invalid state property: " + prop.toString());
        }
      },
      has(target, prop) {
        return prop in initialState ? true : Reflect.has(target, prop);
      }
    }
  });

  //const storeProxy = useMemo( () => { return new Proxy(storeFuncs, storeHandler) }, []);
  const store = useMemo( () => { return new Proxy(storeFuncs, storeHandler) }, [currentState] );
  //const store = { ...storeFuncs, ...storeProxy }

  const checkForChangedItems = () => {
    const wasChanged = (storeFuncs.getValue('changedItems').length > 0) ? true : false;
    storeFuncs.setValue('isAnyItemChanged', wasChanged);
  }

  return <StoreContext.Provider value={store}>{children}</StoreContext.Provider>
}

export default Store;