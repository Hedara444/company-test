// src/components/SubmitButton.jsx
import styles from './SubmitButton.module.css';

const SubmitButton = ({ onClick, disabled   , isLoading}) => {


    return (
        <button
            onClick={onClick}
            disabled={disabled || isLoading}
            className={styles.button}
        >
            {isLoading ? (
                <div className={styles.loadingContainer}>
                    <div className={styles.spinner}></div>
                    Submitting...
                </div>
            ) : (
                'Submit Inquiry'
            )}
        </button>
    );
};

export default SubmitButton;