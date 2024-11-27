//import { StoreContext } from '../utils/store';
import { React } from 'react';
import PropTypes from 'prop-types';

const StyledButton = (props) => {

  const { name, children, ...rest } = props;

  return (
    <button className={"mmu-button " + ((name) ? "mmu-button--" + name.replace(/[^A-Za-z0-9-]/, '-') : "")} {...rest}>{children}</button>
  );
}

StyledButton.propTypes = {
  name: PropTypes.string,
  children: PropTypes.node
};

export default StyledButton;