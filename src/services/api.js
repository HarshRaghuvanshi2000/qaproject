const BASE_URL = 'https://fakestoreapi.com/auth';

export const login = async (username, password) => {
    const url = `${BASE_URL}/login`;
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

// New function to fetch co-qa-data based on date range
export const getCoQaDataByDateRange = async (startDate, endDate,reportType) => {
    const url = `http://localhost:3000/api/users/co-qa-data?startDate=${startDate}&endDate=${endDate}`;

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

export const getCallData = async (signalType, fromDate,toDate) => {
    const url = `http://localhost:3000/api/users/call-data?signalType=${signalType}`;

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
// Inside services/api.js
export const submitCoQaData = async (data) => {
    const response = await fetch('http://localhost:3000/api/users/create-coqa-data', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data),
    });

    if (!response.ok) {
        throw new Error('Network response was not ok');
    }

    return response.json();
};
export const getCallSummary = async () => {
    const url = `http://localhost:3000/api/users/call-summary`;

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

// api.js

export const getSignalTypes = async () => {
    const url = 'http://localhost:3000/api/users/signal-types';

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
    const url = 'http://localhost:3000/api/users/signal-types';

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
