import React, { useState, useEffect } from 'react';
import { useLocation } from 'react-router-dom';
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
import { getScoDetailedData } from '../services/api';

const DetailedReport = () => {
    const [currentPage, setCurrentPage] = useState(1);
    const [itemsPerPage] = useState(12);
    const [sortConfig, setSortConfig] = useState({ key: null, direction: 'ascending' });
    const [data, setData] = useState([]);

    const location = useLocation();

    const getQueryParams = () => {
        const searchParams = new URLSearchParams(location.search);
        return {
            scoEmployeeCode: searchParams.get('scoEmployeeCode'),
            startDate: searchParams.get('startDate'),
            endDate: searchParams.get('endDate'),
        };
    };

    useEffect(() => {
        const { scoEmployeeCode, startDate, endDate } = getQueryParams();

        const fetchData = async () => {
            try {
                const result = await getScoDetailedData(scoEmployeeCode, startDate, endDate);
                setData(result);
            } catch (error) {
                console.error('Failed to fetch detailed report data:', error);
            }
        };

        fetchData();
    }, [location.search]);

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
        doc.text('SCO Detailed Report', 14, 22);
        doc.setFontSize(12);
        doc.text(`Date: ${getQueryParams().startDate} To ${getQueryParams().endDate}`, 14, 30);

        autoTable(doc, {
            startY: 40,
            head: [['SCO Employee Code', 'CO Name', 'CO Employee Code', 'SOP Score', 'Active Listening Score', 'Relevant Detail Score', 'Address Tagging Score', 'Call Handled Time Score', 'SCO QA Time', 'SCO Remarks']],
            body: data.map(item => [
                item.sco_employee_code,
                item.co_name,
                item.co_employee_code,
                item.sop_score,
                item.active_listening_score,
                item.relevent_detail_score,
                item.address_tagging_score,
                item.call_handled_time_score,
                item.sco_qa_time,
                item.sco_remarks
            ])
        });

        doc.save('detailed-report.pdf');
    };

    const handleExportExcel = () => {
        const ws = XLSX.utils.json_to_sheet(data);
        const wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, ws, 'Detailed Report');
        XLSX.writeFile(wb, 'detailed-report.xlsx');
    };

    return (
        <div className="main-content">
            <div className="header-container">
                <h1 className="detailed-title">SCO Detailed Report</h1>
                <div className="date-range">Date: {getQueryParams().startDate} To {getQueryParams().endDate}</div>
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
                                <th onClick={() => requestSort('sco_employee_code')}>
                                    SCO Employee Code <FontAwesomeIcon icon={faSort} />
                                </th>
                                <th onClick={() => requestSort('co_name')}>
                                    CO Name <FontAwesomeIcon icon={faSort} />
                                </th>
                                <th onClick={() => requestSort('co_employee_code')}>
                                    CO Employee Code <FontAwesomeIcon icon={faSort} />
                                </th>
                                <th onClick={() => requestSort('sop_score')}>
                                    SOP Score <FontAwesomeIcon icon={faSort} />
                                </th>
                                <th onClick={() => requestSort('active_listening_score')}>
                                    Active Listening Score <FontAwesomeIcon icon={faSort} />
                                </th>
                                <th onClick={() => requestSort('relevent_detail_score')}>
                                    Relevant Detail Score <FontAwesomeIcon icon={faSort} />
                                </th>
                                <th onClick={() => requestSort('address_tagging_score')}>
                                    Address Tagging Score <FontAwesomeIcon icon={faSort} />
                                </th>
                                <th onClick={() => requestSort('call_handled_time_score')}>
                                    Call Handled Time Score <FontAwesomeIcon icon={faSort} />
                                </th>
                                <th onClick={() => requestSort('sco_qa_time')}>
                                    SCO QA Time <FontAwesomeIcon icon={faSort} />
                                </th>
                                <th onClick={() => requestSort('sco_remarks')}>
                                    SCO Remarks <FontAwesomeIcon icon={faSort} />
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            {currentItems.map((item, index) => (
                                <tr key={index}>
                                    <td>{item.sco_employee_code}</td>
                                    <td>{item.co_name}</td>
                                    <td>{item.co_employee_code}</td>
                                    <td>{item.sop_score}</td>
                                    <td>{item.active_listening_score}</td>
                                    <td>{item.relevent_detail_score}</td>
                                    <td>{item.address_tagging_score}</td>
                                    <td>{item.call_handled_time_score}</td>
                                    <td>{item.sco_qa_time}</td>
                                    <td>{item.sco_remarks}</td>
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
