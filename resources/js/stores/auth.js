import { defineStore } from 'pinia'

export const useAuthStore = defineStore('auth', {
  state: () => {
    let user = null
    try {
      const userData = localStorage.getItem('user')
      if (userData && userData !== 'null') {
        user = JSON.parse(userData)
      }
    } catch (error) {
      console.error('Error parsing user data from localStorage:', error)
      localStorage.removeItem('user')
    }
    
    return {
      token: localStorage.getItem('token'),
      user: user,
      isAuthenticated: !!localStorage.getItem('token'),
      isLoggingOut: false
    }
  },

  getters: {
    isLoggedIn: (state) => !!state.token,
    userRole: (state) => state.user?.role,
    isAdmin: (state) => state.user?.role === 'admin',
    isFarmer: (state) => state.user?.role === 'farmer',
    isTechnician: (state) => state.user?.role === 'technician'
  },

  actions: {
    async login(credentials) {
      try {
        const response = await window.axios.post('/auth/login', credentials)
        
        if (response.data.success) {
          this.token = response.data.token
          this.user = response.data.user
          this.isAuthenticated = true
          
          // Store in localStorage
          localStorage.setItem('token', this.token)
          localStorage.setItem('user', JSON.stringify(this.user))
          
          // Set default axios header
          window.axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`
          
          return { success: true, data: response.data }
        } else {
          // Check if verification is required
          if (response.data.requires_verification) {
            return {
              success: false,
              message: response.data.message,
              requires_verification: true,
              user_id: response.data.user_id
            }
          }
          
          return { 
            success: false, 
            message: response.data.message || 'Login failed' 
          }
        }
      } catch (error) {
        console.error('Login error:', error)
        
        // Check if verification is required in error response
        if (error.response?.data?.requires_verification) {
          return {
            success: false,
            message: error.response.data.message,
            requires_verification: true,
            user_id: error.response.data.user_id
          }
        }
        
        return { 
          success: false, 
          message: error.response?.data?.message || 'Login failed' 
        }
      }
    },

    async requestPasswordResetOtp(mobile) {
      try {
        const response = await window.axios.post('/auth/forgot-password/request-otp', { mobile })
        return {
          success: !!response.data.success,
          message: response.data.message,
          userId: response.data.user_id
        }
      } catch (error) {
        return {
          success: false,
          message: error.response?.data?.message || 'Failed to send OTP'
        }
      }
    },

    async verifyPasswordResetOtp(userId, otp) {
      try {
        const response = await window.axios.post('/auth/forgot-password/verify-otp', { user_id: userId, otp })
        return {
          success: !!response.data.success,
          message: response.data.message
        }
      } catch (error) {
        return {
          success: false,
          message: error.response?.data?.message || 'Invalid or expired code'
        }
      }
    },

    async resetPasswordWithOtp(userId, otp, password, passwordConfirmation) {
      try {
        const response = await window.axios.post('/auth/forgot-password/reset', {
          user_id: userId,
          otp,
          password,
          password_confirmation: passwordConfirmation
        })
        return {
          success: !!response.data.success,
          message: response.data.message
        }
      } catch (error) {
        return {
          success: false,
          message: error.response?.data?.message || 'Failed to reset password',
          errors: error.response?.data?.errors
        }
      }
    },

    async register(userData) {
      try {
        const response = await window.axios.post('/auth/register', userData)
        
        if (response.data.success) {
          // Check if OTP verification is required
          if (response.data.requires_verification) {
            return { 
              success: true, 
              message: response.data.message,
              data: response.data,
              requiresVerification: true,
              userId: response.data.user_id,
              mobile: userData.mobile
            }
          } else {
            // Direct login after registration (if no OTP required)
            this.token = response.data.token
            this.user = response.data.user
            this.isAuthenticated = true
            
            // Store in localStorage
            localStorage.setItem('token', this.token)
            localStorage.setItem('user', JSON.stringify(this.user))
            
            // Set default axios header
            window.axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`
            
            // Set profile completion flag for new users
            localStorage.setItem('showProfileCompletionAlert', 'true')
            
            return { 
              success: true, 
              message: 'Registration successful',
              data: response.data,
              redirectTo: this.getProfileRedirectPath()
            }
          }
        }
      } catch (error) {
        console.error('Registration error:', error)
        return { 
          success: false, 
          message: error.response?.data?.message || 'Registration failed',
          errors: error.response?.data?.errors
        }
      }
    },

    async verifyOtp(userId, otp) {
      try {
        const response = await window.axios.post('/auth/verify-otp', {
          user_id: userId,
          otp: otp
        })
        
        if (response.data.success) {
          this.token = response.data.token
          this.user = response.data.user
          this.isAuthenticated = true
          
          // Store in localStorage
          localStorage.setItem('token', this.token)
          localStorage.setItem('user', JSON.stringify(this.user))
          
          // Set default axios header
          window.axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`
          
          // Set profile completion flag for new users
          localStorage.setItem('showProfileCompletionAlert', 'true')
          
          return { 
            success: true, 
            message: response.data.message,
            data: response.data,
            redirectTo: this.getProfileRedirectPath()
          }
        } else {
          return { 
            success: false, 
            message: response.data.message || 'OTP verification failed'
          }
        }
      } catch (error) {
        console.error('OTP verification error:', error)
        return { 
          success: false, 
          message: error.response?.data?.message || 'OTP verification failed'
        }
      }
    },

    async resendOtp(userId) {
      try {
        const response = await window.axios.post('/auth/resend-otp', {
          user_id: userId
        })
        
        return { 
          success: response.data.success, 
          message: response.data.message || 'Failed to resend OTP'
        }
      } catch (error) {
        console.error('Resend OTP error:', error)
        return { 
          success: false, 
          message: error.response?.data?.message || 'Failed to resend OTP'
        }
      }
    },


    getProfileRedirectPath() {
      if (this.user?.role === 'farmer') {
        return '/farmer/dashboard?tab=profile'
      } else if (this.user?.role === 'technician') {
        return '/technician/dashboard?tab=profile'
      } else if (this.user?.role === 'admin') {
        return '/admin/dashboard'
      }
      return '/dashboard'
    },


    async logout() {
      // Prevent multiple simultaneous logout calls
      if (this.isLoggingOut) {
        return
      }
      
      this.isLoggingOut = true
      
      try {
        if (this.token) {
          await window.axios.post('/auth/logout', {}, {
            headers: { Authorization: `Bearer ${this.token}` }
          })
        }
      } catch (error) {
        console.error('Logout error:', error)
      } finally {
        // Clear state and localStorage
        this.token = null
        this.user = null
        this.isAuthenticated = false
        this.isLoggingOut = false
        
        localStorage.removeItem('token')
        localStorage.removeItem('user')
        
        // Remove axios header
        delete window.axios.defaults.headers.common['Authorization']
      }
    },

    async fetchUser() {
      try {
        if (!this.token) return false
        
        const response = await window.axios.get('/auth/me', {
          headers: { Authorization: `Bearer ${this.token}` }
        })
        
        if (response.data.success) {
          this.user = response.data.user
          localStorage.setItem('user', JSON.stringify(this.user))
          return true
        }
      } catch (error) {
        console.error('Fetch user error:', error)
        // If token is invalid, logout
        this.logout()
        return false
      }
    },

    initializeAuth() {
      if (this.token) {
        // Set axios header
        window.axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`
        
        // Fetch fresh user data
        this.fetchUser()
      }
    }
  }
})