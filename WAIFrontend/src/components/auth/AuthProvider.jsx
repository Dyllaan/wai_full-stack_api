import { createContext, useState, useEffect } from 'react';
import PropTypes from 'prop-types';
import { useNavigate } from 'react-router-dom';
import * as api from './api'; 
import Message from '../Message';

/**
 * 
 * Handles the user context and rehydration of the user
 * @author Louis Figes
 * @generated GitHub Copilot was used in the creation of this code.
 * 
 */

export const AuthContext = createContext();

export default function AuthProvider ({children}) {
  const [user, setUser] = useState(null);
  const [signedIn, setSignedIn] = useState(false);
  const [accessToken, setAccessToken] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [success, setSuccess] = useState(null);
  const navigate = useNavigate();

  useEffect(() => {
    checkToken();
  }, [loading]);

  function clearError() {
    setError(null);
  }

  function clearSuccess() {
    setSuccess(null);
  }

  function handleError(message) {
    signOut();
    switch(message) {
      case "Token expired":
        setError("Your session has expired. Please sign in again.");
        break;
      case "Invalid token":
        setError("Your session is invalid. Please sign in again.");
        break;
      default:
        setError("An error occurred. Please sign in again.");
        break;
    }
    navigate("/login");
  }

  async function checkToken() {
    if(loading && localStorage.getItem('token') !== null && signedIn === false) {
      const token = localStorage.getItem('token');
      await currentUser(token);
    } else {
      setLoading(false);
    }
  }

  async function currentUser(token) {
    const response = await api.get('current-user', { Authorization: `Bearer ${token}` });
    if(response.success) {
      setUserFromResponse(response.data);
    } else {
      handleError(response.data.message);
    }
  }
  

  const login = async(username, password) => {
    const encodedString = btoa(username + ':' + password);
    const response = await api.get('token', { Authorization: `Basic ${encodedString}` });
    if (response.success) {
      setSuccess("You have successfully logged in.");
      setUserFromResponse(response.data);
    } else {
      signOut();
      setError(response.data.message);
    }
  }
  
  function setUserFromResponse(response) {
    localStorage.setItem('token', response.jwt);
    setAccessToken(response.jwt);
    setUser(response.user);
    setSignedIn(true);
    setLoading(false);
    setError(null);
  }

  /**
   * use formdata
   */
  const register = async(name, email, password) => {
    const formData = new FormData();
    formData.append('name', name);
    formData.append('email', email);
    formData.append('password', password);
    const response = await api.post('current-user', formData);
    if(response.success) {
      setUserFromResponse(response.data);
      setSuccess("You have successfully registered.");
    } else {
      setError(response.data.message);
    }
  }

  function signOut() {
    localStorage.removeItem('token');
    setSignedIn(false);
    setUser(null);
    setAccessToken(null);
    setLoading(false);
  }

  const editProfile = async(data) => {
    const headers = {
      'Content-Type': 'application/json',
      'Authorization': `Bearer ${accessToken}`
    };
    
    const response = await api.put('current-user', JSON.stringify(data), headers);
    if(response.success) {
      setSuccess("Profile updated successfully.");
      setUserFromResponse(response.data);
      return response.data.message;
    } else {
      setError(response.data.message);
      return false;
    }
  }

  async function addNote($text, $contentId) {
    const formData = new FormData();
    formData.append('text', $text);
    formData.append('content_id', $contentId);
    const response = await api.post('notes/', formData, { Authorization: `Bearer ${accessToken}` });
    if(response.success) {
      setSuccess("Note: " + $text + " added successfully.");
      return response.data;
    } else {
      setError(response.data.message);
      return false;
    }
  }

  async function deleteNote(noteId) {
    const response = await api.del(`notes/?note_id=${noteId}`, {}, { Authorization: `Bearer ${accessToken}` });
    if (response.success) {
      setSuccess("Note deleted successfully.");
      return response.data;
    } else {
      setError(response.data.message);
      return false;
    }
  }

  async function getFavourites() {
    const response = await api.get('favourites/', { Authorization: `Bearer ${accessToken}` });
    if(response.success) {
      return response.data;
    } else {
      setError(response.data.message);
      return false;
    }
  }

  async function isFavourited(contentId) {
    const response = await api.get(`favourites/?content_id=${contentId}`, { Authorization: `Bearer ${accessToken}` });
    if(response.success && response.data.favourite_id) {
      return true;
    } else {
      return false;
    }
  }

  async function deleteFavourite(contentId) {
    const response = await api.del(`favourites/?content_id=${contentId}`, {}, { Authorization: `Bearer ${accessToken}` });
    if(response.success) {
      setSuccess("Favourite deleted successfully.");
      return response.data;
    } else {
      setError(response.data.message);
      return false;
    }
  }

  async function addFavourite(contentId) {
    const formData = new FormData();
    formData.append('content_id', contentId);
    const response = await api.post('favourites/', formData, { Authorization: `Bearer ${accessToken}` });
    if(response.success) {
      setSuccess("Favourite added successfully.");
      return response.data;
    } else {
      setError(response.data.message);
      return false;
    }
  }

  AuthProvider.propTypes = {
    children: PropTypes.node.isRequired,
  };

  function displayError() {
    if(error) {
      return <Message message={error} clearMessage={clearError} type={'error'} />
    }
  }

  function displaySuccess() {
    if(success) {
      return <Message message={success} clearMessage={clearSuccess} type={'success'} />
    }
  }

  function displayMessages() {
    return (
      <>
        {displayError()}
        {displaySuccess()}
      </>
    );
  }

  return (
    <AuthContext.Provider value={{ signedIn, accessToken, user, login, signOut, loading, register, editProfile, displayMessages, addNote, deleteNote, getFavourites, isFavourited, addFavourite, deleteFavourite  }}>
      {children}
    </AuthContext.Provider>
  );
}