import React, { useState, useRef } from 'react';
import Draggable from 'react-draggable';
import '../styles/InfoPopup.css';

const InfoPopup = ({ isOpen, onClose, logDetails }) => {
  const popupRef = useRef(null);


  return isOpen ? (
    <Draggable nodeRef={popupRef} handle=".popup-header">
      <div ref={popupRef} className="info-popup">
        <div className="popup-header">
          <h3>Call Log Details</h3>
          <button onClick={onClose} className="close-btn">X</button>
        </div>
        <div className="popup-content">
          <p><strong>Event Type:</strong> {logDetails.event_maintype}</p>
          <p><strong>Event Subtype:</strong> {logDetails.event_subtype}</p>
          <p><strong>Call Duration:</strong> {logDetails.call_duration_millis / 5000} seconds</p>
          <p><strong>Review Status:</strong> {logDetails.review_status}</p>
        </div>
      </div>
    </Draggable>
  ) : null;
};

export default InfoPopup;
