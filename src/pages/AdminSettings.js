import React, { useState } from 'react';
import 'bootstrap/dist/css/bootstrap.min.css';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faEdit, faSort } from '@fortawesome/free-solid-svg-icons';
import '../styles/AdminSettings.css';

const AdminSettings = () => {
    const [showModal, setShowModal] = useState(false);
    const [callType, setCallType] = useState('');
    const [percentage, setPercentage] = useState('');
    const [maxLimit, setMaxLimit] = useState('');
    const [sortConfig, setSortConfig] = useState({ key: null, direction: 'ascending' });
    const [data, setData] = useState([
        { id: 1, type: 'Actionable Calls', percentage: '5', limit: '500' },
        { id: 2, type: 'Abusive Calls', percentage: '2', limit: '100' },
        { id: 3, type: 'Missed Calls', percentage: '2', limit: '100' },
        { id: 4, type: 'Non-voice signals', percentage: '2', limit: '100' },
        { id: 5, type: 'No Response calls', percentage: '2', limit: '100' },
        { id: 6, type: 'Trip Monitoring calls', percentage: '100', limit: '100' },
        { id: 7, type: 'Feedback calls', percentage: '1', limit: '100' },
        // Add more rows as needed
    ]);

    const handleEditClick = (type, percentage, limit) => {
        setShowModal(true);
        setCallType(type);
        setPercentage(percentage);
        setMaxLimit(limit);
    };

    const handleCancelClick = () => {
        setShowModal(false);
    };

    const handleApplyClick = () => {
        setShowModal(false);
        // Handle apply logic here (e.g., update state or send API request)
    };

    const requestSort = (key) => {
        let direction = 'ascending';
        if (sortConfig.key === key && sortConfig.direction === 'ascending') {
            direction = 'descending';
        }
        setSortConfig({ key, direction });
        let sortedData = [...data];
        sortedData.sort((a, b) => {
            if (a[key] < b[key]) {
                return direction === 'ascending' ? -1 : 1;
            }
            if (a[key] > b[key]) {
                return direction === 'ascending' ? 1 : -1;
            }
            return 0;
        });
        setData(sortedData);
    };

    return (
        <div className="admin-settings-wrapper">
            <div className="admin-settings-container">
                <h1 className="admin-title">Admin Settings</h1>
                <div className="table-container-settings">
                    <div className="table-responsive">
                        <table className="table table-bordered">
                            <thead>
                                <tr>
                                    <th onClick={() => requestSort('id')}>
                                        Sr. No <FontAwesomeIcon icon={faSort} />
                                    </th>
                                    <th onClick={() => requestSort('type')}>
                                        Call Type <FontAwesomeIcon icon={faSort} />
                                    </th>
                                    <th onClick={() => requestSort('percentage')}>
                                        Percentage of Calls for QA <FontAwesomeIcon icon={faSort} />
                                    </th>
                                    <th onClick={() => requestSort('limit')}>
                                        Maximum Limit (in Number) <FontAwesomeIcon icon={faSort} />
                                    </th>
                                    <th>Modify</th>
                                </tr>
                            </thead>
                            <tbody>
                                {data.map((item) => (
                                    <tr key={item.id}>
                                        <td>{item.id}</td>
                                        <td>{item.type}</td>
                                        <td>{item.percentage}%</td>
                                        <td>{item.limit}</td>
                                        <td>
                                            <button className="btn btn-primary" onClick={() => handleEditClick(item.type, item.percentage, item.limit)}>
                                                <FontAwesomeIcon icon={faEdit} />
                                            </button>
                                        </td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    </div>
                </div>

                {/* Modal */}
                {showModal && (
                    <div className="modal-overlay">
                        <div className="modal show d-block" tabIndex="-1" role="dialog">
                            <div className="modal-dialog modal-lg" role="document">
                                <div className="modal-content">
                                    <div className="modal-header">
                                        <h5 className="modal-title">{callType}</h5>
                                        <button type="button" className="close ml-auto" onClick={handleCancelClick}>
                                            <span>&times;</span>
                                        </button>
                                    </div>
                                    <div className="modal-body">
                                        <div className="form-group">
                                            <label>Percentage of Calls for QA:</label>
                                            <div className="input-group">
                                                <input
                                                    type="number"
                                                    className="form-control"
                                                    value={percentage}
                                                    onChange={(e) => setPercentage(e.target.value)}
                                                />
                                                <div className="input-group-append">
                                                    <span className="input-group-text">%</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div className="form-group">
                                            <label>Maximum Limit (in Number):</label>
                                            <input type="number" className="form-control" value={maxLimit} onChange={(e) => setMaxLimit(e.target.value)} />
                                        </div>
                                    </div>
                                    <div className="modal-footer">
                                        <button type="button" className="btn btn-secondary" onClick={handleCancelClick}>Cancel</button>
                                        <button type="button" className="btn btn-primary" onClick={handleApplyClick}>Apply</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                )}
            </div>
        </div>
    );
};

export default AdminSettings;
