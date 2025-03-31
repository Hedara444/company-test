import React, { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import CompanyList from '../../components/CompanyList/CompanyList.jsx';
import SubmitButton from '../../components/SubmitButton/SubmitButton.jsx';
import SuccessMessageBox from '../../components/SuccessMessageBox/SuccessMessageBox.jsx';
import ErrorMessageBox from '../../components/ErrorMessageBox/ErrorMessageBox.jsx';
import useCompanies from '../../hooks/useCompanies.js';
import styles from './InquiryPage.module.css';
import axios from "axios";

const InquiryPage = () => {
    const [name, setName] = useState('');
    const [email, setEmail] = useState('');
    const [phone, setPhone] = useState('');
    const [companyId, setCompanyId] = useState(null);
    const [message, setMessage] = useState('');
    const [successMessage, setSuccessMessage] = useState('');
    const [errorMessage, setErrorMessage] = useState('');
    const { companies, fetchCompanies, isLoading, error, hasMore,currentPage } = useCompanies();
    const navigate = useNavigate();
    const [submitButtonIsLoading , setSubmitButtonIsLoading ] = useState(false);
    const [showCompanyList, setShowCompanyList] = useState(false);
    useEffect(() => {
        const token = localStorage.getItem('token');
        if (!token) {
            navigate('/login'); // Redirect to loginPage if not authenticated
        } else {
           setShowCompanyList(true);
        }
    }, [navigate]);

    const handleSubmit = async (e) => {
        e.preventDefault();
        setSuccessMessage('');
        setErrorMessage('');
        setSubmitButtonIsLoading(true);
        if (!name || !email || !phone || !companyId || !message) {
            setErrorMessage('All fields are required.');
            setSubmitButtonIsLoading(false);
            return;
        }

        const inquiryData = {
            name,
            email,
            phone,
            companyId,
            message,
        };

        try {

            setSubmitButtonIsLoading(true);
            const response = await axios.post(
                'http://127.0.0.1:8000/api/inquiries',
                inquiryData,
                {
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${localStorage.getItem('token')}`,
                    },
                }
            );

            if (response.status === 201) {
                setSuccessMessage('Inquiry submitted successfully!');
                // Reset form fields
                setName('');
                setEmail('');
                setPhone('');
                setCompanyId(null);
                setMessage('');
                setSubmitButtonIsLoading(false);

            }
        } catch (error) {
            if (error.response) {
                if (error.response.status === 422) {
                    setErrorMessage(error.response.data.error|| 'Validation error.');
                } else {
                    setErrorMessage('An error occurred. Please try again.');
                }
            } else {
                setErrorMessage('Network error. Please check your connection.');
            }
        }
        finally {
            setSubmitButtonIsLoading(false);
        }
    };
    const handleSelectCompanyClick = async () => {
        if (companies.length === 0 && !isLoading) {
            await fetchCompanies();
        }
        setShowCompanyList(true);
    };
    return (
        <div className={styles.container}>
            <h2>Submit Your Inquiry</h2>
            <form onSubmit={handleSubmit}>
                {/* Main Form Layout */}
                <div className={styles.formLayout}>
                    {/* Left Column - Inputs */}
                    <div className={styles.inputColumn}>
                        <input
                            type="text"
                            placeholder="Name"
                            className={styles.input}
                            value={name}
                            onChange={(e) => setName(e.target.value)}
                            required
                        />

                        <input
                            type="email"
                            placeholder="Email"
                            className={styles.input}
                            value={email}
                            onChange={(e) => setEmail(e.target.value)}
                            required
                        />

                        <input
                            type="tel"
                            pattern="[+0-9\-]*"
                            placeholder="Phone ex: +963-958111222"
                            className={styles.input}
                            value={phone}
                            onChange={(e) => setPhone(e.target.value)}
                            required
                        />

                        {/* Company Selection */}
                        <div className={styles.formGroup}>
                            {!companyId ? (
                                <button
                                    type="button"
                                    onClick={handleSelectCompanyClick}
                                    className={styles.selectButton}
                                    disabled={isLoading}
                                >
                                    {isLoading ? 'Loading Companies...' : 'Select a Company'}
                                </button>
                            ) : (
                                <div className={styles.selectedCompanyDisplay}>
                                    Selected: {companies.find(c => c.id === companyId)?.name}
                                </div>
                            )}

                            {showCompanyList && !companyId && (
                                <div className={styles.scrollContainer}>
                                    {error && <div className={styles.errorText}>{error}</div>}
                                    <CompanyList

                                        companies={companies}
                                        onSelect={(id) => {
                                            setCompanyId(id);
                                            setShowCompanyList(false);
                                        }}
                                        selectedCompanyId={companyId}
                                        hasMore={hasMore}
                                        isLoading={isLoading}
                                        fetchNextPage={() => fetchCompanies(currentPage + 1)}
                                    />
                                </div>
                            )}
                        </div>
                    </div>

                    {/* Right Column - Message */}
                    <div className={styles.textareaColumn}>
                        <div className={styles.textareaColumn}>
                    <textarea
                        placeholder="Your message..."
                        className={styles.textarea}
                        value={message}
                        onChange={(e) => setMessage(e.target.value)}
                        required
                    />
                        </div>
                    </div>

                </div>

                {/* Submit Button */}
                <SubmitButton
                    onClick={handleSubmit}
                    disabled={!name || !email || !phone || !companyId || !message}
                    isLoading={submitButtonIsLoading}  // Add this prop
                />
            </form>

            {/* Messages */}
            {successMessage && (
                <SuccessMessageBox
                    message={successMessage}
                    onDismiss={() => setSuccessMessage('')}
                />
            )}

            {errorMessage && (
                <ErrorMessageBox
                    message={errorMessage}
                    onDismiss={() => setErrorMessage('')}
                />
            )}
        </div>
    );
};

export default InquiryPage;