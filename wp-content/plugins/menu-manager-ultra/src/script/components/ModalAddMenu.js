import Modal from "react-modal";
import { React, useRef, useState, useEffect } from 'react';
import StyledButton from "./core/StyledButton";

const ModalAddMenu = (props) => {

  Modal.setAppElement('#menu-manager-ultra');

  const [modalStyle, setModalStyle] = useState({});
  const { handleSave, handleCancel, isOpen, inputRef, message } = props;
  const wpContentElement = useRef(document.getElementById('wpcontent'));

  /* TODO make this a custom callback so we don't have to repeat it */
  useEffect(
    () => {
      setModalStyle(computeModalStyle());
    }, []
  );  	

  const computeModalStyle = () => {

    const computedStyle = getComputedStyle(wpContentElement.current);

    return {
      'content': {
        'margin-left': 'auto',
        'margin-right': 'auto', 
        'max-height': '200px', 
        'max-width': '400px', 
        'top': 'calc(50% - 250px)'
      },
      'overlay': {
        'padding-left': computedStyle.paddingLeft,
        'margin-left': computedStyle.marginLeft,
        'background-color': 'rgba(0, 0, 0, 0.8)'
      }
    };
  }

  return (
    <Modal
      id="modal_menu_add"
      isOpen={isOpen}
      contentLabel="modalMenuAdd"
      shouldCloseOnOverlayClick={true}
      shouldCloseOnEsc={true}
      onRequestClose={handleCancel}
      style={modalStyle}
    >
      {message ? 
        <div class="mmu-modal-message">
          {message}
        </div>
        : 
        ""
      }
      <div className="mmu-modal-input-container">
        <label htmlFor="input_new_menu_name">Menu Name</label>
        <input ref={inputRef} type="text" defaultValue="" id="input_new_menu_name" name="new_menu_name" />
      </div>
      <div className="mmu-modal-actions">
        <StyledButton onClick={(e) => handleSave(e)}>Save</StyledButton>
        <a onClick={(e) => handleCancel(e)}>Cancel</a>
      </div>
    </Modal>
  );
}

export default ModalAddMenu;