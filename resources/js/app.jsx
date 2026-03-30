import React from 'react';
import ReactDOM from 'react-dom/client';
import DashboardApp from './components/DashboardApp';

const appElement = document.getElementById('app');

if (appElement) {
    ReactDOM.createRoot(appElement).render(
        <React.StrictMode>
            <DashboardApp />
        </React.StrictMode>
    );
}