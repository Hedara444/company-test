
import React from 'react';
import { BrowserRouter as Router, Routes, Route } from 'react-router-dom';
import InquiryPage from './pages/InquiryPage/InquiryPage.jsx';
import LoginPage from './pages/AuthPages/LoginPage/LoginPage.jsx';
import SignupPage from './pages/AuthPages/SignupPage/SignupPage.jsx';

const App = () => {
    return (
        <Router>
            <Routes>
                <Route path="/inquiry" element={<InquiryPage />} />
                <Route path="/login" element={<LoginPage />} />
                <Route path="/signup" element={<SignupPage />} />
                <Route path="/" element={<LoginPage />} /> {/* Redirect to loginPage by default */}
            </Routes>
        </Router>
    );
};

export default App;