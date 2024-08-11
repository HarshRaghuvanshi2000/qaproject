import React, { useState, useEffect, useCallback } from 'react';
import '../styles/PerformanceReports.css';
import { Table, Button, Form, Row, Col, Dropdown } from 'react-bootstrap';
import { jsPDF } from 'jspdf';
import 'jspdf-autotable';
import { FaFilePdf, FaFileExcel } from 'react-icons/fa';
import * as XLSX from 'xlsx';
import { getCoQaDataByDateRange } from '../services/api'; // Import your API function
import '../App.css';

const PerformanceReports = () => {
    const [reportType, setReportType] = useState("CO performance");
    const [sortConfig, setSortConfig] = useState(null);
    const [currentPage, setCurrentPage] = useState(1);
    const [itemsPerPage] = useState(10);
    const [data, setData] = useState([]);
    const [searchTerm, setSearchTerm] = useState("");
    const [endDate, setEndDate] = useState("");
    const [startDate, setStartDate] = useState("");
    const [selectedReportType, setSelectedReportType] = useState("CO performance"); // New state to track dropdown selection
   
    const fetchData = useCallback(async () => {
        try {
            const fetchedData = await getCoQaDataByDateRange(startDate, endDate, selectedReportType); // Include reportType in API call if needed
            setData(fetchedData);
            setReportType(selectedReportType); // Update reportType only after search is clicked
        } catch (error) {
            console.error('Failed to fetch data:', error);
            setData([]); // Clear data in case of error
        }
    }, [startDate, endDate, selectedReportType]);



    useEffect(() => {
        const today = new Date();
        const lastMonth = new Date(today.getFullYear(), today.getMonth() - 1, today.getDate());

        setStartDate(lastMonth.toISOString().split('T')[0]);
        setEndDate(today.toISOString().split('T')[0]);
    }, []);

    useEffect(() => {
        if (startDate && endDate) {
            fetchData();
        }
    }, [startDate, endDate, fetchData]);
    const handleSearch = () => {
        if (startDate && endDate) {
            fetchData();
        } else {
            console.error('Please fill all required fields');
        }
    };

    const handleSearchTermChange = (e) => {
        setSearchTerm(e.target.value);
    };

    const filteredData = data.filter(item => {
        return Object.values(item).some(val =>
            val.toString().toLowerCase().includes(searchTerm.toLowerCase())
        );
    });

    const sortedData = React.useMemo(() => {
        if (!filteredData) return [];
        let sortableData = [...filteredData];
        if (sortConfig !== null) {
            sortableData.sort((a, b) => {
                if (a[sortConfig.key] < b[sortConfig.key]) {
                    return sortConfig.direction === 'ascending' ? -1 : 1;
                }
                if (a[sortConfig.key] > b[sortConfig.key]) {
                    return sortConfig.direction === 'ascending' ? 1 : -1;
                }
                return 0;
            });
        }
        return sortableData;
    }, [filteredData, sortConfig]);

    const requestSort = key => {
        let direction = 'ascending';
        if (sortConfig && sortConfig.key === key && sortConfig.direction === 'ascending') {
            direction = 'descending';
        }
        setSortConfig({ key, direction });
    };

    const getClassNamesFor = (name) => {
        if (!sortConfig) {
            return;
        }
        return sortConfig.key === name ? sortConfig.direction : undefined;
    };

    const indexOfLastItem = currentPage * itemsPerPage;
    const indexOfFirstItem = indexOfLastItem - itemsPerPage;
    const currentItems = sortedData.slice(indexOfFirstItem, indexOfLastItem);

    const paginate = (pageNumber) => setCurrentPage(pageNumber);

    const totalPages = Math.ceil(sortedData.length / itemsPerPage);
    const pageNumbers = [];
    const maxPageNumbersToShow = 5;

    let startPage = Math.max(currentPage - Math.floor(maxPageNumbersToShow / 2), 1);
    let endPage = startPage + maxPageNumbersToShow - 1;

    if (endPage > totalPages) {
        endPage = totalPages;
        startPage = Math.max(endPage - maxPageNumbersToShow + 1, 1);
    }

    for (let i = startPage; i <= endPage; i++) {
        pageNumbers.push(i);
    }

    const renderPageNumbers = () => {
        return (
            <>
                {currentPage > 1 && (
                    <li key="prev" onClick={() => paginate(currentPage - 1)}>&laquo;</li>
                )}
                {pageNumbers.map(number => (
                    <li key={number} className={number === currentPage ? 'active' : ''} onClick={() => paginate(number)}>
                        {number}
                    </li>
                ))}
                {currentPage < totalPages && (
                    <li key="next" onClick={() => paginate(currentPage + 1)}>&raquo;</li>
                )}
            </>
        );
    };

    const downloadPDF = () => {
        const doc = new jsPDF();
        const tableColumn = reportType === "CO performance"
            ? ["Name", "Login ID", "Total Calls", "Total Completed Calls", "Average Call Duration", "SOP Score", "Active Listening Score", "Details Capturing Score", "Address Tagging Score", "Handled Time", "Average Score"]
            : ["Name", "Login ID", "QA Calls", "Completed QA", "Average QA Completion Time", "Average Pending QA Per Day", "Detailed Report"];

        const tableRows = data.map(row => Object.values(row));

        doc.autoTable({
            head: [tableColumn],
            body: tableRows,
        });

        doc.save("report.pdf");
    };

    const downloadExcel = () => {
        const ws = XLSX.utils.json_to_sheet(data);
        const wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, ws, "Performance Report");
        XLSX.writeFile(wb, "performance_report.xlsx");
    };

    return (
        <div className="main-content">
            <div className="title-and-search">
                <h1 className="performance-title">Performance Reports</h1>
                <Form.Control
                    type="text"
                    placeholder="Search..."
                    value={searchTerm}
                    onChange={handleSearchTermChange}
                    className="search-bar"
                />
            </div>
            <div className="filters mb-3">
                <Row className="align-items-end">
                    <Col md={2}>
                        <Form.Group controlId="reportType">
                            <Form.Label>Select Report Type *</Form.Label>
                            <Form.Control as="select" value={selectedReportType} onChange={(e) => setSelectedReportType(e.target.value)}>                                <option value="CO performance">CO performance</option>
                                <option value="SCO performance">SCO performance</option>
                            </Form.Control>
                        </Form.Group>
                    </Col>
                    <Col md={2}>
                        <Form.Group controlId="fromDate">
                            <Form.Label>From *</Form.Label>
                            <Form.Control
                                type="date"
                                value={startDate}
                                onChange={(e) => setStartDate(e.target.value)}
                            />
                        </Form.Group>
                    </Col>
                    <Col md={2}>
                        <Form.Group controlId="toDate">
                            <Form.Label>To *</Form.Label>
                            <Form.Control
                                type="date"
                                value={endDate}
                                onChange={(e) => setEndDate(e.target.value)}
                            />
                        </Form.Group>
                    </Col>
                    <Col md={2}>
                        <Form.Group controlId="selectShift">
                            <Form.Label>Select Shift</Form.Label>
                            <Form.Control as="select" defaultValue="All">
                                <option>All</option>
                                <option>Morning Shift: 8 AM - 2 PM</option>
                                <option>Afternoon Shift: 2 PM - 8 PM</option>
                                <option>Night Shift: 8 PM - 8 AM</option>
                            </Form.Control>
                        </Form.Group>
                    </Col>
                    <Col md={1} className="d-flex align-items-end">
                        <Button variant="primary" className="w-100" onClick={handleSearch}>Search</Button>
                    </Col>
                    <Col md={1} className="d-flex align-items-end ml-auto">
                        <Dropdown className="export-dropdown">
                            <Dropdown.Toggle variant="primary" id="dropdown-basic">
                                Export
                            </Dropdown.Toggle>
                            <Dropdown.Menu>
                                <Dropdown.Item onClick={downloadPDF} className="export-option">
                                    <FaFilePdf style={{ color: 'red' }} /> Export PDF
                                </Dropdown.Item>
                                <Dropdown.Item onClick={downloadExcel} className="export-option">
                                    <FaFileExcel style={{ color: 'green' }} /> Export Excel
                                </Dropdown.Item>
                            </Dropdown.Menu>
                        </Dropdown>
                    </Col>
                </Row>
            </div>
            <div className="table-responsive">
                <Table striped bordered hover>
                    <thead>
                        <tr>
                        {reportType === "CO performance" && <>
                                    <th onClick={() => requestSort('name')} className={getClassNamesFor('name')}>Name</th>
                                    <th onClick={() => requestSort('login_id')} className={getClassNamesFor('login_id')}>Login ID</th>
                                    <th onClick={() => requestSort('total_calls')} className={getClassNamesFor('total_calls')}>Total Calls</th>
                                    <th onClick={() => requestSort('total_completed_calls')} className={getClassNamesFor('total_completed_calls')}>Total Completed Calls</th>
                                    <th onClick={() => requestSort('average_call_duration')} className={getClassNamesFor('average_call_duration')}>Average Call Duration</th>
                                    <th onClick={() => requestSort('sop_score')} className={getClassNamesFor('sop_score')}>SOP Score</th>
                                    <th onClick={() => requestSort('active_listening_score')} className={getClassNamesFor('active_listening_score')}>Active Listening Score</th>
                                    <th onClick={() => requestSort('details_capturing_score')} className={getClassNamesFor('details_capturing_score')}>Details Capturing Score</th>
                                    <th onClick={() => requestSort('address_tagging_score')} className={getClassNamesFor('address_tagging_score')}>Address Tagging Score</th>
                                    <th onClick={() => requestSort('handled_time')} className={getClassNamesFor('handled_time')}>Handled Time</th>
                                    <th onClick={() => requestSort('average_score')} className={getClassNamesFor('average_score')}>Average Score</th>
                                    </>}
                                    {reportType === "SCO performance" && <>
                                    <th onClick={() => requestSort('name')} className={getClassNamesFor('name')}>Name</th>
                                    <th onClick={() => requestSort('login_id')} className={getClassNamesFor('login_id')}>Login ID</th>
                                    <th onClick={() => requestSort('qa_calls')} className={getClassNamesFor('qa_calls')}>QA Calls</th>
                                    <th onClick={() => requestSort('completed_qa')} className={getClassNamesFor('completed_qa')}>Completed QA</th>
                                    <th onClick={() => requestSort('average_qa_completion_time')} className={getClassNamesFor('average_qa_completion_time')}>Average QA Completion Time</th>
                                    <th onClick={() => requestSort('average_pending_qa_per_day')} className={getClassNamesFor('average_pending_qa_per_day')}>Average Pending QA Per Day</th>
                                    <th onClick={() => requestSort('details_report')} className={getClassNamesFor('details_report')}>Detailed Report</th>
                                    </>}                            
                        </tr>
                    </thead>
                    <tbody>
                        {currentItems.length > 0 ? (
                            currentItems.map((row, index) => (
                                <tr key={index}>
                                      {reportType === "CO performance" && <>
                                    <td>{row.co_name}</td>                                    <td>{row.co_employee_code}</td>
                                    <td>{row.co_call_duration}</td>
                                    <td>{row.co_call_time}</td>
                                    <td>{row.sop_score}</td>
                                    <td>{row.sop_score}</td>
                                    <td>{row.active_listening_score}</td>
                                    <td>{row.relevent_detail_score}</td>
                                    <td>{row.address_tagging_score}</td>
                                    <td>{row.call_type}</td>
                                    <td>{row.call_type}</td>
                                    </>}
                                    {reportType === "SCO performance" && <>
                                        <td>{row.co_name}</td>                                    <td>{row.co_employee_code}</td>
                                    <td>{row.co_call_duration}</td>
                                    <td>{row.co_call_time}</td>
                                    <td>{row.sop_score}</td>
                                    <td>{row.sop_score}</td>
                                    <td>{row.active_listening_score}</td>
                                    </>}
                                </tr>
                            ))
                        ) : (
                            <tr>
                                <td colSpan="11" className="text-center">No data found</td>
                            </tr>
                        )}
                    </tbody>
                </Table>
            </div>
            <div className="pagination-container">
                <ul className="pagination">
                    {renderPageNumbers()}
                </ul>
            </div>
        </div>
    );
};

export default PerformanceReports;
