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

    const allowedPathsForLimitedUser = ['/dashboard', '/call-logs'];

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
                <Route
                    path="/dashboard"
                    element={
                        <ProtectedRoute allowedPaths={allowedPathsForLimitedUser}>
                            <Dashboard />
                        </ProtectedRoute>
                    }
                />
                <Route
                    path="/call-logs"
                    element={
                        <ProtectedRoute allowedPaths={allowedPathsForLimitedUser}>
                            <CallLogsComponent />
                        </ProtectedRoute>
                    }
                />
                <Route
                    path="/performance-reports"
                    element={
                        <ProtectedRoute allowedPaths={[]}>
                            <PerformanceReports />
                        </ProtectedRoute>
                    }
                />
                <Route
                    path="/admin-settings"
                    element={
                        <ProtectedRoute allowedPaths={[]}>
                            <AdminSettings />
                        </ProtectedRoute>
                    }
                />
                <Route
                    path="/detailed-report"
                    element={
                        <ProtectedRoute allowedPaths={[]}>
                            <DetailedReport />
                        </ProtectedRoute>
                    }
                />
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
