import React, { useState, useContext, useRef, useEffect } from 'react';
import apiFetch from '@wordpress/api-fetch';
import PostService from '../services/PostService';
import ReactPaginate from 'react-paginate';
import { StoreContext } from '../utils/store';
import appDebug from "../utils/appDebug";
import { AppConfig } from "../config/AppConfig";

const PostSearchInterface = (props) => {

  const { item, postSelectAction } = props;

  const [selectedSearchPostType, setSelectedSearchPostType] = useState(null);
  const [searchResults, setSearchResults] = useState([]);
  const [searchValue, setSearchValue] = useState("");
  const [pageCount, setPageCount] = useState(0);
  const [totalResultCount, setTotalResultCount] = useState(0);
  const searchInputElement = useRef(null);
  const store = useContext(StoreContext);
  const itemsPerPage = 20;
  
  useEffect(
    () => {
      if (store.postTypes.length <= 0) {
        postTypesLoad();
      }
    }, []
  );

  const handlePostSearch = async () => {

    appDebug(AppConfig.debugCategoryDefault, 'search value', searchInputElement.current.value);
    setSearchValue(searchInputElement.current.value);

    const returnedData = await PostService.fetchResults(searchInputElement.current.value);

    appDebug(AppConfig.debugCategoryDefault, 'search returnedData', returnedData);

    updateSearchResults(returnedData.results, returnedData.total_results);

  }

  const updateSearchResults = (results, totalResultCount) => {
    
    setSearchResults(results);

    const pageCount = (totalResultCount) ? Math.ceil(totalResultCount / itemsPerPage): 0;
    setPageCount(pageCount);
    setTotalResultCount(totalResultCount);
  }

  const handlePageClick = async (e) => {

    const newOffset = (e.selected * itemsPerPage) % totalResultCount;
    appDebug(AppConfig.debugCategoryDefault, 'new page offset', newOffset);

    const returnedData = await PostService.fetchResults(searchInputElement.current.value, newOffset);

    appDebug(AppConfig.debugCategoryDefault, 'search returnedData', returnedData);

    updateSearchResults(returnedData.results, returnedData.total_results);    
  }

  const postTypesLoad = () => { 

    /* @TODO this call should be abstracted and the base URL should reference a constant */
    apiFetch( { path: `/mm_ultra/v1/posts/types` } ).then(
      (types) => {
        store.setPostTypes(types);
      }
    )
  }


  return (

    <div className="mmu-menu-item-post-search-container">
      <div className="mmu-menu-item-post-search">
        <div className="mmu-menu-item-post-search-interface">
          <div className="mmu-menu-item-post-search-type-container">
            <select className="mmu-menu-item-post-search-interface-element mmu-menu-item-post-search-type" value={selectedSearchPostType} onChange={(e) => { setSelectedSearchPostType(e.target.value)} }>
              <option value="">Search all Post Types</option>    
              {store.postTypes.map(
                (type, index) => {
                  return (
                    <option key={type.name} value={type.name}>{type.labels.singular_name}</option>
                  );
                }
              )}
            </select>
          </div>
          <div className="mmu-menu-item-post-search-keyword-container">
            <input ref={searchInputElement} type="text" className="mmu-menu-item-post-search-interface-element mm-ultra-search-input mm-ultra-search-input--posts" name="search_posts" />
            <button onClick={() => handlePostSearch()} className="mmu-menu-item-post-search-interface-element mmu-button mmu-button--search">Search</button>
          </div>  
        </div>
        <div className="mmu-menu-item-editor-help-text">Use the search to find content. Click a result to apply it to this menu item.</div>
        {window.MMU.can_use_premium_code ? "" :
          <div className="notice notice-info">
            <p>
              <a href={window.MMU.upgrade_url}>Upgrade to Pro today</a> to search custom page & post types
            </p>
          </div>            
        }        
        <div className="mmu-menu-item-post-search-results">
          <div className="mmu-menu-item-post-search-results-list">
            {searchResults.length > 0 ? 
            <table className="mm-ultra-search-results mm-ultra-search-results--post">
              <tr>
                <th className="mmu-table-col--ID" scope="col">ID</th>
                <th scope="col">Title</th>
              </tr>
              {searchResults.map(
                (post, index) => {
                  return (
                    <tr key={post.ID} onClick={(e) => postSelectAction(searchResults, post.ID)}>
                      <td className="mmu-table-col--ID">{post.ID}</td>
                      <td>
                        <div className="mmu-post-results-field-value mmu-post-results-field-value--title">{post.post_title}</div>
                        <div className="mmu-post-results-field-value mmu-post-results-field-value--url">{post.display_link}</div>
                      </td>
                    </tr>
                  )
                }
              )}
            </table>
            : ""
            }
          </div>
          <ReactPaginate
            breakLabel="..."
            nextLabel="Next >"
            onPageChange={handlePageClick}
            pageCount={pageCount}
            previousLabel="< Previous"
            renderOnZeroPageCount={null}
            className="mmu-pager"
            disabledClassName="mmu-pager-action--disabled"
          />          
        </div>
      </div>
    </div>
           
  );
} 

export default PostSearchInterface;