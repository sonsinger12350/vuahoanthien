/**
 * This is just a wrapper around npm-debug because the syntax
 * is rather verbose when using it in the browser. We could also add to this
 * function to stream debug information to something like DataDog or New Relic.
 */
 import DebugModule from "debug";
 import { AppConfig } from "../config/AppConfig";

 export const debug = (...args) => {
 
  if (!AppConfig.debugNamespace) {
    /* Don't bother doing anything if DEBUG is disabled */
    return; 
  }

  /* Re-use debuggers for the same namespace */
  const debuggers = {};   
  
  let this_debugger = null;

  if (args.length < 2) {
    console.log("app_debug requires a namespace and then the debug parameters. Too few parameters passed.");
    return;
  }

  const category = args.shift();
  
  if (typeof(debuggers[category]) != 'undefined') {
    this_debugger = debuggers[category];
  }
  else {
    /* 
      * Set a global app name in DebugModule. Our namespace will become APP_NAME:namespace 
      * This is important especially since other node modules might use the debug module,
      * and if so, not having a namespace means you could see a lot of debug information coming
      * from everywhere.
    */
    this_debugger = DebugModule(AppConfig.appName + ':' + category);
    debuggers[category] = this_debugger;

    DebugModule.enable(AppConfig.debugNamespace); /* Necessary because the debug module doesn't pick this up in the browser automatically */
  }

   this_debugger(...args);
 
 }
 
 export const appDebug = (...args) => {

  return debug(AppConfig.debugCategoryDefault, ...args);

 }
 
 export default appDebug;