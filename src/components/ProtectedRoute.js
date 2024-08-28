// src/components/ProtectedRoute.js
import React from 'react';
import { Navigate } from 'react-router-dom';

const ProtectedRoute = ({ children, allowedPaths }) => {
    const token = localStorage.getItem('token');
    const username = localStorage.getItem('username'); // Assuming username is stored in localStorage after login
    const isAuthenticated = !!token;

    // Check if user is "mor_2314" and limit access to only allowedPaths
    if (isAuthenticated && username === 'mor_2314') {
        const isAllowed = allowedPaths.includes(window.location.pathname);
        return isAllowed ? children : <Navigate to="/dashboard" />;
    }

    // For other users, allow access to all routes
    return isAuthenticated ? children : <Navigate to="/login" />;
};

export default ProtectedRoute;
