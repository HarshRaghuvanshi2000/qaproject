// src/App.js
import React from 'react';
import { BrowserRouter as Router, Routes, Route, useLocation } from 'react-router-dom';
import Login from './pages/Login';
import Dashboard from './pages/Dashboard';
import PerformanceReports from './pages/PerformanceReports';
import AdminSettings from './pages/AdminSettings';
import Sidebar from './components/Sidebar';
import Header from './components/Header';
import CallLogsComponent from './pages/CallLogsComponent';
import DetailedReport from './pages/DetailedReport';
import ProtectedRoute from './components/ProtectedRoute';

const AppContent = () => {
    const location = useLocation();
    const shouldShowHeaderAndSidebar = !['/login', '/'].includes(location.pathname);

    return (
        <>
            {shouldShowHeaderAndSidebar && (
                <>
                    <Sidebar />
                    <Header />
                </>
            )}
            <Routes>
                <Route path="/" element={<Login />} />
                <Route path="/login" element={<Login />} />
                {['/dashboard', '/performance-reports', '/admin-settings', '/call-logs', '/detailed-report'].map((path) => (
                    <Route
                        key={path}
                        path={path}
                        element={
                            <ProtectedRoute>
                                {path === '/dashboard' && <Dashboard />}
                                {path === '/performance-reports' && <PerformanceReports />}
                                {path === '/admin-settings' && <AdminSettings />}
                                {path === '/call-logs' && <CallLogsComponent />}
                                {path === '/detailed-report' && <DetailedReport />}
                            </ProtectedRoute>
                        }
                    />
                ))}
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
