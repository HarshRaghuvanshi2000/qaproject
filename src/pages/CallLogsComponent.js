import React, { useState, useEffect, useRef } from 'react';
import '../styles/CallLogsComponent.css';
import { FaPlay, FaPause } from 'react-icons/fa';
import AudioPlayer from 'react-h5-audio-player';
import 'react-h5-audio-player/lib/styles.css';
import '../App.css';
import { getCallData } from '../services/api'; // Import your API function

const itemsPerPage = 10;

const CallLogsComponent = () => {
    const [currentPage, setCurrentPage] = useState(1);
    const [currentAudio, setCurrentAudio] = useState(null);
    const [paginatedData, setPaginatedData] = useState([]);
    const [isPlaying, setIsPlaying] = useState(false);
    const [currentAudioIndex, setCurrentAudioIndex] = useState(0);
    const [callLogs, setCallLogs] = useState([]);
    const [currentLogDetails, setCurrentLogDetails] = useState(null);

    const audioPlayerRef = useRef(null);

    useEffect(() => {
        // Fetch data from the API
        const fetchCallData = async () => {
            try {
                const data = await getCallData('yourCallType', 'yourFromDate', 'yourToDate');
                setCallLogs(data);
                setCurrentAudio(data.length > 0 ? data[0].voice_path : null);
                paginateData(data);
            } catch (error) {
                console.error("Error fetching call data:", error);
            }
        };
        fetchCallData();
    }, []);

    useEffect(() => {
        paginateData(callLogs);
    }, [currentPage, callLogs]);

    useEffect(() => {
        if (audioPlayerRef.current && currentAudio) {
            audioPlayerRef.current.audio.current.play();
            setIsPlaying(true);
        }
    }, [currentAudio]);

    useEffect(() => {
        const totalPages = Math.ceil(callLogs.length / itemsPerPage);
        const newPage = Math.floor(currentAudioIndex / itemsPerPage) + 1;
        if (newPage <= totalPages) {
            setCurrentPage(newPage);
        }
    }, [currentAudioIndex, callLogs]);

    const paginateData = (data) => {
        const indexOfLastItem = currentPage * itemsPerPage;
        const indexOfFirstItem = indexOfLastItem - itemsPerPage;
        setPaginatedData(data.slice(indexOfFirstItem, indexOfLastItem));
    };

    const handlePlayPause = (file, index) => {
        if (currentAudio === file.voice_path) {
            if (isPlaying) {
                audioPlayerRef.current.audio.current.pause();
            } else {
                audioPlayerRef.current.audio.current.play();
            }
            setIsPlaying(!isPlaying);
        } else {
            setCurrentAudio(file.voice_path);
            setIsPlaying(true);
            setCurrentAudioIndex(index);
            setCurrentLogDetails(file); // Set the current log details here
        }
    };
    

    const handlePrev = () => {
        setCurrentAudioIndex((prevIndex) => {
            const newIndex = (prevIndex - 1 + callLogs.length) % callLogs.length;
            setCurrentAudio(callLogs[newIndex].voice_path);
            setCurrentPage(Math.floor(newIndex / itemsPerPage) + 1);
            return newIndex;
        });
    };

    const handleNext = () => {
        setCurrentAudioIndex((prevIndex) => {
            const newIndex = (prevIndex + 1) % callLogs.length;
            setCurrentAudio(callLogs[newIndex].voice_path);
            setCurrentPage(Math.floor(newIndex / itemsPerPage) + 1);
            return newIndex;
        });
    };

    const renderPageNumbers = () => {
        const totalPages = Math.ceil(callLogs.length / itemsPerPage);
        const pageNumbers = [];
        const startPage = Math.max(1, currentPage - 2);
        const endPage = Math.min(totalPages, currentPage + 2);

        if (currentPage > 1) {
            pageNumbers.push(<li key="prev" onClick={() => setCurrentPage(currentPage - 1)}>&laquo;</li>);
        }

        for (let i = startPage; i <= endPage; i++) {
            pageNumbers.push(
                <li key={i} className={i === currentPage ? 'active' : ''} onClick={() => setCurrentPage(i)}>
                    {i}
                </li>
            );
        }

        if (currentPage < totalPages) {
            pageNumbers.push(<li key="next" onClick={() => setCurrentPage(currentPage + 1)}>&raquo;</li>);
        }

        return pageNumbers;
    };

    return (
        <div className="main-content">
            <h1 className="call-logs-title">Actionable Call Logs</h1>
            <div className="call-logs-content">
                <div className="table-container">
                    <table className="table call-logs-table">
                        <thead>
                            <tr>
                                <th onClick={() => console.log("Sort Sr. No")}>Sr. No</th>
                                <th onClick={() => console.log("Sort Username")}>Username</th>
                                <th onClick={() => console.log("Sort Event Type")}>Event Type</th>
                                <th onClick={() => console.log("Sort Event Subtype")}>Event Subtype</th>
                                <th onClick={() => console.log("Sort Call Duration")}>Call Duration</th>
                                <th onClick={() => console.log("Sort Review Status")}>Review Status</th>
                                <th>Play</th>
                            </tr>
                        </thead>
                        <tbody>
                            {paginatedData.map((file, index) => (
                                <tr
                                    key={file.id}
                                    className={currentAudio === file.voice_path ? 'playing' : ''}
                                >
                                    <td>{index + 1 + (currentPage - 1) * itemsPerPage}</td>
                                    <td>{file.agent_name}</td>
                                    <td>{file.event_maintype}</td>
                                    <td>{file.event_subtype}</td>
                                    <td>{file.call_duration_millis}</td>
                                    <td>{file.review_status}</td>
                                    <td>
                                        <button onClick={() => handlePlayPause(file.voice_path)}>
                                            {currentAudio === file.voice_path && isPlaying ? <FaPause /> : <FaPlay />}
                                        </button>
                                    </td>
                                </tr>
                                ))}
                        </tbody>
                    </table>
                    <ul id="page-numbers">
                        {renderPageNumbers()}
                    </ul>
                </div>
                <div className="content-side">
                    <div className="audio-player-section">
                        <p>Audio title will come here </p>
                        <AudioPlayer
                            ref={audioPlayerRef}
                            src={currentAudio}
                            autoPlay
                            onPlay={() => setIsPlaying(true)}
                            onPause={() => setIsPlaying(false)}
                            showSkipControls
                            onClickPrevious={handlePrev}
                            onClickNext={handleNext}
                        />
                    </div>
                    <div className="call-information">
                        <table>
                            <tbody>
                                {currentLogDetails ? (
                                    <>
                                        <tr>
                                            <td>Address:</td>
                                            <td>{currentLogDetails.victim_address}</td> {/* Assuming 'address' is a property in your data */}
                                        </tr>
                                        <tr>
                                            <td>Event Type:</td>
                                            <td>{currentLogDetails.event_maintype}</td>
                                        </tr>
                                        <tr>
                                            <td>Incident Time:</td>
                                            <td>{currentLogDetails.incident_time}</td> {/* Assuming 'incident_time' is a property in your data */}
                                        </tr>
                                        <tr>
                                            <td>Reported By:</td>
                                            <td>{currentLogDetails.reported_by}</td> {/* Assuming 'reported_by' is a property in your data */}
                                        </tr>
                                    </>
                                ) : (
                                    <tr>
                                        <td colSpan="2">Select a call log to view details</td>
                                    </tr>
                                )}
                            </tbody>
                        </table>
                    </div>

                    <form className="questionnaire">
                        <h3 className="questionnaire-title">Questionnaire</h3>
                        <div className="question">
                            <label>1. Compliance of SOP</label>
                            <div className="options">
                                <label><input type="radio" name="q1" value="Poor" /> Poor</label>
                                <label><input type="radio" name="q1" value="Good" /> Good</label>
                                <label><input type="radio" name="q1" value="Excellent" /> Excellent</label>
                            </div>
                        </div>
                        <div className="question">
                            <label>2. Active listening & proper response</label>
                            <div className="options">
                                <label><input type="radio" name="q2" value="Poor" /> Poor</label>
                                <label><input type="radio" name="q2" value="Good" /> Good</label>
                                <label><input type="radio" name="q2" value="Excellent" /> Excellent</label>
                            </div>
                        </div>
                        <div className="question">
                            <label>3. Correct and relevant details capturing</label>
                            <div className="options">
                                <label><input type="radio" name="q3" value="Poor" /> Poor</label>
                                <label><input type="radio" name="q3" value="Good" /> Good</label>
                                <label><input type="radio" name="q3" value="Excellent" /> Excellent</label>
                            </div>
                        </div>
                        <div className="question">
                            <label>4. Correct address tagging</label>
                            <div className="options">
                                <label><input type="radio" name="q4" value="Poor" /> Poor</label>
                                <label><input type="radio" name="q4" value="Good" /> Good</label>
                                <label><input type="radio" name="q4" value="Excellent" /> Excellent</label>
                            </div>
                        </div>
                        <div className="question">
                            <label>5. Call handled time</label>
                            <div className="options">
                                <label><input type="radio" name="q5" value="Poor" /> Poor</label>
                                <label><input type="radio" name="q5" value="Good" /> Good</label>
                                <label><input type="radio" name="q5" value="Excellent" /> Excellent</label>
                            </div>
                        </div>
                        <div className="question">
                            <label>Remarks (Optional)</label>
                            <textarea rows="4"></textarea>
                        </div>
                        <div className="submit-container">
                            <button type="submit">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    );
};

export default CallLogsComponent;
