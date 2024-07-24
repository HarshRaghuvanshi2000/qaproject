import React, { useState } from 'react';
import '../styles/DetailedReport.css';
import '../App.css';
import { Pagination, Dropdown } from 'react-bootstrap';
import 'jspdf-autotable';
import { FaFilePdf, FaFileExcel } from 'react-icons/fa';
import 'bootstrap/dist/css/bootstrap.min.css';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faSort } from '@fortawesome/free-solid-svg-icons';
import jsPDF from 'jspdf';
import autoTable from 'jspdf-autotable';
import * as XLSX from 'xlsx';

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
    const [sortConfig, setSortConfig] = useState({ key: null, direction: 'ascending' });
    const [data, setData] = useState(generateSampleData(100));

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

    const indexOfLastItem = currentPage * itemsPerPage;
    const indexOfFirstItem = indexOfLastItem - itemsPerPage;
    const currentItems = data.slice(indexOfFirstItem, indexOfLastItem);

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
        doc.setFontSize(16);
        doc.text('SCO3251 - Detailed Report', 14, 22);
        doc.setFontSize(12);
        doc.text('Date : 01-01-2024 To 01-02-2024', 14, 30);

        autoTable(doc, {
            startY: 40,
            head: [['Sr. No', 'Event Type', 'Event Subtype', 'Call Duration', 'Review Status', 'Call Type', 'SOP QA Score', 'Listening QA Score', 'Capturing QA Score', 'Address QA Score', 'Handled Time']],
            body: data.map(item => [
                item.srNo,
                item.eventType,
                item.eventSubtype,
                item.callDuration,
                item.reviewStatus,
                item.callType,
                item.sopScore,
                item.listeningScore,
                item.detailsCapturingScore,
                item.addressTaggingScore,
                item.handledTime
            ])
        });

        doc.save('detailed-report.pdf');
    };

    const handleExportExcel = () => {
        const ws = XLSX.utils.json_to_sheet(data);
        const wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, ws, 'Detailed Report');

        const header = [
            ['SCO3251 - Detailed Report'],
            ['Date Range: 01-01-2024 To 01-02-2024'],
        ];

        const wsHeader = XLSX.utils.aoa_to_sheet(header, { origin: 'A1' });
        XLSX.utils.book_append_sheet(wb, wsHeader, 'Header');
        XLSX.writeFile(wb, 'detailed-report.xlsx');
    };

    return (
        <div className="main-content">
            <div className="header-container">
                <h1 className="detailed-title">SCO3251 - Detailed Report</h1>
                <div className="date-range">Date : 01-01-2024 To 01-02-2024</div>
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
            <div className="table-container-settings">
                <div className="table-responsive">
                    <table className="table table-bordered">
                        <thead>
                            <tr>
                                <th onClick={() => requestSort('srNo')}>
                                    Sr. No <FontAwesomeIcon icon={faSort} />
                                </th>
                                <th onClick={() => requestSort('eventType')}>
                                    Event Type <FontAwesomeIcon icon={faSort} />
                                </th>
                                <th onClick={() => requestSort('eventSubtype')}>
                                    Event Subtype <FontAwesomeIcon icon={faSort} />
                                </th>
                                <th onClick={() => requestSort('callDuration')}>
                                    Call Duration <FontAwesomeIcon icon={faSort} />
                                </th>
                                <th onClick={() => requestSort('reviewStatus')}>
                                    Review Status <FontAwesomeIcon icon={faSort} />
                                </th>
                                <th onClick={() => requestSort('callType')}>
                                    Call Type <FontAwesomeIcon icon={faSort} />
                                </th>
                                <th onClick={() => requestSort('sopScore')}>
                                    SOP QA Score <FontAwesomeIcon icon={faSort} />
                                </th>
                                <th onClick={() => requestSort('listeningScore')}>
                                    Listening QA Score <FontAwesomeIcon icon={faSort} />
                                </th>
                                <th onClick={() => requestSort('detailsCapturingScore')}>
                                    Capturing QA Score <FontAwesomeIcon icon={faSort} />
                                </th>
                                <th onClick={() => requestSort('addressTaggingScore')}>
                                    Address QA Score <FontAwesomeIcon icon={faSort} />
                                </th>
                                <th onClick={() => requestSort('handledTime')}>
                                    Handled Time <FontAwesomeIcon icon={faSort} />
                                </th>
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
                    </table>
                </div>
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
