import React from 'react';
import { NavLink } from 'react-router-dom';
import logo from '../assets/images/logo_hry112.png';
import { FaPhoneAlt, FaCog, FaFileAlt } from 'react-icons/fa';
import '../styles/Sidebar.css';
const Sidebar = () => {
    return (
        <aside className="sidebar">
            <div className="sidebar-content">
                <img src={logo} alt="Logo" className="logo" />
                <div className="sidebar-heading">
                    <p className="heading">Quality Assurance</p>
                </div>
                <nav>
                    <ul>
                        <li>
                            <NavLink exact to="/dashboard" activeClassName="active-link">
                                <FaPhoneAlt className="icon" /> Call Logs
                            </NavLink>
                        </li>
                        <li>
                            <NavLink to="/performance-reports" activeClassName="active-link">
                                <FaFileAlt className="icon" /> Performance Reports
                            </NavLink>
                        </li>
                        <li>
                            <NavLink to="/admin-settings" activeClassName="active-link">
                                <FaCog className="icon" /> Admin Settings
                            </NavLink>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>
    );
};

export default Sidebar;