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
                    total: call.totalCalls,
                    qa: call.completedCalls,
                    pending: call.pendingCalls,
                    typeId:call.signalTypeId,
                    icon: getIconForType(call.signalType), // You can map icons based on the signalType
                    color: getColorForType(call.signalType), // You can map colors based on the signalType
                }));
                setCallData(formattedData);
            } catch (error) {
                console.error('Failed to fetch call summary:', error);
            }
        };

        fetchCallSummary();
    }, []);

    // Map signal types to icons
    const getIconForType = (type) => {
        switch (type) {
            case 'Actionable Calls': return 'fas fa-phone-alt';
            case 'Abusive Calls': return 'fas fa-exclamation-circle';
            case 'Missed Calls': return 'fas fa-times-circle';
            case 'Non Voice Signal': return 'fas fa-wave-square';
            case 'No Response Calls': return 'fas fa-phone-slash';
            case 'Trip Monitoring Calls': return 'fas fa-road';
            case 'Feedback Calls': return 'fas fa-comments';
            default: return 'fas fa-phone-alt';
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
            case 'Feedback calls': return '#6610f2';
            default: return '#007bff';
        }
    };

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
                                    <Link to={`/call-logs?signalTypeId=${call.typeId}`} className="call-list-link">Table List</Link>
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
