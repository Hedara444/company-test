import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import styles from './SignupPage.module.css';
import ErrorMessageBox from '../../../components/ErrorMessageBox/ErrorMessageBox.jsx';


const SignupPage = () => {
    const [name, setName] = useState('');
    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');
    const [passwordConfirmation, setPasswordConfirmation] = useState(''); // New state for password confirmation
    const [errorMessage, setErrorMessage] = useState('');
    const navigate = useNavigate();

    const handleSignup = async (e) => {
        e.preventDefault();
        setErrorMessage('');

        // Check if passwords match
        if (password !== passwordConfirmation) {
            setErrorMessage('Passwords do not match.');
            return;
        }

        try {
            const response = await fetch('http://127.0.0.1:8000/api/auth/register', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ name, email, password, password_confirmation: passwordConfirmation }), // Include password confirmation
            });

            if (response.ok) {
                navigate('/login'); // Redirect to Login page after successful signup
            } else {
                const errorData = await response.json();
                setErrorMessage(errorData.message || 'Signup failed.');
            }
            // eslint-disable-next-line no-unused-vars
        } catch (error) {
            setErrorMessage('An error occurred while signing up.');
        }
    };

    return (
        <div className={styles.container}>
            <h1 className={styles.title}>Create Account</h1>
            <form className={styles.form} onSubmit={handleSignup}>
                <input
                    className={styles.input}
                    type="text"
                    placeholder="Name"
                    autoComplete="name"
                    value={name}
                    onChange={(e) => setName(e.target.value)}
                    required
                />
                <input
                    className={styles.input}
                    type="email"
                    placeholder="Email"
                    autoComplete="email"
                    autoCapitalize="off"
                    autoCorrect="off"
                    value={email}
                    onChange={(e) => setEmail(e.target.value)}
                    required

                />
                <input
                    className={styles.input}
                    type="password"
                    placeholder="Password"
                    autoComplete="new-password"
                    value={password}
                    onChange={(e) => setPassword(e.target.value)}
                    required
                />
                <input
                    className={styles.input}
                    type="password"
                    placeholder="Confirm Password"
                    value={passwordConfirmation}
                    onChange={(e) => setPasswordConfirmation(e.target.value)}
                    required
                />
                <button className={styles.button} type="submit">
                    Create Account
                </button>
            </form>
            <p className={styles.linkText}>
                Already have an account?{' '}
                <a className={styles.link} href="/login">
                    Login
                </a>
            </p>
            {errorMessage && <ErrorMessageBox message={errorMessage} />}
        </div>
    );
};

export default SignupPage;