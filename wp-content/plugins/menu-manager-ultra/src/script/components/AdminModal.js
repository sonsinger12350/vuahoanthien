import Modal from "react-modal";
import { useRef, useState, useEffect } from 'react';

const AdminModal = (props) => {

  Modal.setAppElement('#menu-manager-ultra');

  const [modalStyle, setModalStyle] = useState({});
  const { handleCancel, isOpen, inputRef, message, name, width = "600px", height = "600px", children, ...rest } = props;
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
        'max-height': height, 
        'max-width': width, 
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
      id={"modal" + name}
      isOpen={isOpen}
      contentLabel={"modal" + name}
      shouldCloseOnOverlayClick={true}
      shouldCloseOnEsc={true}
      onRequestClose={handleCancel}
      style={modalStyle}
      {...rest}
    >
      {children}
    </Modal>
  );
}

export default AdminModal;