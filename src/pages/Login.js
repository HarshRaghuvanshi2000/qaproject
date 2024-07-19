// src/pages/Login.js

import React, { useState } from 'react';
import '../styles/Login.css'; // Correct path to the CSS file
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faUser, faLock, faEye, faEyeSlash, faExclamationTriangle } from '@fortawesome/free-solid-svg-icons';
import logo from '../assets/images/logo_hry112.png';
import { login } from '../services/api'; // Import your login API function
import { useNavigate } from 'react-router-dom';

const Login = () => {
    const navigate = useNavigate();
    const [username, setUsername] = useState('');
    const [password, setPassword] = useState('');
    const [passwordVisible, setPasswordVisible] = useState(false);
    const [errors, setErrors] = useState({ username: false, password: false });
    const [loginError, setLoginError] = useState('');

    const togglePasswordVisibility = () => {
        setPasswordVisible(!passwordVisible);
    };

    const handleLogin = async (e) => {
        e.preventDefault();
        const validationErrors = {};

        if (!username) {
            validationErrors.username = true;
        }

        if (!password) {
            validationErrors.password = true;
        }

        setErrors(validationErrors);

        if (!validationErrors.username && !validationErrors.password) {
            try {
                const response = await login(username, password);
                console.log('Login response:', response);

                if (response.token) {
                    // Handle successful login
                    console.log('Login successful');
                    localStorage.setItem('token', response.token);
                    navigate('/dashboard');
                } else {
                    // Handle login failure (e.g., show error message)
                    setLoginError('Incorrect username or password.');
                    console.error('Login failed:', response.message);
                }
            } catch (error) {
                setLoginError('Login error. Please try again.');
                console.error('Login error:', error);
            }
        }
    };

    return (
        <div className="login-wrapper">
            <div className="login-container">
                <div className="logo-container">
                    <img src={logo} alt="Logo" className="logo" />
                </div>
                <div className="form-container">
                    <h2>Quality Assurance Login System</h2>
                    <form onSubmit={handleLogin}>
                        <div className={`input-container ${errors.username ? 'has-error' : ''}`}>
                            <label htmlFor="username">Username</label>
                            <div className="input-icon">
                                <FontAwesomeIcon icon={faUser} className="icon" />
                                <input
                                    type="text"
                                    id="username"
                                    value={username}
                                    onChange={(e) => setUsername(e.target.value)}
                                    placeholder="Enter your Username"
                                    className={`form-control ${errors.username ? 'error-border' : ''}`}
                                    required
                                />
                            </div>
                            {errors.username && <span className="error-message">Please fill out this field.</span>}
                        </div>
                        <div className={`input-container password-container ${errors.password ? 'has-error' : ''}`}>
                            <label htmlFor="password">Password</label>
                            <div className="input-icon">
                                <FontAwesomeIcon icon={faLock} className="icon" />
                                <input
                                    type={passwordVisible ? 'text' : 'password'}
                                    id="password"
                                    value={password}
                                    onChange={(e) => setPassword(e.target.value)}
                                    placeholder="Enter your password"
                                    className={`form-control ${errors.password ? 'error-border' : ''}`}
                                    required
                                />
                                <FontAwesomeIcon
                                    icon={passwordVisible ? faEyeSlash : faEye}
                                    className="password-icon"
                                    onClick={togglePasswordVisibility}
                                />
                            </div>
                            {errors.password && <span className="error-message">Please fill out this field.</span>}
                        </div>
                        {loginError && (
                            <div className="login-error-message">
                                <FontAwesomeIcon icon={faExclamationTriangle} className="error-icon" />
                                {loginError}
                            </div>
                        )}
                        <button type="submit">LOG IN</button>
                    </form>
                </div>
            </div>
        </div>
    );
};

export default Login;
