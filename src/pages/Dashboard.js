import React, { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';
import { getCallSummary } from '../services/api'; // Import the API function
import '../styles/Dashboard.css';
import '../App.css';

const Dashboard = () => {
    const [callData, setCallData] = useState([]);

    useEffect(() => {
        const fetchCallSummary = async () => {
            try {
                const data = await getCallSummary();
                const formattedData = data.map(call => ({
                    type: call.signalType,
                    total: Number(call.totalCalls), // Ensure these values are numbers
                    qa: Number(call.completedCalls), // Ensure these values are numbers
                    pending: Number(call.pendingCalls), // Ensure these values are numbers
                    typeId: call.signalTypeId,
                    icon: getIconForType(call.signalType), // Map icons based on the signalType
                    color: getColorForType(call.signalType), // Map colors based on the signalType
                }));
                setCallData(formattedData);
            } catch (error) {
                console.error('Failed to fetch call summary:', error);
            }
        };

        fetchCallSummary();
    }, []);

    // Map signal types to free Font Awesome icons
    const getIconForType = (type) => {
        switch (type) {
            case 'Actionable Calls': return 'fas fa-phone'; // Free icon for phone
            case 'Abusive Calls': return 'fas fa-exclamation-triangle'; // Free warning icon
            case 'Missed Calls': return 'fas fa-times-circle'; // Free times icon
            case 'Non Voice Signal': return 'fas fa-signal'; // Free signal icon
            case 'No Response Calls': return 'fas fa-phone-slash'; // Free phone-slash icon
            case 'Trip Monitoring Calls': return 'fas fa-road'; // Free road icon
            case 'Feedback Calls': return 'fas fa-comments'; // Free comments icon
            default: return 'fas fa-phone'; // Default free phone icon
        }
    };

    // Map signal types to colors
    const getColorForType = (type) => {
        switch (type) {
            case 'Actionable Calls': return '#007bff';
            case 'Abusive Calls': return '#dc3545';
            case 'Missed Calls': return '#6c757d';
            case 'Non Voice Signal': return '#17a2b8';
            case 'No Response Calls': return '#ffc107';
            case 'Trip Monitoring Calls': return '#28a745';
            case 'Feedback Calls': return '#6610f2';
            default: return '#007bff';
        }
    };

    // Icons for the overview section with color
    const overviewIcons = {
        total: { icon: 'fas fa-list', color: '#007bff' }, // Free list icon
        completed: { icon: 'fas fa-check-circle', color: '#28a745' }, // Free check-circle icon
        pending: { icon: 'fas fa-hourglass-half', color: '#ffc107' }, // Free hourglass icon
    };

    return (
        <div className="main-content">
            <h1 className="dashboard-title">Dashboard</h1>
            <div className="overview-section">
                <div className="overview-card">
                    <h2>
                        <i className={overviewIcons.total.icon} style={{ color: overviewIcons.total.color, marginRight: '10px' }}></i>
                        <strong>Total Calls</strong>
                    </h2>
                    <div className="overview-number">
                        {callData.reduce((acc, call) => acc + call.total, 0)}
                    </div>
                </div>
                <div className="overview-card">
                    <h2>
                        <i className={overviewIcons.completed.icon} style={{ color: overviewIcons.completed.color, marginRight: '10px' }}></i>
                        <strong>Completed Calls</strong>
                    </h2>
                    <div className="overview-number">
                        {callData.reduce((acc, call) => acc + call.qa, 0)}
                    </div>
                </div>
                <div className="overview-card">
                    <h2>
                        <i className={overviewIcons.pending.icon} style={{ color: overviewIcons.pending.color, marginRight: '10px' }}></i>
                        <strong>Pending Calls</strong>
                    </h2>
                    <div className="overview-number">
                        {callData.reduce((acc, call) => acc + call.pending, 0)}
                    </div>
                </div>
            </div>
            <div className="call-log-sections">
                {callData.map((call, index) => (
                    <div className="call-log-section" key={index}>
                        <h2>
                            <i className={call.icon} style={{ color: call.color, marginRight: '10px' }}></i>
                            {call.type}
                        </h2>
                        <div className="call-log-details">
                            <div className="call-log-detail">
                                <strong>Total Calls</strong>
                                <span>{call.total}</span>
                            </div>
                            <div className="call-log-detail">
                                <strong>Completed Calls</strong>
                                <span>{call.qa}</span>
                            </div>
                            <div className="call-log-detail">
                                <strong>Pending calls</strong>
                                <span>{call.pending}</span>
                            </div>
                            <div className="call-log-detail">
                                <strong>Call List</strong>
                                <span>
                                    <Link to={`/call-logs?signalTypeId=${call.typeId}&signalType=${call.type}`} className="call-list-link">Table List</Link>
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
