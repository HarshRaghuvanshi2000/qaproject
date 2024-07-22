// src/App.js
import React from 'react';
import { BrowserRouter as Router, Routes, Route, useLocation } from 'react-router-dom';
import Login from './pages/Login';
import Dashboard from './pages/Dashboard';
import PerformanceReports from './pages/PerformanceReports';
import AdminSettings from './pages/AdminSettings';
import Sidebar from './components/Sidebar';
import Header from './components/Header';
import CallLogsComponent from './components/CallLogsComponent';
import DetailedReport from './pages/DetailedReport';
import ProtectedRoute from './components/ProtectedRoute';

const AppContent = () => {
    const location = useLocation();
    const shouldShowHeaderAndSidebar = location.pathname !== '/login' && location.pathname !== '/';

    return (
        <>
            {shouldShowHeaderAndSidebar && <Sidebar />}
            {shouldShowHeaderAndSidebar && <Header />}
            <Routes>
                <Route path="/login" element={<Login />} />
                <Route path="/" element={<Login />} /> {/* Default route to login */}
                <Route path="/dashboard" element={
                    <ProtectedRoute>
                        <Dashboard />
                    </ProtectedRoute>
                } />
                <Route path="/performance-reports" element={
                    <ProtectedRoute>
                        <PerformanceReports />
                    </ProtectedRoute>
                } />
                <Route path="/admin-settings" element={
                    <ProtectedRoute>
                        <AdminSettings />
                    </ProtectedRoute>
                } />
                <Route path="/call-logs" element={
                    <ProtectedRoute>
                        <CallLogsComponent />
                    </ProtectedRoute>
                } />
                <Route path="/detailed-report" element={
                    <ProtectedRoute>
                        <DetailedReport />
                    </ProtectedRoute>
                } />
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
