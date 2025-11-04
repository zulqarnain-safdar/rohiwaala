import axios from 'axios';
import Swal from 'sweetalert2';

window.axios = axios;
window.Swal = Swal;

// Create a global alert function that uses SweetAlert2
window.alert = (message, title = 'RohiWala.pk', type = 'info') => {
  const config = {
    title: title,
    text: message,
    icon: type,
    confirmButtonText: 'OK',
    confirmButtonColor: '#10B981',
    customClass: {
      confirmButton: 'swal2-confirm-rohiwala'
    }
  };

  // Handle different types
  switch (type) {
    case 'success':
      config.icon = 'success';
      config.confirmButtonColor = '#10B981';
      break;
    case 'error':
      config.icon = 'error';
      config.confirmButtonColor = '#EF4444';
      break;
    case 'warning':
      config.icon = 'warning';
      config.confirmButtonColor = '#F59E0B';
      break;
    case 'info':
    default:
      config.icon = 'info';
      config.confirmButtonColor = '#3B82F6';
      break;
  }

  return Swal.fire(config);
};

// Create additional utility functions
window.showSuccess = (message, title = 'Success!') => {
  return window.alert(message, title, 'success');
};

window.showError = (message, title = 'Error!') => {
  return window.alert(message, title, 'error');
};

window.showWarning = (message, title = 'Warning!') => {
  return window.alert(message, title, 'warning');
};

window.showInfo = (message, title = 'Information') => {
  return window.alert(message, title, 'info');
};

// Create confirmation dialog
window.confirm = (message, title = 'Confirm Action') => {
  return Swal.fire({
    title: title,
    text: message,
    icon: 'question',
    showCancelButton: true,
    confirmButtonText: 'Yes',
    cancelButtonText: 'No',
    confirmButtonColor: '#10B981',
    cancelButtonColor: '#6B7280',
    customClass: {
      confirmButton: 'swal2-confirm-rohiwala',
      cancelButton: 'swal2-cancel-rohiwala'
    }
  });
};

// Configure axios defaults
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.headers.common['Accept'] = 'application/json';
// Don't set Content-Type here - let axios handle it automatically for FormData

// Set base URL for API calls
window.axios.defaults.baseURL = '/api';

// Add request interceptor to include auth token and CSRF token
window.axios.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem('token');
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }
    
    // Add CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (csrfToken) {
      config.headers['X-CSRF-TOKEN'] = csrfToken.getAttribute('content');
    }
    
    // Set Content-Type to application/json only if it's not FormData
    if (!(config.data instanceof FormData)) {
      config.headers['Content-Type'] = 'application/json';
    }
    
    return config;
  },
  (error) => {
    return Promise.reject(error);
  }
);

// Add response interceptor to handle auth errors
window.axios.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response?.status === 401) {
      // Only redirect if not already on login page and not a login request
      const currentPath = window.location.pathname;
      const isLoginRequest = error.config?.url?.includes('/auth/login');
      
      if (currentPath !== '/login' && !isLoginRequest) {
        // Token expired or invalid, redirect to login
        localStorage.removeItem('token');
        localStorage.removeItem('user');
        window.location.href = '/login';
      }
    }
    return Promise.reject(error);
  }
);
