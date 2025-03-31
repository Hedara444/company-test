import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import styles from './LoginPage.module.css';
import ErrorMessageBox from '../../../components/ErrorMessageBox/ErrorMessageBox.jsx';

const LoginPage = () => {
    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');
    const [errorMessage, setErrorMessage] = useState('');
    const navigate = useNavigate();
    const [isLoading, setIsLoading] = useState(false);


    const handleLogin = async (e) => {
        e.preventDefault();
        setErrorMessage('');
        setIsLoading(true);

        try {
            const response = await fetch('http://127.0.0.1:8000/api/auth/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ email, password }),
            });

            if (response.ok) {
                const data = await response.json();
                // Store the token in local storage or contexts
                localStorage.setItem('token', data.token);
                navigate('/inquiry'); // Redirect to Inquiry page
            } else {
                const errorData = await response.json();
                setErrorMessage(errorData.message || 'Login failed.');
            }
            // eslint-disable-next-line no-unused-vars
        } catch (error) {
            setErrorMessage('An error occurred while logging in.');
        }
        finally {
            setIsLoading(false);
        }
    };

    return (

            <div className={styles.container}>
                <h1 className={styles.title}>Welcome Back</h1>
                <form className={styles.form} onSubmit={handleLogin}>
                    <input
                        className={styles.input}
                        type="email"
                        placeholder="Email"
                        value={email}
                        onChange={(e) => setEmail(e.target.value)}
                        required
                    />
                    <input
                        className={styles.input}
                        type="password"
                        placeholder="Password"
                        value={password}
                        onChange={(e) => setPassword(e.target.value)}
                        required
                    />
                    <button
                        className={styles.button}
                        type="submit"
                        disabled={isLoading}
                    >
                        {isLoading ? (
                            <div className={styles.loadingContainer}>
                                <div className={styles.spinner}></div>
                                Logging In...
                            </div>
                        ) : (
                            'Login'
                        )}
                    </button>
                </form>
                <p className={styles.linkText}>
                    No account?{' '}
                    <a className={styles.link} href="/signup">
                        Sign Up
                    </a>
                </p>
                {errorMessage && <ErrorMessageBox message={errorMessage}/>}
            </div>


    );
};

export default LoginPage;