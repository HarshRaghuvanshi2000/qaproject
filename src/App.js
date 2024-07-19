// src/App.js

import React from 'react';
import { BrowserRouter as Router, Routes, Route, useLocation, Navigate } from 'react-router-dom';
import Login from './pages/Login';
import Dashboard from './pages/Dashboard';
import PerformanceReports from './pages/PerformanceReports';
import AdminSettings from './pages/AdminSettings'; // Import AdminSettings
import Sidebar from './components/Sidebar'; // Import Sidebar
import Header from './components/Header'; // Import Header
import CallLogsComponent from './components/CallLogsComponent'; // Import CallLogsComponent
import DetailedReport from './pages/DetailedReport';

const AppContent = () => {
    const location = useLocation();
    const shouldShowHeaderAndSidebar = location.pathname !== '/login' && location.pathname !== '/';

    console.log("Current path:", location.pathname); // Debugging log
    console.log("Should show header and sidebar:", shouldShowHeaderAndSidebar); // Debugging log

    return (
        <>
            {shouldShowHeaderAndSidebar && <Sidebar />}
            {shouldShowHeaderAndSidebar && <Header />}
            <Routes>
                <Route path="/login" element={<Login />} />
                <Route path="/dashboard" element={<Dashboard />} />
                <Route path="/" element={<Login />} /> {/* Default route to login */}
                <Route path="/performance-reports" element={<PerformanceReports />} />
                <Route path="/admin-settings" element={<AdminSettings />} /> {/* Add AdminSettings route */}
                <Route path="/call-logs" element={<CallLogsComponent />} /> {/* Add CallLogsComponent route */}
                <Route path="/detailed-report" element={<DetailedReport/>} /> {/* Add DetailedReport route */}
                <Route path="/" element={<Navigate to="/dashboard" replace />} />
            </Routes>
        </>
    );
};

const App = () => (
    <Router>
        <AppContent />
    </Router>
);

export default App;
