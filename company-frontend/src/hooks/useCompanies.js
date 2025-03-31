import { useState } from 'react';


const useCompanies = () => {
    const [companies, setCompanies] = useState([]);
    const [isLoading, setIsLoading] = useState(false);
    const [error, setError] = useState(null);
    const [currentPage, setCurrentPage] = useState(1);
    const [totalPages, setTotalPages] = useState(1);
    const [hasMore, setHasMore] = useState(true);

    const fetchCompanies = async (page = 1) => {
        if (!hasMore || isLoading) return;

        setIsLoading(true);
        setError(null);
        try {
            const response = await fetch(`http://127.0.0.1:8000/api/companies?page=${page}`, {
                headers: {
                    'Authorization': `Bearer ${localStorage.getItem('token')}`,
                },
            });

            if (!response.ok) throw new Error('Failed to fetch companies');

            const data = await response.json();
            setCompanies(prev => [...prev, ...data.data]);
            setCurrentPage(page);
            setTotalPages(data.meta.last_page);
            setHasMore(data.meta.current_page < data.meta.last_page);
        } catch (err) {
            setError(err.message);
        } finally {
            setIsLoading(false);
        }
    };

    return { companies, fetchCompanies, isLoading, error, hasMore, currentPage, totalPages };
};

export default useCompanies;