import apiFetch from '@wordpress/api-fetch';
import appDebug from "../utils/appDebug";

/* Importing regenerator-runtima allows us to use async/await */
import regeneratorRuntime from "regenerator-runtime"; // eslint-disable-line no-unused-vars
import { ServiceErrorHandler } from './ServiceErrorHandler';

const DEBUG_CATEGORY = 'PostService';

const endpoint_base_url_default = "/mm_ultra/v1"

const PostService = {

  handleError: (err) => {
      
    appDebug(DEBUG_CATEGORY, "Error fetching from endpoint");

    if (err.response) {
      appDebug(DEBUG_CATEGORY, "Data", err.response.data);
      appDebug(DEBUG_CATEGORY, "Status", err.response.status);
      appDebug(DEBUG_CATEGORY, "Headers", err.response.headers);
    }
    else if (err.request) {
      appDebug(DEBUG_CATEGORY, "Request", err.request);
    }
    else {
      appDebug(DEBUG_CATEGORY, "Unkown error", err.message);
    }

    throw new Error("Error fetching Post. Check the log for more details");
  },

  /**
   * This is where the actual work is done for getting results.
   * 
   * @param {string} given_string 
   * @param {base url of API endpoint} endpoint_base_url 
   * @returns {Array} results matching given_string
   */
  fetchResults: async(given_string, offset = 0) => {

    const response = await apiFetch(
    { 
      path: `${endpoint_base_url_default}/posts/search`,
      method: 'POST',
      data: { 
        search_key: given_string, 
        offset: offset 
      }
    } )
    .catch((err) => {
      PostService.handleError(err);
    });

    appDebug(DEBUG_CATEGORY, 'response', response);

    if (
      typeof(response.results) != 'undefined' 
      && Array.isArray(response.results)) {
      
          return response;
    }
    else {
      appDebug(DEBUG_CATEGORY, "Invalid results returned from API", response)
      throw new Error("Invalid response data from API");
    }
  },

  /**
   * Fetch a single post
   * 
   * @param {id} post_id 
   * @returns {Object} returned post, or null
   */
  fetchPost: async(id, endpoint_base_url) => {

    if (!endpoint_base_url) {
      endpoint_base_url = endpoint_base_url_default;
    }

    appDebug(DEBUG_CATEGORY, 'endpoint is', `${endpoint_base_url}/post/${id}`);

    const response = await apiFetch(
      { 
        path: `${endpoint_base_url}/post/${id}/get`,
        method: 'GET'
      } )
    .catch((err) => {

      ServiceErrorHandler.handleError(err);
      
    });

    appDebug(DEBUG_CATEGORY, 'response', response);

    if ( typeof(response.post) != 'undefined') {
      return response.post;
    }
    else {
      appDebug(DEBUG_CATEGORY, "Invalid results returned from API", response)
      throw new Error("Invalid response data from API");
    }
  } 
}

export default PostService;                            