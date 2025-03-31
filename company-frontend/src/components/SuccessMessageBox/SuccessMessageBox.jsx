import React, { useState, useEffect } from 'react';
import styles from './SuccessMessageBox.module.css';



const SuccessMessageBox = ({ message , onDismiss }) => {
    const [isVisible, setIsVisible] = useState(true);

    useEffect(() => {
        // Auto-dismiss after 3 seconds
        const timer = setTimeout(() => {
            setIsVisible(false);
        }, 3000);

        return () => clearTimeout(timer);
    }, []);


    useEffect(() => {
        if (!isVisible) {
            // Wait for exit animation to finish before calling onDismiss
            const timer = setTimeout(onDismiss, 500);
            return () => clearTimeout(timer);
        }
    }, [isVisible, onDismiss]);

    return (
        <div className={`${styles.notification} ${isVisible ? styles.visible : ''}`}>
            {message}
        </div>
    );

};

export default SuccessMessageBox;