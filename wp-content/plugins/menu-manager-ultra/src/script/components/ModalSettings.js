import Settings from "./Settings";
import AdminModal from "./AdminModal";
import React from 'react';
import PropTypes from 'prop-types';

const ModalSettings = (props) => {

  const { setShowSettings, handleCancel, isOpen } = props;

  return (
    <AdminModal
      name="settings"
      isOpen={isOpen}
      onRequestClose={handleCancel}
    >
      <Settings setShowSettings={setShowSettings} />
    </AdminModal>
  );
}

ModalSettings.propTypes = {
  setShowSettings: PropTypes.func,
  handleCancel: PropTypes.func,
  isOpen: PropTypes.bool
};

export default ModalSettings;