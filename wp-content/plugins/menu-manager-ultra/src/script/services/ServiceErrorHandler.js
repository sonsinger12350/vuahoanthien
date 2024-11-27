import appDebug from "../utils/appDebug";

const SERVICE_MESSAGE_BASE = "Error fetching data from backend";

export const ServiceErrorHandler = {

  handleError: (err, options = {}) => {
    
    let { 
      debug_category = "API", 
      messages = [],
      message_base = SERVICE_MESSAGE_BASE 
    } = options;
    
    if (!Array.isArray(messages)) {
      messages = [messages];
    }

    if (err.response) {
      appDebug(debug_category, "Data", err.response.data);
      appDebug(debug_category, "Status", err.response.status);
      appDebug(debug_category, "Headers", err.response.headers);
    }
    
    if (err.request) {
      appDebug(debug_category, "Request", err.request);
    }
    
    if (err.message) {
      appDebug(debug_category, err.message);
    }

    const fullMessage = message_base + "\n" + messages.join("\n") + "\n";

    throw new Error(fullMessage, { originalError: err });
  },

  assertSuccess: (result, options = {}) => {

    const { assertFieldValues } = options;

    let valid = false;

    if (result && typeof(result.status) != 'undefined') {
      if (result.status == 'success') { /* TODO make this a constant */
        valid = true;
      }
    }

    if (!valid) {

      let message = SERVICE_MESSAGE_BASE;

      if (result) {
        if (typeof(result.message) != 'undefined') {
          if (result.message) {
            message = message + "\n" + result.message;
          }
        }

        if (typeof(result.messages) != 'undefined') {
          if (result.messages) {
            if (!Array.isArray(result.messages)) {
              message = message + "\n" + result.messages;
            }
            else {
              message = message + "\n" + result.messages.join("\n");
            }
          }
        }
      }

      throw new Error(message);
    }

    if (assertFieldValues != null) {

      let fieldKey;

      for(fieldKey in assertFieldValues) {
        if (typeof(result[assertFieldValues[fieldKey]]) == 'undefined') {
          throw new Error("Missing field key '" + assertFieldValues[fieldKey].toString() + "' in response");
        }
      }
    }

    return valid;
  }


}