import React from 'react';
import { Link } from 'react-router-dom';
import '../styles/Dashboard.css';
import '../App.css';


const Dashboard = () => {
    const callData = [
        { type: "Actionable Calls", total: 4500, qa: 225, pending: 220, icon: "fas fa-phone-alt", color: "#007bff" },
        { type: "Abusive Calls", total: 4500, qa: 225, pending: 220, icon: "fas fa-exclamation-circle", color: "#dc3545" },
        { type: "Missed Calls", total: 4500, qa: 225, pending: 220, icon: "fas fa-times-circle", color: "#6c757d" },
        { type: "Non Voice Signal", total: 4500, qa: 225, pending: 220, icon: "fas fa-wave-square", color: "#17a2b8" },
        { type: "No Response calls", total: 4500, qa: 225, pending: 220, icon: "fas fa-phone-slash", color: "#ffc107" },
        { type: "Trip Monitoring calls", total: 4500, qa: 225, pending: 220, icon: "fas fa-road", color: "#28a745" },
        { type: "Feedback calls", total: 4500, qa: 225, pending: 220, icon: "fas fa-comments", color: "#6610f2" },
        { type: "Abusive Calls", total: 4500, qa: 225, pending: 220, icon: "fas fa-exclamation-circle", color: "#dc3545" },

    ];

    return (
            <div className="main-content">
                    <h1 className="dashboard-title">Call Logs</h1>
                    <div className="call-log-sections">
                        {callData.map((call, index) => (
                            <div className="call-log-section" key={index}>
                                <h2>
                                    <i className={`${call.icon}`} style={{ color: call.color, marginRight: '10px' }}></i>
                                    {call.type}
                                </h2>
                                <div className="call-log-details">
                                    <div className="call-log-detail">
                                        <strong>Total Calls</strong>
                                        <span>{call.total}</span>
                                    </div>
                                    <div className="call-log-detail">
                                        <strong>Calls For QA</strong>
                                        <span>{call.qa}</span>
                                    </div>
                                    <div className="call-log-detail">
                                        <strong>Pending</strong>
                                        <span>{call.pending}</span>
                                    </div>
                                    <div className="call-log-detail">
                                        <strong>Call List</strong>
                                        <span>
                                            <Link to="/call-logs" className="call-list-link">Table List</Link>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        ))}
                    </div>
                </div>
    );
};

export default Dashboard;
