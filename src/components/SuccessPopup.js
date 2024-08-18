import React from 'react';
import '../styles/SuccessPopup.css';

const SuccessPopup = ({ message, isOpen, onClose }) => {
  if (!isOpen) return null;

  return (
    <div className="popup-overlay">
      <div className="popup-container">
        <div className="popup-header">
          <span className="popup-close" onClick={onClose}>&times;</span>
        </div>
        <div className="popup-content">
          <div className="popup-icon">&#10003;</div>
          <h2>Success!</h2>
          <p>{message}</p>
          <button className="popup-button" onClick={onClose}>Awesome!</button>
        </div>
      </div>
    </div>
  );
};

export default SuccessPopup;
