import App from "./App";
import { React } from "react";
import { render } from '@wordpress/element';
import StoreProvider from './utils/store'

/**
 * Import the stylesheet for the plugin.
 */
import '../style/main.scss';

if (document.getElementById('menu-manager-ultra')) {
  // Render the App component into the DOM
  render(
    <StoreProvider>
      <App />
    </StoreProvider>, 
    document.getElementById('menu-manager-ultra')
  );
}