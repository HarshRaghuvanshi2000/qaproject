import React, { useState, useMemo } from 'react';
import '../styles/DetailedReport.css';
import '../App.css';
import { Table, Pagination, Dropdown } from 'react-bootstrap';
import { jsPDF } from 'jspdf';
import 'jspdf-autotable';
import * as XLSX from 'xlsx';
import { FaFilePdf, FaFileExcel } from 'react-icons/fa';

const generateSampleData = (numRows) => {
    const data = [];
    for (let i = 1; i <= numRows; i++) {
        data.push({
            srNo: i,
            eventType: `Event Type ${i}`,
            eventSubtype: `Subtype ${i}`,
            callDuration: `${Math.floor(Math.random() * 60)} min`,
            reviewStatus: 'Pending',
            callType: `Type ${i}`,
            sopScore: `${Math.floor(Math.random() * 100)}%`,
            listeningScore: `${Math.floor(Math.random() * 100)}%`,
            detailsCapturingScore: `${Math.floor(Math.random() * 100)}%`,
            addressTaggingScore: `${Math.floor(Math.random() * 100)}%`,
            handledTime: `${Math.floor(Math.random() * 60)} min`,
        });
    }
    return data;
};

const DetailedReport = () => {
    const [currentPage, setCurrentPage] = useState(1);
    const [itemsPerPage] = useState(12);
    const [sortConfig, setSortConfig] = useState(null);
    const data = generateSampleData(100);

    const sortedData = useMemo(() => {
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

    const requestSort = (key) => {
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

    const totalPages = Math.ceil(data.length / itemsPerPage);
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

    const handleExportPDF = () => {
        const doc = new jsPDF();
        doc.autoTable({
            head: [['Sr. No', 'Event Type', 'Event Subtype', 'Call Duration', 'Review Status', 'Call Type', 'Compliance of SOP QA Score', 'Active Listening & Proper Response QA Score', 'Correct and Relevant Details Capturing QA Score', 'Correct Address Tagging QA Score', 'Call Handled Time QA Time']],
            body: data.map(item => [item.srNo, item.eventType, item.eventSubtype, item.callDuration, item.reviewStatus, item.callType, item.sopScore, item.listeningScore, item.detailsCapturingScore, item.addressTaggingScore, item.handledTime]),
        });
        doc.save('detailed_report.pdf');
    };

    const handleExportExcel = () => {
        const worksheet = XLSX.utils.json_to_sheet(data);
        const workbook = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(workbook, worksheet, 'Detailed Report');
        XLSX.writeFile(workbook, 'detailed_report.xlsx');
    };

    return (
        <div className="main-content">
            <div className="header-container">
                <h1 className="detailed-title">SCO3251 - Detailed Report</h1>
                <div className="date-range">01-01-2024 To 01-02-2024</div>
                <Dropdown className="export-dropdown">
                    <Dropdown.Toggle variant="primary" id="dropdown-basic">
                        Export
                    </Dropdown.Toggle>

                    <Dropdown.Menu>
                        <Dropdown.Item onClick={handleExportPDF} className="export-option">
                            <FaFilePdf style={{ color: 'red' }} /> Export PDF
                        </Dropdown.Item>
                        <Dropdown.Item onClick={handleExportExcel} className="export-option">
                            <FaFileExcel style={{ color: 'green' }} /> Export Excel
                        </Dropdown.Item>
                    </Dropdown.Menu>
                </Dropdown>
            </div>
            <div className="table-responsive">
                <Table striped bordered hover>
                    <thead>
                        <tr>
                            {['srNo', 'eventType', 'eventSubtype', 'callDuration', 'reviewStatus', 'callType', 'sopScore', 'listeningScore', 'detailsCapturingScore', 'addressTaggingScore', 'handledTime'].map((key) => (
                                <th key={key} onClick={() => requestSort(key)} className={getClassNamesFor(key)}>
                                    {key.charAt(0).toUpperCase() + key.slice(1).replace(/([A-Z])/g, ' $1')}
                                    {sortConfig && sortConfig.key === key && (
                                        sortConfig.direction === 'ascending' ? ' 🔼' : ' 🔽'
                                    )}
                                </th>
                            ))}
                        </tr>
                    </thead>
                    <tbody>
                        {currentItems.map((item, index) => (
                            <tr key={index}>
                                <td>{item.srNo}</td>
                                <td>{item.eventType}</td>
                                <td>{item.eventSubtype}</td>
                                <td>{item.callDuration}</td>
                                <td>{item.reviewStatus}</td>
                                <td>{item.callType}</td>
                                <td>{item.sopScore}</td>
                                <td>{item.listeningScore}</td>
                                <td>{item.detailsCapturingScore}</td>
                                <td>{item.addressTaggingScore}</td>
                                <td>{item.handledTime}</td>
                            </tr>
                        ))}
                    </tbody>
                </Table>
            </div>
            <div className="pagination-container">
                <Pagination className="justify-content-center">
                    <Pagination.Prev onClick={() => paginate(currentPage - 1)} disabled={currentPage === 1} />
                    {pageNumbers.map(number => (
                        <Pagination.Item key={number} active={number === currentPage} onClick={() => paginate(number)}>
                            {number}
                        </Pagination.Item>
                    ))}
                    <Pagination.Next onClick={() => paginate(currentPage + 1)} disabled={currentPage === totalPages} />
                </Pagination>
            </div>
        </div>
    );
};

export default DetailedReport;
