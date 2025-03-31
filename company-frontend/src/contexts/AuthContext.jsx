import { createContext, useContext, useState, useEffect } from 'react';
import axios from 'axios';

const AuthContext = createContext();

export function AuthProvider({children}){
    const [user , setUser] = useState(null);
    const [loading , setLoading] = useState(true);


    //Initialize CSRF protection
    useEffect(()=> {
        axios.get('http://localhost:8000/sanctum/csrf-cookie');
        setLoading(false);
    } ,[]);

    const register = async (name, email, password) => {
        try {
            const { data } = await axios.post('http://localhost:8000/api/register', {
                name,
                email,
                password,
                password_confirmation: password
            });
            setUser(data.user);
            return data;
        } catch (error) {
            throw error.response.data;
        }
    };


    const login = async (email, password) => {
        try {
            const { data } = await axios.post('http://localhost:8000/api/login', {
                email,
                password
            });
            setUser(data.user);
            localStorage.setItem('auth_token', data.token);
            return data;
        } catch (error) {
            throw error.response.data;
        }
    };

    const logout = async () => {
        try {
            await axios.post('http://localhost:8000/api/logout', {}, {
                headers: {
                    Authorization: `Bearer ${localStorage.getItem('auth_token')}`
                }
            });
            localStorage.removeItem('auth_token');
            setUser(null);
        } catch (error) {
            console.error('Logout failed:', error);
        }
    };

    return (
        <AuthContext.Provider value={{ user, register, login, logout, loading }}>
            {!loading && children}
        </AuthContext.Provider>
    );
}





export const useAuth = () => useContext(AuthContext);

