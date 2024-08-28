// src/components/Sidebar.js

import React, { useState } from 'react';
import { NavLink, useLocation } from 'react-router-dom';
import { FaBars, FaPhoneAlt, FaCog, FaFileAlt } from 'react-icons/fa';
import logo from '../assets/images/logo_hry112.png';
import '../styles/Sidebar.css';

const Sidebar = () => {
    const [isOpen, setIsOpen] = useState(true); // Default to true to show the sidebar
    const location = useLocation();
    
    // Retrieve the username from localStorage
    const username = localStorage.getItem('username');

    // Function to handle active link for both "/dashboard" and "/call-logs"
    const getActiveClass = (paths) => {
        return paths.some((path) => location.pathname.startsWith(path)) ? 'active-link' : '';
    };

    const toggleSidebar = () => {
        setIsOpen(!isOpen);
    };

    return (
        <>
            <button className="sidebar-toggle" onClick={toggleSidebar}>
                <FaBars />
            </button>
            <aside className={`sidebar ${isOpen ? 'open' : ''}`}>
                <div className="sidebar-content">
                    <img src={logo} alt="Logo" className="logo" />
                    <div className="sidebar-heading">
                        <p className="heading">Quality Assurance</p>
                    </div>
                    <nav>
                        <ul>
                            <li>
                                <NavLink
                                    to="/dashboard"
                                    className={getActiveClass(['/dashboard', '/call-logs'])}
                                >
                                    <FaPhoneAlt className="icon" /> Dashboard
                                </NavLink>
                            </li>
                            {/* Conditionally render links based on the username */}
                            {username !== 'mor_2314' && (
                                <>
                                    <li>
                                        <NavLink
                                            to="/performance-reports"
                                            className={getActiveClass(['/performance-reports', '/detailed-report'])}
                                        >
                                            <FaFileAlt className="icon" /> Performance Reports
                                        </NavLink>
                                    </li>
                                    <li>
                                        <NavLink
                                            to="/admin-settings"
                                            className={getActiveClass(['/admin-settings'])}
                                        >
                                            <FaCog className="icon" /> Admin Settings
                                        </NavLink>
                                    </li>
                                </>
                            )}
                        </ul>
                    </nav>
                </div>
                
                {/* Footer section */}
                <div className="sidebar-footer">
                    <p className="powered-by">
                        Powered By
                        <a href="https://www.cdac.in" target="_blank" rel="noopener noreferrer" className="cdac-link"> C-DAC</a>
                    </p>
                </div>
            </aside>
        </>
    );
};

export default Sidebar;
