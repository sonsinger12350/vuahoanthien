import Modal from "react-modal";
import { React, useRef, useState, useEffect } from 'react';
import StyledButton from "./core/StyledButton";
import PropTypes from 'prop-types';

const ModalDeleteConfirm = (props) => {

  Modal.setAppElement('#menu-manager-ultra');

  const [modalStyle, setModalStyle] = useState({});
  const { handleConfirm, handleCancel, isOpen } = props;
  const wpContentElement = useRef(document.getElementById('wpcontent'));

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
        'max-height': '250px', 
        'max-width': '500px', 
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
      id="modal_menu_item_delete_confirm"
      isOpen={isOpen}
      contentLabel="modalB"
      shouldCloseOnOverlayClick={true}
      shouldCloseOnEsc={true}
      onRequestClose={handleCancel}
      style={modalStyle}
    >
      <div className="mmu-modal-message">
        This item has items beneath it.
        { window.MMU?.can_use_premium_code ?
        <div>
          How would you like to treat those items when deleting?
        </div> 
        : 
        ""
        }
      </div>
      <div className="mmu-modal-actions">
        <StyledButton onClick={(e) => handleConfirm(e, 1)}>Delete all sub-items as well</StyledButton>
        { window.MMU?.can_use_premium_code ?
          <StyledButton onClick={(e) => handleConfirm(e, 0)}>Move sub-items one level up</StyledButton>
        :
        <div className="notice notice-info notice-pro-upgrade">
          <p>
            The <a href={window.MMU.upgrade_url}>Pro version</a> has a feature for moving children of deleted items up one level. 
          </p>
        </div>
        }
      </div>
    </Modal>
  );
}

ModalDeleteConfirm.propTypes = {
  handleConfirm: PropTypes.func,
  handleCancel: PropTypes.func,
  isOpen: PropTypes.bool
};

export default ModalDeleteConfirm;