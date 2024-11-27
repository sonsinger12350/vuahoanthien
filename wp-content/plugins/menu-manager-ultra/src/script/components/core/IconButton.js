//import { StoreContext } from '../utils/store';
import { React, useState, useEffect } from 'react';
import PropTypes from 'prop-types';

const IconButton = (props) => {

  const { name, children, icon, disabled, border = true, background = true, clickHandler, ...rest } = props;
  const [classes, setClasses] = useState([]);
  const iconKey = icon ?? name
  
  useEffect(
    () => {
      const classSafeName = name ? name.replace(/[^A-Za-z0-9-]/, '-') : 'default';
      const classNamesList = [
        'mmu-button', 
        'mmu-button--with-icon',
        'mmu-button--with-icon--' + classSafeName,
      ];

      if (disabled) {
        classNamesList.push('mmu-button--disabled mmu-button--with-icon--disabled');
      }

      if (!border) {
        classNamesList.push('mmu-button--without-border');
      }

      if (!background) {
        classNamesList.push('mmu-button--without-background');
      }

      setClasses(classNamesList);
    }
    , [name, icon, disabled]
  );

  const handleClick = (e) => {

    if (!disabled) {
      clickHandler(e);
    }
  }

  return (
    <button className={classes.join(' ')} onClick={(e) => handleClick(e)} {...rest}>
      <i className={"dashicons dashicons-" + iconKey}></i>
      <i className="mmu-button-text">{children}</i>
    </button>
  );
}

IconButton.propTypes = {
  name: PropTypes.string,
  children: PropTypes.node,
  icon: PropTypes.string,
  disabled: PropTypes.bool,
  border: PropTypes.bool,
  background: PropTypes.bool,
  clickHandler: PropTypes.func
  
};


export default IconButton;