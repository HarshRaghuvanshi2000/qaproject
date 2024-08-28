const BASE_URL = process.env.REACT_APP_API_BASE_URL;
export const login = async (username, password) => {
    const url = `https://fakestoreapi.com/auth/auth/login`;
    const body = JSON.stringify({ username, password });

    try {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body,
        });

        if (!response.ok) {
            throw new Error('Login failed');
        }

        const data = await response.json();
        return data;
    } catch (error) {
        console.error('Login error:', error);
        throw error;
    }
};

export const getCoQaDataByDateRange = async (reportType, startDate, endDate) => {
    const url = `${BASE_URL}/co-qa-data?reportType=${reportType}&startDate=${startDate}&endDate=${endDate}`;
    console.log(url);

    try {
        const response = await fetch(url, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
            },
        });
        console.log(response);

        if (!response.ok) {
            throw new Error('Failed to fetch co-qa data');
        }

        const data = await response.json();
        return data;
    } catch (error) {
        console.error('API error:', error);
        throw error;
    }
};

export const getCallData = async (signalTypeId, fromDate, toDate) => {
    const url = `${BASE_URL}/call-data?signalType=${signalTypeId}`;
    console.log(url);

    try {
        const response = await fetch(url, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
            },
        });
        console.log(response);

        if (!response.ok) {
            throw new Error('Failed to fetch call data');
        }

        const data = await response.json();
        return data;
    } catch (error) {
        console.error('API error:', error);
        throw error;
    }
};
export const submitCoQaData = async (data) => {
    const url = `${BASE_URL}/create-coqa-data`;

    try {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data),
        });

        if (!response.ok) {
            throw new Error('Network response was not ok');
        }

        return await response.json();
    } catch (error) {
        console.error('API error:', error);
        throw error;
    }
};

export const getCallSummary = async () => {
    const url = `${BASE_URL}/call-summary`;

    try {
        const response = await fetch(url, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
            },
        });
        console.log(response);

        if (!response.ok) {
            throw new Error('Failed to fetch call summary');
        }

        const data = await response.json();
        return data;
    } catch (error) {
        console.error('API error:', error);
        throw error;
    }
};

export const getSignalTypes = async () => {
    const url = `${BASE_URL}/signal-types`;

    try {
        const response = await fetch(url, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
            },
        });

        if (!response.ok) {
            throw new Error('Failed to fetch signal types');
        }

        const data = await response.json();
        return data;
    } catch (error) {
        console.error('API error:', error);
        throw error;
    }
};

export const updateSignalType = async (signalTypeId, percentageOfCallsQa, maximumLimit) => {
    const url = `${BASE_URL}/signal-types`;

    const body = JSON.stringify({
        signal_type_id: signalTypeId,
        percentage_of_calls_qa: percentageOfCallsQa,
        maximum_limit: maximumLimit,
    });

    try {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body,
        });

        if (!response.ok) {
            throw new Error('Failed to update signal type');
        }

        return await response.json();
    } catch (error) {
        console.error('API error:', error);
        throw error;
    }
};
export const getScoDetailedData = async (scoEmployeeCode, startDate, endDate) => {
    const url = `${BASE_URL}/sco-detailed-data?scoEmployeeCode=${scoEmployeeCode}&startDate=${startDate}&endDate=${endDate}`;
    console.log(url);

    try {
        const response = await fetch(url, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
            },
        });
        console.log(response);

        if (!response.ok) {
            throw new Error('Failed to fetch detailed report data');
        }

        const data = await response.json();
        return data;
    } catch (error) {
        console.error('API error:', error);
        throw error;
    }
};