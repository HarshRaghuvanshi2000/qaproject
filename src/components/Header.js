import React, { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom'; // Ensure correct import
import '../styles/Header.css';
import profile_logo from '../assets/images/profile_logo.png';
import { FaChevronDown, FaSignOutAlt } from 'react-icons/fa';

const Header = () => {
  const navigate = useNavigate();
  const [dropdownOpen, setDropdownOpen] = useState(false);
  const [currentTime, setCurrentTime] = useState(new Date());

  useEffect(() => {
    const timer = setInterval(() => {
      setCurrentTime(new Date());
    }, 1000);

    return () => clearInterval(timer);
  }, []);

  const handleLogout = () => {
    // Clear token from local storage
    localStorage.removeItem('token');

    // Redirect to login page
    navigate('/login');
  };

  const toggleDropdown = () => {
    setDropdownOpen(!dropdownOpen);
  };

  const formatDate = (date) => {
    const options = { weekday: 'long', day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit', second: '2-digit' };
    return date.toLocaleDateString('en-US', options);
  };

  return (
    <header className="header">
      <div className="header-content">
        <h5 className="header-title">Quality Assurance Management System, Dial 112, Haryana</h5>
        <div className="user-info">
          <span className="current-time">{formatDate(currentTime)}</span>
          <img src={profile_logo} alt="Profile" className="profile-pic" />
          <div className="user-details">
            <span className="user-name">Anjali</span>
            <span className="user-designation">SCO</span>
          </div>
          <div className="dropdown">
            <button onClick={toggleDropdown} className="dropdown-toggle">
              <FaChevronDown />
            </button>
            {dropdownOpen && (
              <div className="dropdown-menu">
                <button onClick={handleLogout} className="dropdown-item">
                  <FaSignOutAlt className="dropdown-icon" />
                  Log Out
                </button>
              </div>
            )}
          </div>
        </div>
      </div>
    </header>
  );
};

export default Header;
