import axios from 'axios';

const API_BASE_URL = 'https://qhomesbackend.tfcmockup.com/api';

// Configure axios defaults
axios.defaults.baseURL = API_BASE_URL;
// Remove withCredentials to fix CORS issues
// axios.defaults.withCredentials = true;

// Set default headers for better compatibility
axios.defaults.headers.common['Accept'] = 'application/json';
axios.defaults.headers.common['Content-Type'] = 'application/json';

// Request interceptor to add auth token
axios.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem('auth_token');
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
  },
  (error) => {
    return Promise.reject(error);
  }
);

// Response interceptor to handle token expiration
axios.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response?.status === 401) {
      localStorage.removeItem('auth_token');
      window.location.href = '/login';
    }
    return Promise.reject(error);
  }
);

export const apiService = {
  // Auth endpoints
  register: async (userData) => {
    const response = await axios.post('/register', userData);
    if (response.data.access_token) {
      localStorage.setItem('auth_token', response.data.access_token);
    }
    return response.data;
  },

  login: async (credentials) => {
    const response = await axios.post('/login', credentials);
    if (response.data.access_token) {
      localStorage.setItem('auth_token', response.data.access_token);
    }
    return response.data;
  },

  logout: async () => {
    await axios.post('/logout');
    localStorage.removeItem('auth_token');
  },

  getUser: async () => {
    const response = await axios.get('/user');
    return response.data;
  },

  isAuthenticated: () => {
    return !!localStorage.getItem('auth_token');
  }
};

export default apiService;
