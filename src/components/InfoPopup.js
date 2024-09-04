import React, { useRef } from 'react';
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
          <p><strong>Event Registration Time:</strong> {logDetails.event_registration_time}</p>
          <p><strong>Event Subtype:</strong> {logDetails.event_subtype}</p>
          <p><strong>Priority:</strong> {logDetails.priority}</p>
          <p><strong>Additional Info</strong> {logDetails.addl_info}</p>
          <p><strong>Victim Address:</strong> {logDetails.victim_address}</p>
          <p><strong>Victim Age:</strong> {logDetails.victim_age}</p>
          <p><strong>Victim Gender:</strong> {logDetails.victim_gender}</p>
          <p><strong>Victim Name:</strong> {logDetails.victim_name}</p>
          <p><strong>Signal Landing Time:</strong> {logDetails.signal_landing_time}</p>
          <p><strong>Near PS:</strong> {logDetails.near_ps}</p>
          <p><strong>Call Duration:</strong> {logDetails.call_duration_millis / 1000} seconds</p>
          <p><strong>Call Pick Duration:</strong> {logDetails.call_pick_duration_millis / 1000} seconds</p>
          <p><strong>District Code:</strong> {logDetails.district_code}</p>
        </div>
      </div>
    </Draggable>
  ) : null;
};

export default InfoPopup;
