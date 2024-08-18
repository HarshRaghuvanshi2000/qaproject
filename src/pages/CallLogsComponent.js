import React, { useState, useEffect, useRef } from 'react';
import '../styles/CallLogsComponent.css';
import { FaPlay, FaPause } from 'react-icons/fa';
import AudioPlayer from 'react-h5-audio-player';
import 'react-h5-audio-player/lib/styles.css';
import '../App.css';
import { getCallData, submitCoQaData } from '../services/api';

const itemsPerPage = 10;

const CallLogsComponent = () => {
    const [currentPage, setCurrentPage] = useState(1);
    const [currentAudio, setCurrentAudio] = useState(null);
    const [paginatedData, setPaginatedData] = useState([]);
    const [isPlaying, setIsPlaying] = useState(false);
    const [currentAudioIndex, setCurrentAudioIndex] = useState(0);
    const [callLogs, setCallLogs] = useState([]);
    const [currentLogDetails, setCurrentLogDetails] = useState(null);
    const [sopScore, setSopScore] = useState('');
    const [activeListeningScore, setActiveListeningScore] = useState('');
    const [releventDetailScore, setReleventDetailScore] = useState('');
    const [addressTaggingScore, setAddressTaggingScore] = useState('');
    const [callHandledTimeScore, setCallHandledTimeScore] = useState('');
    const [remarks, setRemarks] = useState('');
    const [startTime, setStartTime] = useState(null);

    const audioPlayerRef = useRef(null);

    useEffect(() => {
        const fetchCallData = async () => {
            try {
                const data = await getCallData('1', 'yourFromDate', 'yourToDate');
                setCallLogs(data);
                setCurrentAudio(null); // Do not autoplay the first audio
                setCurrentLogDetails(data.length > 0 ? data[0] : null); // Select first call log by default
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

    useEffect(() => {
        if (currentLogDetails) {
            // Clear form fields when currentLogDetails change
            setSopScore('');
            setActiveListeningScore('');
            setReleventDetailScore('');
            setAddressTaggingScore('');
            setCallHandledTimeScore('');
            setRemarks('');
            setStartTime(null);
        }
    }, [currentLogDetails]);
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
            setCurrentLogDetails(file); // Ensure this line updates the details correctly
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
    }; // 
    const handleSubmit = async (event) => {
        event.preventDefault();

        if (!currentLogDetails) {
            alert("Please select a call log to submit.");
            return;
        }

        const scoQaTime = calculateScoQaTime();

        const data = {
            signalId: currentLogDetails.signal_id,
            scoQaTime,
            sopScore,
            activeListeningScore,
            releventDetailScore,
            addressTaggingScore,
            callHandledTimeScore,
            scoEmployeeCode: "SCO1",
            scoRemarks: remarks,
        };

        try {
            const response = await submitCoQaData(data);
            console.log('Submission successful:', response);
            // Clear form fields after successful submission
            setSopScore('');
            setActiveListeningScore('');
            setReleventDetailScore('');
            setAddressTaggingScore('');
            setCallHandledTimeScore('');
            setRemarks('');
        } catch (error) {
            console.error('Submission failed:', error);
        }
    };

    const calculateScoQaTime = () => {
        const endTime = new Date();
        return Math.floor((endTime - startTime) / 1000).toString(); // Calculate time in seconds
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
                                    <td>{file.event_maintype}</td>
                                    <td>{file.event_subtype}</td>
                                    <td>{file.call_duration_millis}</td>
                                    <td>{file.review_status}</td>
                                    <td>
                                        <button onClick={() => handlePlayPause(file, index)}>
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
                        <p>Audio title will come here</p>
                        <AudioPlayer
                            ref={audioPlayerRef}
                            src={currentAudio}
                            showSkipControls
                            onClickPrevious={handlePrev}
                            onClickNext={handleNext}
                            onPause={() => setIsPlaying(false)}
                            autoPlay={false}
                            onPlay={() => setStartTime(new Date())}
                        />
                    </div>
                    <div className="call-information">
                        <table>
                            <tbody>
                                {currentLogDetails ? (
                                    <>
                                        <tr>
                                            <td>Address:</td>
                                            <td>{currentLogDetails.victim_address}</td>
                                        </tr>
                                        <tr>
                                            <td>Event Type:</td>
                                            <td>{currentLogDetails.event_maintype}</td>
                                        </tr>
                                        <tr>
                                            <td>Incident Time:</td>
                                            <td>{currentLogDetails.incident_time}</td>
                                        </tr>
                                        <tr>
                                            <td>Reported By:</td>
                                            <td>{currentLogDetails.reported_by}</td>
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


                    <form className="questionnaire" onSubmit={handleSubmit}>
                        <h3 className="questionnaire-title">Questionnaire</h3>
                        <div className="question">
                            <label>1. Compliance of SOP</label>
                            <div className="options">
                                <label><input type="radio" name="q1" value="1" checked={sopScore === '1'} onChange={(e) => setSopScore(e.target.value)} /> Poor</label>
                                <label><input type="radio" name="q1" value="2" checked={sopScore === '2'} onChange={(e) => setSopScore(e.target.value)} /> Good</label>
                                <label><input type="radio" name="q1" value="3" checked={sopScore === '3'} onChange={(e) => setSopScore(e.target.value)} /> Excellent</label>
                            </div>
                        </div>
                        <div className="question">
                            <label>2. Active listening & proper response</label>
                            <div className="options">
                                <label><input type="radio" name="q2" value="1" checked={activeListeningScore === '1'} onChange={(e) => setActiveListeningScore(e.target.value)} /> Poor</label>
                                <label><input type="radio" name="q2" value="2" checked={activeListeningScore === '2'} onChange={(e) => setActiveListeningScore(e.target.value)} /> Good</label>
                                <label><input type="radio" name="q2" value="3" checked={activeListeningScore === '3'} onChange={(e) => setActiveListeningScore(e.target.value)} /> Excellent</label>
                            </div>
                        </div>
                        <div className="question">
                            <label>3. Correct and relevant details capturing</label>
                            <div className="options">
                                <label><input type="radio" name="q3" value="1" checked={releventDetailScore === '1'} onChange={(e) => setReleventDetailScore(e.target.value)} /> Poor</label>
                                <label><input type="radio" name="q3" value="2" checked={releventDetailScore === '2'} onChange={(e) => setReleventDetailScore(e.target.value)} /> Good</label>
                                <label><input type="radio" name="q3" value="3" checked={releventDetailScore === '3'} onChange={(e) => setReleventDetailScore(e.target.value)} /> Excellent</label>
                            </div>
                        </div>
                        <div className="question">
                            <label>4. Correct address tagging</label>
                            <div className="options">
                                <label><input type="radio" name="q4" value="1" checked={addressTaggingScore === '1'} onChange={(e) => setAddressTaggingScore(e.target.value)} /> Poor</label>
                                <label><input type="radio" name="q4" value="2" checked={addressTaggingScore === '2'} onChange={(e) => setAddressTaggingScore(e.target.value)} /> Good</label>
                                <label><input type="radio" name="q4" value="3" checked={addressTaggingScore === '3'} onChange={(e) => setAddressTaggingScore(e.target.value)} /> Excellent</label>
                            </div>
                        </div>
                        <div className="question">
                            <label>5. Call handled time</label>
                            <div className="options">
                                <label><input type="radio" name="q5" value="1" checked={callHandledTimeScore === '1'} onChange={(e) => setCallHandledTimeScore(e.target.value)} /> Poor</label>
                                <label><input type="radio" name="q5" value="2" checked={callHandledTimeScore === '2'} onChange={(e) => setCallHandledTimeScore(e.target.value)} /> Good</label>
                                <label><input type="radio" name="q5" value="3" checked={callHandledTimeScore === '3'} onChange={(e) => setCallHandledTimeScore(e.target.value)} /> Excellent</label>
                            </div>
                        </div>
                        <div className="question">
                            <label>Remarks (Optional)</label>
                            <textarea rows="4" value={remarks} onChange={(e) => setRemarks(e.target.value)} ></textarea>
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
