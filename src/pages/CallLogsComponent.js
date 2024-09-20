import React, { useState, useEffect, useRef } from 'react';
import '../styles/CallLogsComponent.css';
import { FaPlay, FaPause, FaBackward, FaForward } from 'react-icons/fa'; // Add necessary icons
import AudioPlayer from 'react-h5-audio-player';
import 'react-h5-audio-player/lib/styles.css';
import '../App.css';
import { getCallData, submitCoQaData } from '../services/api';
import '../styles/SuccessPopup.css';
import { useLocation } from 'react-router-dom';
import InfoPopup from '../components/InfoPopup'; // Import the InfoPopup component

const AUDIO_BASE_URL = 'http://10.26.0.8:8080/ACDSAdmin-1.2/AudioDownloadServlet?absoluteFileName='
const WS_BASE_URL ='ws://localhost:3000';
// const WS_BASE_URL ='ws://10.26.0.19:3001';

const itemsPerPage = 11;

const CallLogsComponent = () => {
  const location = useLocation();
  const queryParams = new URLSearchParams(location.search);
  const signalTypeId = queryParams.get('signalTypeId');
  const signalType = queryParams.get('signalType');
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
  const [infoPopupOpen, setInfoPopupOpen] = useState(false);
  const [infoPopupContent, setInfoPopupContent] = useState(null);
  const [popupPosition, setPopupPosition] = useState({ top: '50%', left: '50%' });
  const [dragging, setDragging] = useState(false);
  const [offset, setOffset] = useState({ x: 0, y: 0 });
  const infoPopupRef = useRef(null);
  const [showSuccessMessage, setShowSuccessMessage] = useState(false);
  const [Submitting, setSubmitting] = useState(false);
    const [socket, setSocket] = useState(null);
    const [showErrorMessage, setShowErrorMessage] = useState(false);
    const [errorMessage, setErrorMessage] = useState('');
    const userName = localStorage.getItem('fullName');
    const userEmployeeCode = localStorage.getItem('employeeCode');
    const employeeCode = localStorage.getItem('username');; // Assign a unique user ID to identify the user
    const [currentCallId, setCurrentCallId] = useState(null); // Track the currently reviewed call

    const formatDuration = (durationMillis) => {
        if (durationMillis == null) return null;
        const totalSeconds = Math.floor(durationMillis / 1000);
        const minutes = Math.floor(totalSeconds / 60);
        const seconds = totalSeconds % 60;
        return `${minutes} Min ${seconds} Sec`;
    };
    const displayValue= (value) => {
        return value != null && value !== '' ? value : 'N/A';
      }
      
  const audioPlayerRef = useRef(null);

  useEffect(() => {
    const handleBeforeUnload = () => {
      if (currentLogDetails && currentLogDetails.review_status === 'Pending' && isPlaying) {
        socket.send(JSON.stringify({
          type: 'UPDATE_STATUS',
          userId: employeeCode,
          callId: currentLogDetails.signal_id,
          status: 'Pending', // Update the call status to "Pending" on page refresh or navigation
        }));
      }
    };
  
    // Attach the event listener for beforeunload
    window.addEventListener('beforeunload', handleBeforeUnload);
  
    // Clean up the event listener when component unmounts
    return () => {
      window.removeEventListener('beforeunload', handleBeforeUnload);
    };
  }, [currentLogDetails, isPlaying, socket, employeeCode]);
  // Step 1: Fetch call data before establishing WebSocket connection
  useEffect(() => {
      const fetchCallData = async () => {
          try {
              const data = await getCallData(signalTypeId, 'yourFromDate', 'yourToDate');
              setCallLogs(data);

              // Initialize WebSocket connection after data is loaded
              initializeWebSocket();

              const pendingLog = data.find(log => log.review_status === 'Pending');
              if (pendingLog) {
                  setCurrentLogDetails(pendingLog); // Select first pending call log by default
              }
          } catch (error) {
              console.error("Error fetching call data:", error);
          }
      };

      if (signalTypeId) {
          fetchCallData();
      }
  }, [signalTypeId]);

  // Step 2: Initialize WebSocket connection
  const initializeWebSocket = () => {
      const ws = new WebSocket(WS_BASE_URL);
      setSocket(ws);

      ws.onopen = () => {
          console.log('WebSocket Client Connected');
      };

      ws.onmessage = (event) => {
          const message = JSON.parse(event.data);
              handleWebSocketMessage(message);
      };

      ws.onclose = () => {
          console.log('WebSocket Client Disconnected');
      };
  };

  // Step 3: Handle WebSocket messages
  const handleWebSocketMessage = (message) => {

    if (message.type === 'INITIAL_CALL_STATUSES') {
        console.log(message);
        // Initial setup to load all the current call statuses
        setCallLogs((prevLogs) =>
          prevLogs.map((log) => {
            const callStatus = message.callStatuses[log.signal_id];
            if (callStatus) {
              // If the userId matches, the user sees 'Pending' when they are reviewing
              if (callStatus.userId === employeeCode && callStatus.status === 'Being Reviewed') {
                return { ...log, review_status: 'Pending' };
              } else if (callStatus.status === 'Being Reviewed') {
                // Other users see 'Being Reviewed'
                return { ...log, review_status: 'Being Reviewed' };
              } else {
                // For any other status (like 'Pending'), apply it directly
                return { ...log, review_status: callStatus.status };
              }
            }
            return log;
          })
        );
      }
      if (message.type === 'STATUS_UPDATE' ) {
        console.log(message);
        console.log('aagya');

          setCallLogs((prevLogs) =>
              prevLogs.map((log) => {
                  if (log.signal_id === message.callId) {

                      // If the employeeCode matches, only the current user should see 'Being Reviewed'
                      if (message.userId === employeeCode && message.status === 'Being Reviewed') {
                          return { ...log, review_status: 'Pending' };
                      } else if (message.status === 'Being Reviewed') {

                          // Other users see 'Pending' for the same call if it's reviewed by another user
                          return { ...log, review_status: 'Being Reviewed' };
                      } 
                      else if (message.status === 'Pending'){
                        return { ...log, review_status: 'Pending' };
                      }
                      else {
                          // For 'Pending' status, apply the message directly
                          return { ...log, review_status: message.status };
                      }
                  }
                  return log;
              })
          );
      }
  };

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
    if (file.review_status === 'Completed' || file.review_status === 'Being Reviewed') {
      return;
    }

    if (
        currentCallId && 
        currentCallId !== file.signal_id && 
        callLogs &&
        !callLogs.some(log => log.signal_id === currentCallId && log.review_status === 'Completed') // Check if currentCallId is not 'Completed'
    ) {
        socket.send(
            JSON.stringify({
                type: 'UPDATE_STATUS',
                userId: employeeCode,
                callId: currentCallId,
                status: 'Pending', // Mark the previous call as 'Pending' for all users
            })
        );
    }

    setCurrentCallId(file.signal_id);


    if (!file.voice_path) {
      setErrorMessage('No audio file found');
      setShowErrorMessage(true);
      setTimeout(() => setShowErrorMessage(false), 3000); // Hide the popup after 3 seconds
    } else {
      setErrorMessage(''); // Clear the error message if audio path is valid
    }
    if (currentAudio === AUDIO_BASE_URL + (file.voice_path)) {
      if (isPlaying) {
        audioPlayerRef.current.audio.current.pause();
      } else {
        audioPlayerRef.current.audio.current.play();
      }
      setIsPlaying(!isPlaying);
    } else {
      setCurrentAudio(AUDIO_BASE_URL + (file.voice_path));
      setIsPlaying(true);
      setCurrentAudioIndex(index);
      setCurrentLogDetails(file);
      setCurrentCallId(file.signal_id);

      // Notify other users that this call is being reviewed
      socket.send(JSON.stringify({ 
        type: 'UPDATE_STATUS', 
        userId: employeeCode, 
        callId: file.signal_id, 
        status: 'Being Reviewed' 
      }));
};
  };
  

    const handleInfoClick = (file) => {
        if (file.review_status === 'Completed' || file.review_status === 'Being Reviewed') {
            // Prevent access if review status is "Completed" or "Being Reviewed"
            return;
        }
        setInfoPopupContent(file);
        setInfoPopupOpen(true);
    };


  const handlePrev = () => {
    setCurrentAudioIndex((prevIndex) => {
      const newIndex = (prevIndex - 1 + callLogs.length) % callLogs.length;
      setCurrentAudio(AUDIO_BASE_URL + (callLogs[newIndex].voice_path));
      return newIndex;
    });
  };

  const handleNext = () => {
    setCurrentAudioIndex((prevIndex) => {
      const newIndex = (prevIndex + 1) % callLogs.length;
      setCurrentAudio(AUDIO_BASE_URL + (callLogs[newIndex].voice_path));
      return newIndex;
    });
    }; // 
    const handleSubmit = async (event) => { 
        event.preventDefault();
        setSubmitting(true);
    
        // Check if all required fields are selected, making 3rd and 4th options optional based on signalTypeId
        if (!sopScore || !activeListeningScore || !callHandledTimeScore ||
            (signalTypeId === '1' && (!releventDetailScore || !addressTaggingScore))) {
            setErrorMessage("Please select all required fields before submitting");
            setShowErrorMessage(true);
            setTimeout(() => setShowErrorMessage(false), 3000);
            setSubmitting(false);
            return;
            }
    

        const scoQaTime = calculateScoQaTime();

        const data = {
            signalId: currentLogDetails.signal_id,
            scoQaTime,
            sopScore,
            activeListeningScore,
            releventDetailScore: signalTypeId === '1' ? releventDetailScore : null,
            addressTaggingScore: signalTypeId === '1' ? addressTaggingScore : null,
            callHandledTimeScore,
            scoEmployeeCode: userEmployeeCode,
            scoName: userName,
            scoRemarks: remarks,
        };
    
        try {
            const response = await submitCoQaData(data);
            console.log('Submission successful:', response);
    
            // Show success popup
            setShowSuccessMessage(true);

            // Update the state instead of reloading the page
            setCallLogs(callLogs.map(log =>
                log.signal_id === currentLogDetails.signal_id ? { ...log, review_status: 'Completed' } : log
            ));
    
            socket.send(JSON.stringify({ type: 'SUBMIT_STATUS', userId: employeeCode, callId: currentLogDetails.signal_id, status: 'Completed' }));

            // Clear form fields after successful submission
            setSopScore('');
            setActiveListeningScore('');
            setReleventDetailScore('');
            setAddressTaggingScore('');
            setCallHandledTimeScore('');
            setRemarks('');
        } catch (error) {
            console.error('Submission failed:', error);
        } finally {
            setSubmitting(false); // Re-enable the submit button
            setTimeout(() => {
                setShowSuccessMessage(false);
            }, 3000);
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
    const handlePopupDragStart = (e) => {
        if (infoPopupRef.current) {
            const rect = infoPopupRef.current.getBoundingClientRect();
            setOffset({
                x: e.clientX - rect.left,
                y: e.clientY - rect.top,
            });
            setDragging(true);
        }
    };

    const handlePopupDrag = (e) => {
        if (dragging) {
            setPopupPosition({
                top: e.clientY - offset.y,
                left: e.clientX - offset.x,
            });
        }
    };

    const handlePopupDragEnd = () => {
        setDragging(false);
    };

    // Inside InfoPopup component

    const handleInfoPopupClose = () => {
        setInfoPopupOpen(false);
    };

    return (
        <div className="main-content">
            <h1 className="call-logs-title">{signalType}</h1>
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
                                <th>Details</th>
                            </tr>
                        </thead>
                        <tbody>
    {paginatedData.map((file, index) => (
        <tr
            key={file.id}
            className={`${
                file.review_status === 'Completed' || file.review_status === 'Being Reviewed'
                    ? 'shaded'
                    : currentAudio === AUDIO_BASE_URL + file.voice_path
                    ? 'playing'
                    : ''
            }`}
        >
            <td>{index + 1 + (currentPage - 1) * itemsPerPage}</td>
            <td>{displayValue(file.event_maintype)}</td>
            <td>{displayValue(file.event_subtype)}</td>
            <td>{displayValue(formatDuration(file.call_duration_millis))}</td>
            <td
                style={{
                    color:
                        file.review_status === 'Completed'
                            ? '#006400' // Dark green for "Completed"
                            : file.review_status === 'Pending'
                            ? '#eca02d' // Orange for "Pending"
                            : 'inherit',
                    fontWeight: 'bold',
                }}
            >
                {displayValue(file.review_status)}
            </td>
            <td>
                <button
                    onClick={() => handlePlayPause(file, index)}
                    disabled={file.review_status === 'Completed' || file.review_status === 'Being Reviewed'} // Disable button if status is "Completed" or "Being Reviewed"
                >
                    {currentAudio === AUDIO_BASE_URL + file.voice_path && isPlaying ? <FaPause /> : <FaPlay />}
                </button>
            </td>
            <td>
                <button
                    onClick={() => handleInfoClick(file)}
                    disabled={file.review_status === 'Completed' || file.review_status === 'Being Reviewed'} // Disable button if status is "Completed" or "Being Reviewed"
                >
                    Info
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
                        <strong><p>Audio Player</p></strong>
                        <AudioPlayer
                            ref={audioPlayerRef}
                            src={currentAudio}
                            showSkipControls
                            customIcons={{
                                play: <FaPlay />,
                                pause: <FaPause />,
                                previous: <FaBackward />,  // Local icon for "previous"
                                next: <FaForward />,       // Local icon for "next"
                            }}
                            onClickPrevious={handlePrev}
                            onClickNext={handleNext}
                            onPause={() => setIsPlaying(false)}
                            autoPlay={false}
                            onPlay={() => setStartTime(new Date())}
                            onError={(error) => {
                                setErrorMessage('Audio file not accessible on this device');
                                setShowErrorMessage(true);
                                setTimeout(() => setShowErrorMessage(false), 3000); // Hide after 3 seconds
                            }}
                            onEnded={() => setIsPlaying(false)}
                        />
                    </div>
                    <div className="call-information">
                        <table>
                            <tbody>
                                {currentLogDetails ? (
                                    <>
                                        <tr>
                                            <td>Event Type:</td>
                                            <td>{displayValue(currentLogDetails.event_maintype)}</td>
                                        </tr>
                                        <tr>
                                            <td>Event Subtype:</td>
                                            <td>{displayValue(currentLogDetails.event_subtype)}</td>
                                        </tr>
                                        <tr>
                                            <td>Call Duration:</td>
                                            <td>{displayValue(formatDuration(currentLogDetails.call_duration_millis))}</td>
                                        </tr>
                                        <tr>
                                            <td>Additional Info:</td>
                                            <td>{displayValue(currentLogDetails.addl_info)}</td>
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
                                <label>
                                    <input type="radio" name="q3" value="1" checked={releventDetailScore === '1'} onChange={(e) => setReleventDetailScore(e.target.value)} disabled={signalTypeId !== '1'} /> Poor
                                </label>
                                <label>
                                    <input type="radio" name="q3" value="2" checked={releventDetailScore === '2'} onChange={(e) => setReleventDetailScore(e.target.value)} disabled={signalTypeId !== '1'} /> Good
                                </label>
                                <label>
                                    <input type="radio" name="q3" value="3" checked={releventDetailScore === '3'} onChange={(e) => setReleventDetailScore(e.target.value)} disabled={signalTypeId !== '1'} /> Excellent
                                </label>
                            </div>
                        </div>
                        <div className="question">
                            <label>4. Correct address capturing</label>
                            <div className="options">
                                <label>
                                    <input type="radio" name="q4" value="1" checked={addressTaggingScore === '1'} onChange={(e) => setAddressTaggingScore(e.target.value)} disabled={signalTypeId !== '1'} /> Poor
                                </label>
                                <label>
                                    <input type="radio" name="q4" value="2" checked={addressTaggingScore === '2'} onChange={(e) => setAddressTaggingScore(e.target.value)} disabled={signalTypeId !== '1'} /> Good
                                </label>
                                <label>
                                    <input type="radio" name="q4" value="3" checked={addressTaggingScore === '3'} onChange={(e) => setAddressTaggingScore(e.target.value)} disabled={signalTypeId !== '1'} /> Excellent
                                </label>
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
                    {showErrorMessage && (
                        <div className="error-popup">
                            <p>{errorMessage}</p>
                        </div>
                    )}
                    {showSuccessMessage && (
                        <div className="alert alert-success" role="alert">
                            Successfully updated!
                        </div>
                    )}
                    <InfoPopup
                        isOpen={infoPopupOpen}
                        onClose={handleInfoPopupClose}
                        logDetails={infoPopupContent}
                        position={popupPosition}
                        onDragStart={handlePopupDragStart}
                        onDrag={handlePopupDrag}
                        onDragEnd={handlePopupDragEnd}
                        ref={infoPopupRef}
                    />
                </div>
            </div>
        </div>
    );
};

export default CallLogsComponent;