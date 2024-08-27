import React, { useState, useEffect } from 'react';
import 'bootstrap/dist/css/bootstrap.min.css';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faEdit, faSort } from '@fortawesome/free-solid-svg-icons';
import { getSignalTypes, updateSignalType } from '../services/api';  // Import API functions
import '../styles/AdminSettings.css';
import '../App.css';

const AdminSettings = () => {
    const [showModal, setShowModal] = useState(false);
    const [callTypeId, setCallTypeId] = useState('');
    const [callType, setCallType] = useState('');
    const [percentage, setPercentage] = useState('');
    const [maxLimit, setMaxLimit] = useState('');
    const [sortConfig, setSortConfig] = useState({ key: null, direction: 'ascending' });
    const [data, setData] = useState([]);
    const [showSuccessMessage, setShowSuccessMessage] = useState(false);

    useEffect(() => {
        fetchSignalTypes();
    }, []);

    const fetchSignalTypes = async () => {
        try {
            const signalTypes = await getSignalTypes();
            const filteredData = signalTypes.filter(type => type.is_active === 'Y');
            setData(filteredData);
        } catch (error) {
            console.error('Failed to fetch signal types:', error);
        }
    };

    const handleEditClick = (id, type, percentage, limit) => {
        setShowModal(true);
        setCallTypeId(id);
        setCallType(type);
        setPercentage(percentage);
        setMaxLimit(limit);
    };

    const handleCancelClick = () => {
        setShowModal(false);
    };

    const handleApplyClick = async () => {
        try {
            await updateSignalType(callTypeId, percentage, maxLimit);
            setShowModal(false);
            fetchSignalTypes();
            setShowSuccessMessage(true);
            setTimeout(() => {
                setShowSuccessMessage(false);
            }, 2000); // Show success message for 2 seconds
        } catch (error) {
            console.error('Failed to update signal type:', error);
        }
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
        <div className="main-content">
            <h1 className="admin-title">Admin Settings</h1>
            <div className="table-container-settings">
                <div className="table-responsive">
                    <table className="table table-bordered">
                        <thead>
                            <tr>
                                <th onClick={() => requestSort('id')}>
                                    Sr. No <FontAwesomeIcon icon={faSort} />
                                </th>
                                <th onClick={() => requestSort('signal_type')}>
                                    Call Type <FontAwesomeIcon icon={faSort} />
                                </th>
                                <th onClick={() => requestSort('percentage_of_calls_qa')}>
                                    Percentage of Calls for QA <FontAwesomeIcon icon={faSort} />
                                </th>
                                <th onClick={() => requestSort('maximum_limit')}>
                                    Maximum Limit (in Number) <FontAwesomeIcon icon={faSort} />
                                </th>
                                <th>Modify</th>
                            </tr>
                        </thead>
                        <tbody>
                            {data.map((item) => (
                                <tr key={item.signal_type_id}>
                                    <td>{item.signal_type_id}</td>
                                    <td>{item.signal_type}</td>
                                    <td>{item.percentage_of_calls_qa}%</td>
                                    <td>{item.maximum_limit}</td>
                                    <td>
                                        <button
                                            className="btn btn-primary"
                                            onClick={() => handleEditClick(item.signal_type_id, item.signal_type, item.percentage_of_calls_qa, item.maximum_limit)}
                                        >
                                            <FontAwesomeIcon icon={faEdit} />
                                        </button>
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>
            </div>

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
                                        <input
                                            type="number"
                                            className="form-control"
                                            value={maxLimit}
                                            onChange={(e) => setMaxLimit(e.target.value)}
                                        />
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

            {showSuccessMessage && (
                <div className="alert alert-success" role="alert">
                    Successfully updated!
                </div>
            )}
        </div>
    );
};

export default AdminSettings;
