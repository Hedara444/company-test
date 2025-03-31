import React, { useState, useEffect } from 'react';
import styles from './ErrorMessageBox.module.css';

const ErrorMessageBox = ({ message ,onDismiss }) => {

    const [isVisible , setIsVisible] = useState(true);
    useEffect(()=>{
        const timer = setTimeout(() => {
            setIsVisible(false);
        },3000);

        return () => clearTimeout(timer);
        },[]);

    useEffect(() => {
        if (!isVisible) {
            // Wait for exit animation to finish before calling onDismiss
            const timer = setTimeout(onDismiss, 500);
            return () => clearTimeout(timer);
        }
    }, [isVisible, onDismiss]);

    return (
        <div className={`${styles.notification}`}>
            {message}
        </div>
    );
};

export default ErrorMessageBox;