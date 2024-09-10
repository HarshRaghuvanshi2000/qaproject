import React, { useState, useEffect, useRef } from 'react';
import { useNavigate } from 'react-router-dom';
import '../styles/Header.css';
import profile_logo from '../assets/images/profile_logo.png';
import { FaChevronDown, FaSignOutAlt } from 'react-icons/fa';

const Header = () => {
  const navigate = useNavigate();
  const [dropdownOpen, setDropdownOpen] = useState(false);
  const [currentTime, setCurrentTime] = useState(new Date());
  const [userName, setUserName] = useState('');
  const [userDesignation, setUserDesignation] = useState('');
  const dropdownRef = useRef(null);

  useEffect(() => {
    const timer = setInterval(() => {
      setCurrentTime(new Date());
    }, 1000);

    return () => clearInterval(timer);
  }, []);

  useEffect(() => {
    const handleClickOutside = (event) => {
      if (dropdownRef.current && !dropdownRef.current.contains(event.target)) {
        setDropdownOpen(false);
      }
    };

    document.addEventListener('mousedown', handleClickOutside);
    return () => {
      document.removeEventListener('mousedown', handleClickOutside);
    };
  }, []);

  useEffect(() => {
    // Retrieve user data from localStorage
    const storedUserName = localStorage.getItem('fullName');
    const storedUserDesignation = localStorage.getItem('desgName');
    setUserName(storedUserName || 'User'); // Fallback if not found
    setUserDesignation(storedUserDesignation || 'Designation'); // Fallback if not found
  }, []);

  const handleLogout = () => {
    localStorage.removeItem('token');
    navigate('/login');
  };

  const toggleDropdown = () => {
    setDropdownOpen(!dropdownOpen);
  };

  const formatDate = (date) => {
    const options = {
      weekday: 'long',
      day: '2-digit',
      month: 'short',
      year: 'numeric',
      hour: '2-digit',
      minute: '2-digit',
      second: '2-digit',
    };
    return date.toLocaleDateString('en-US', options);
  };

  return (
    <header className="custom-header">
      <div className="custom-header-content">
        <h5 className="custom-header-title">
          Quality Assurance Management System, Dial 112, Haryana
        </h5>
        <div className="custom-user-info">
          <span className="custom-current-time">{formatDate(currentTime)}</span>
          <img src={profile_logo} alt="Profile" className="custom-profile-pic" />
          <div className="custom-user-details">
            <span className="custom-user-name">{userName}</span>
            <span className="custom-user-designation">{userDesignation}</span>
          </div>
          <div className="custom-dropdown" ref={dropdownRef}>
            <button onClick={toggleDropdown} className="custom-dropdown-toggle">
              <FaChevronDown />
            </button>
            {dropdownOpen && (
              <div className="custom-dropdown-menu">
                <button onClick={handleLogout} className="custom-dropdown-item">
                  <FaSignOutAlt className="custom-dropdown-icon" />
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
