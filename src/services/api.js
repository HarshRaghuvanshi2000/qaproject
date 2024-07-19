// src/services/api.js

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
