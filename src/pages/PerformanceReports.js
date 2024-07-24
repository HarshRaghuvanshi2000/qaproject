import React, { useState, useEffect } from 'react';
import '../styles/PerformanceReports.css';
import { Link } from 'react-router-dom';
import { Table, Button, Form, Row, Col, Pagination,Dropdown } from 'react-bootstrap';
import { jsPDF } from 'jspdf';
import 'jspdf-autotable';
import { FaFilePdf, FaFileExcel } from 'react-icons/fa';
import * as XLSX from 'xlsx';
import '../App.css';


const generateSampleData = (numRows, reportType) => {
    const data = [];
    for (let i = 1; i <= numRows; i++) {
        if (reportType === "CO performance") {
            data.push({
                name: `Name ${i}`,
                loginID: `LoginID${i}`,
                totalCalls: Math.floor(Math.random() * 1000),
                totalCompletedCalls: Math.floor(Math.random() * 1000),
                avgCallDuration: `${Math.floor(Math.random() * 60)} min`,
                sopScore: `${Math.floor(Math.random() * 100)}%`,
                activeListeningScore: `${Math.floor(Math.random() * 100)}%`,
                detailsCapturingScore: `${Math.floor(Math.random() * 100)}%`,
                addressTaggingScore: `${Math.floor(Math.random() * 100)}%`,
                handledTime: `${Math.floor(Math.random() * 60)} min`,
                avgScore: `${Math.floor(Math.random() * 100)}%`,
            });
        } else if (reportType === "SCO performance") {
            data.push({
                name: `Name ${i}`,
                loginID: `LoginID${i}`,
                qaCalls: Math.floor(Math.random() * 1000),
                completedQA: Math.floor(Math.random() * 1000),
                avgQACompletionTime: `${Math.floor(Math.random() * 60)} min`,
                avgPendingQAPerDay: `${Math.floor(Math.random() * 50)} per day`,
                detailedReport: `Report ${i}`
            });
        }
    }
    return data;
};

const PerformanceReports = () => {
    const [reportType, setReportType] = useState("CO performance");
    const [sortConfig, setSortConfig] = useState(null);
    const [currentPage, setCurrentPage] = useState(1);
    const [itemsPerPage] = useState(10);
    const [data, setData] = useState(generateSampleData(30, reportType));

    useEffect(() => {
        setData(generateSampleData(100, reportType));
    }, [reportType]);

    const sortedData = React.useMemo(() => {
        let sortableData = [...data];
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
    }, [data, sortConfig]);

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
            <h1 className="performance-title">Performance Reports</h1>
            <div className="filters mb-3">
                <Row>
                    <Col md={2}>
                        <Form.Group controlId="reportType">
                            <Form.Label>Select Report Type *</Form.Label>
                            <Form.Control as="select" value={reportType} onChange={(e) => setReportType(e.target.value)}>
                                <option value="CO performance">CO performance</option>
                                <option value="SCO performance">SCO performance</option>
                            </Form.Control>
                        </Form.Group>
                    </Col>
                    <Col md={2}>
                        <Form.Group controlId="fromDate">
                            <Form.Label>From *</Form.Label>
                            <Form.Control type="date" placeholder="dd-mm-yyyy" />
                        </Form.Group>
                    </Col>
                    <Col md={2}>
                        <Form.Group controlId="toDate">
                            <Form.Label>To *</Form.Label>
                            <Form.Control type="date" placeholder="dd-mm-yyyy" />
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
                        <Button variant="primary" className="w-100">Search</Button>
                    </Col>
                    <Col md={1} className="d-flex align-items-end">
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
                            {reportType === "CO performance" ? (
                                <>
                                    <th onClick={() => requestSort('name')} className={getClassNamesFor('name')}>Name & Login ID</th>
                                    <th onClick={() => requestSort('totalCalls')} className={getClassNamesFor('totalCalls')}>Total Calls</th>
                                    <th onClick={() => requestSort('totalCompletedCalls')} className={getClassNamesFor('totalCompletedCalls')}>Total Completed Calls</th>
                                    <th onClick={() => requestSort('avgCallDuration')} className={getClassNamesFor('avgCallDuration')}>Average Call Duration</th>
                                    <th onClick={() => requestSort('sopScore')} className={getClassNamesFor('sopScore')}>SOP Score</th>
                                    <th onClick={() => requestSort('activeListeningScore')} className={getClassNamesFor('activeListeningScore')}>Active Listening Score</th>
                                    <th onClick={() => requestSort('detailsCapturingScore')} className={getClassNamesFor('detailsCapturingScore')}>Details Capturing Score</th>
                                    <th onClick={() => requestSort('addressTaggingScore')} className={getClassNamesFor('addressTaggingScore')}>Address Tagging Score</th>
                                    <th onClick={() => requestSort('handledTime')} className={getClassNamesFor('handledTime')}>Handled Time</th>
                                    <th onClick={() => requestSort('avgScore')} className={getClassNamesFor('avgScore')}>Average Score</th>
                                </>
                            ) : (
                                <>
                                    <th onClick={() => requestSort('name')} className={getClassNamesFor('name')}>Name</th>
                                    <th onClick={() => requestSort('loginID')} className={getClassNamesFor('loginID')}>Login ID</th>
                                    <th onClick={() => requestSort('qaCalls')} className={getClassNamesFor('qaCalls')}>QA Calls</th>
                                    <th onClick={() => requestSort('completedQA')} className={getClassNamesFor('completedQA')}>Completed QA</th>
                                    <th onClick={() => requestSort('avgQACompletionTime')} className={getClassNamesFor('avgQACompletionTime')}>Average QA Completion Time</th>
                                    <th onClick={() => requestSort('avgPendingQAPerDay')} className={getClassNamesFor('avgPendingQAPerDay')}>Average Pending QA per day</th>
                                    <th>Detailed Report</th>
                                </>
                            )}
                        </tr>
                    </thead>
                    <tbody>
                        {currentItems.map((item, index) => (
                            <tr key={index}>
                                {reportType === "CO performance" ? (
                                    <>
                                        <td>{item.name}</td>
                                        <td>{item.totalCalls}</td>
                                        <td>{item.totalCompletedCalls}</td>
                                        <td>{item.avgCallDuration}</td>
                                        <td>{item.sopScore}</td>
                                        <td>{item.activeListeningScore}</td>
                                        <td>{item.detailsCapturingScore}</td>
                                        <td>{item.addressTaggingScore}</td>
                                        <td>{item.handledTime}</td>
                                        <td>{item.avgScore}</td>
                                    </>
                                ) : (
                                    <>
                                        <td>{item.name}</td>
                                        <td>{item.loginID}</td>
                                        <td>{item.qaCalls}</td>
                                        <td>{item.completedQA}</td>
                                        <td>{item.avgQACompletionTime}</td>
                                        <td>{item.avgPendingQAPerDay}</td>
                                        <td>
                                            <Link to="/detailed-report">
                                                <Button variant="link">{item.detailedReport}</Button>
                                            </Link>
                                        </td>
                                    </>
                                )}
                            </tr>
                        ))}
                    </tbody>
                </Table>
            </div>
            <div className="pagination-container">
                <Pagination className="justify-content-center">
                    <Pagination.Prev
                        onClick={() => paginate(currentPage - 1)}
                        disabled={currentPage === 1}
                    />
                    {pageNumbers.map(number => (
                        <Pagination.Item
                            key={number}
                            active={number === currentPage}
                            onClick={() => paginate(number)}
                        >
                            {number}
                        </Pagination.Item>
                    ))}
                    <Pagination.Next
                        onClick={() => paginate(currentPage + 1)}
                        disabled={currentPage === totalPages}
                    />
                </Pagination>
            </div>
        </div>
    );
};

export default PerformanceReports;
