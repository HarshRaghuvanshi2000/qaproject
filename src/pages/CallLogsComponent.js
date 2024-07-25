import React, { useState, useEffect } from 'react';
import '../styles/CallLogsComponent.css';
import { FaPlay, FaPause } from 'react-icons/fa';
import AudioPlayer from 'react-h5-audio-player';
import 'react-h5-audio-player/lib/styles.css';
import '../App.css';


const sampleAudioFiles = Array.from({ length: 80 }, (_, index) => ({
    id: index + 1,
    username: `User${index + 1}`,
    eventType: 'Content',
    eventSubtype: 'Content',
    callDuration: '5:00',
    reviewStatus: 'Pending',
    src: `https://www.soundhelix.com/examples/mp3/SoundHelix-Song-${index % 16 + 1}.mp3`
}));

const itemsPerPage = 12;

const CallLogsComponent = () => {
    const [currentPage, setCurrentPage] = useState(1);
    const [currentAudio, setCurrentAudio] = useState(sampleAudioFiles[0].src);
    const [paginatedData, setPaginatedData] = useState([]);
    const [isPlaying, setIsPlaying] = useState(false);

    useEffect(() => {
        const indexOfLastItem = currentPage * itemsPerPage;
        const indexOfFirstItem = indexOfLastItem - itemsPerPage;
        setPaginatedData(sampleAudioFiles.slice(indexOfFirstItem, indexOfLastItem));
    }, [currentPage]);

    useEffect(() => {
        const audioElement = document.getElementById('audioPlayer');
        if (audioElement) {
            audioElement.play();
            setIsPlaying(true);
        }
    }, [currentAudio]);

    const handlePlayPause = (src) => {
        const audioElement = document.getElementById('audioPlayer');
        if (currentAudio === src) {
            if (isPlaying) {
                audioElement.pause();
            } else {
                audioElement.play();
            }
            setIsPlaying(!isPlaying);
        } else {
            setCurrentAudio(src);
            setIsPlaying(true);
        }
    };

    const renderPageNumbers = () => {
        const totalPages = Math.ceil(sampleAudioFiles.length / itemsPerPage);
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
                            {paginatedData.map((file) => (
                                <tr
                                    key={file.id}
                                    className={currentAudio === file.src ? 'playing' : ''}
                                >
                                    <td>{file.id}</td>
                                    <td>{file.username}</td>
                                    <td>{file.eventType}</td>
                                    <td>{file.eventSubtype}</td>
                                    <td>{file.callDuration}</td>
                                    <td>{file.reviewStatus}</td>
                                    <td>
                                        <button onClick={() => handlePlayPause(file.src)}>
                                            {currentAudio === file.src && isPlaying ? <FaPause /> : <FaPlay />}
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
                            id="audioPlayer"
                            src={currentAudio}
                            autoPlay
                            onPlay={() => setIsPlaying(true)}
                            onPause={() => setIsPlaying(false)}
                        />
                    </div>
                    <div className="call-information">
                        <table>
                            <tbody>
                                <tr>
                                    <td>Address:</td>
                                    <td>Panchkula</td>
                                </tr>
                                <tr>
                                    <td>Event Type:</td>
                                    <td>Accident</td>
                                </tr>
                                <tr>
                                    <td>Incident Time:</td>
                                    <td>12 PM</td>
                                </tr>
                                <tr>
                                    <td>Reported By:</td>
                                    <td>Mrunal</td>
                                </tr>
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
