import React from 'react';
import styles from './CompanyList.module.css';

const CompanyList = ({ companies, onSelect, selectedCompanyId, hasMore, isLoading, fetchNextPage }) => {
    const handleScroll = (e) => {
        const { scrollTop, clientHeight, scrollHeight } = e.currentTarget;
        const threshold = 50;

        if (scrollHeight - (scrollTop + clientHeight) < threshold && hasMore && !isLoading) {
            fetchNextPage();
        }
    };

    return (
        <div className={styles.scrollContainer} onScroll={handleScroll}>
            {selectedCompanyId ? (
                <div className={styles.selectedCompany}>
                    Selected Company: {companies.find(company => company.id === selectedCompanyId)?.name}
                </div>
            ) : (
                <ul className={styles.companyList}>
                    {companies.map((company) => (
                        <li
                            key={company.id}
                            onClick={() => onSelect(company.id)}
                            className={styles.companyItem}
                            onMouseEnter={(e) => e.currentTarget.style.backgroundColor = '#38983c'}
                            onMouseLeave={(e) => e.currentTarget.style.backgroundColor = '#292929'}
                        >
                            {company.name}
                        </li>
                    ))}
                </ul>
            )}
            {isLoading && <div>Loading more companies...</div>}
        </div>
    );
};

export default CompanyList;