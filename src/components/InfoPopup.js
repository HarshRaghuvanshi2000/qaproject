import React, { useRef } from 'react';
import Draggable from 'react-draggable';
import '../styles/InfoPopup.css';

const InfoPopup = ({ isOpen, onClose, logDetails }) => {
  const popupRef = useRef(null);

  // Helper function to format milliseconds to "MM Min SS Sec"
  const formatMillisToMinSec = (millis) => {
    if (millis == null) return (
      <span style={{
          color: '#495057', // Dark gray
          fontStyle: 'italic', 
          backgroundColor: '#f8f9fa', // Light background
          padding: '2px 4px', // Padding for better visibility
      }}>
          Not Selected
      </span>
  ); // Handle null or undefined
    const minutes = Math.floor(millis / 60000);
    const seconds = Math.floor((millis % 60000) / 1000);
    return `${String(minutes).padStart(2, '0')} Min ${String(seconds).padStart(2, '0')} Sec`;
  };

  // Helper function to format timestamp in milliseconds to a date-time string
  const formatMillisToDateTime = (millis) => {
    if (millis == null || isNaN(millis)) return (
      <span style={{
          color: '#495057', // Dark gray
          fontStyle: 'italic', 
          backgroundColor: '#f8f9fa', // Light background
          padding: '2px 4px', // Padding for better visibility
      }}>
          Not Selected
      </span>
  ); // Handle null, undefined, or invalid numbers
  
    const date = new Date(parseInt(millis, 10)); // Convert milliseconds to Date object
    if (isNaN(date.getTime())) return 'Invalid Date'; // Check if date is valid
  
    // Extract date components
    const day = String(date.getDate()).padStart(2, '0'); // Days are 1-based
    const month = String(date.getMonth() + 1).padStart(2, '0'); // Months are 0-based
    const year = date.getFullYear();
    const hours = String(date.getHours()).padStart(2, '0');
    const minutes = String(date.getMinutes()).padStart(2, '0');
    const seconds = String(date.getSeconds()).padStart(2, '0');
  
    // Return formatted string in DD-MM-YYYY HH:MM:SS format
    return `${day}-${month}-${year} ${hours}:${minutes}:${seconds}`;
  };
  // Helper function to handle null or undefined values
  const formatValue = (value) => {
    return value != null && value !== '' 
        ? value 
        : (
            <span style={{
                color: '#495057', // Dark gray
                fontStyle: 'italic', 
                backgroundColor: '#f8f9fa', // Light background
                padding: '2px 4px', // Padding for better visibility
            }}>
                Not Selected
            </span>
        );
};

  return isOpen ? (
    <Draggable nodeRef={popupRef} handle=".popup-header">
      <div ref={popupRef} className="info-popup">
        <div className="popup-header">
          <h3>Call Log Details</h3>
          <button onClick={onClose} className="close-btn">X</button>
        </div>
        <div className="popup-content">
          <p><strong>Event Type:</strong> {formatValue(logDetails.event_maintype)}</p>
          <p><strong>Event Registration Time:</strong> {formatMillisToDateTime(logDetails.event_registration_time)}</p>
          <p><strong>Event Subtype:</strong> {formatValue(logDetails.event_subtype)}</p>
          <p>
            <strong>Priority:</strong>
            <span className={`priority ${logDetails.priority ? logDetails.priority.toLowerCase() : ''}`}>
              {formatValue(logDetails.priority)}
            </span>
          </p>
          <p><strong>Additional Info:</strong> {formatValue(logDetails.addl_info)}</p>
          <p><strong>Victim Address:</strong> {formatValue(logDetails.victim_address)}</p>
          <p><strong>Victim Age:</strong> {formatValue(logDetails.victim_age)}</p>
          <p><strong>Victim Gender:</strong> {formatValue(logDetails.victim_gender)}</p>
          <p><strong>Victim Name:</strong> {formatValue(logDetails.victim_name)}</p>
          <p><strong>Signal Landing Time:</strong> {formatMillisToDateTime(logDetails.signal_landing_time)}</p>
          <p><strong>Near PS:</strong> {formatValue(logDetails.near_ps)}</p>
          <p><strong>Call Duration:</strong> {formatMillisToMinSec(logDetails.call_duration_millis)}</p>
          <p><strong>Call Pick Duration:</strong> {formatMillisToMinSec(logDetails.call_pick_duration_millis)}</p>
          <p><strong>District Code:</strong> {formatValue(logDetails.district_code)}</p>
        </div>
      </div>
    </Draggable>
  ) : null;
};

export default InfoPopup;
