<template>
  <div v-if="show" class="fixed inset-0 z-50 overflow-y-auto">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" @click="closeModal"></div>
    
    <!-- Modal -->
    <div class="flex min-h-full items-center justify-center p-4">
      <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-md transform transition-all">
        <!-- Close Button -->
        <button @click="closeModal" class="absolute top-4 right-4 p-2 text-gray-400 hover:text-gray-600 transition-colors">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
          </svg>
        </button>

        <!-- Modal Content -->
        <div class="p-8">
          <!-- Header -->
          <div class="text-center mb-8">
            <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
              <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
              </svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Customer Registration</h2>
            <p class="text-gray-600">Sign up to continue with your order</p>
            <div class="mt-2 px-3 py-2 bg-blue-50 border border-blue-200 rounded-lg">
              <p class="text-sm text-blue-800">
                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Only customer accounts can be created here.
              </p>
            </div>
          </div>

          <!-- OTP Verification Section -->
          <div v-if="showOtpVerification" class="space-y-6">
            <div class="text-center">
              <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
              </div>
              <h3 class="text-lg font-semibold text-gray-900 mb-2">Verify Your Phone</h3>
              <p class="text-sm text-gray-600">
                We've sent a verification code to<br>
                <span class="font-semibold text-green-600">{{ formattedMobile }}</span>
              </p>
            </div>

            <!-- OTP Input -->
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-3">
                Enter Verification Code
              </label>
              <div class="flex justify-center space-x-3">
                <input
                  v-for="(digit, index) in otpDigits"
                  :key="index"
                  :ref="`otpInput${index}`"
                  v-model="otpDigits[index]"
                  type="text"
                  maxlength="1"
                  class="w-12 h-12 text-center text-xl font-bold border-2 border-gray-300 rounded-lg focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-all duration-200"
                  :class="{ 'border-red-500': hasOtpError }"
                  @input="handleOtpInput(index, $event)"
                  @keydown="handleOtpKeydown(index, $event)"
                  @paste="handleOtpPaste"
                  autocomplete="off"
                />
              </div>
              <div v-if="hasOtpError" class="mt-2 text-sm text-red-600 text-center">
                {{ otpErrorMessage }}
              </div>
            </div>

            <!-- Timer and Resend -->
            <div class="text-center">
              <div v-if="timeLeft > 0" class="text-sm text-gray-600 mb-2">
                Resend code in {{ formatTime(timeLeft) }}
              </div>
              <button
                v-else
                type="button"
                @click="handleResendOtp"
                :disabled="resending"
                class="text-sm text-green-600 hover:text-green-700 font-semibold disabled:opacity-50 disabled:cursor-not-allowed"
              >
                {{ resending ? 'Sending...' : 'Resend Code' }}
              </button>
            </div>

            <!-- Verify Button -->
            <button
              @click="handleVerifyOtp"
              :disabled="!isOtpComplete || verifying"
              class="w-full bg-green-600 text-white py-3 px-4 rounded-xl font-semibold hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200"
            >
              {{ verifying ? 'Verifying...' : 'Verify & Continue' }}
            </button>

            <!-- Back to Registration -->
            <div class="text-center">
              <button
                @click="handleBackToRegistration"
                class="text-sm text-gray-600 hover:text-gray-800 font-medium"
              >
                ← Back to Registration
              </button>
            </div>
          </div>

          <!-- Register Form -->
          <form v-else @submit.prevent="handleRegister" class="space-y-6">
            <!-- Full Name -->
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">Full Name</label>
              <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                  </svg>
                </div>
                <input
                  v-model="form.name"
                  type="text"
                  required
                  class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                  placeholder="Enter your full name"
                />
              </div>
            </div>

            <!-- Mobile Number -->
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">Mobile Number</label>
              <FlagSelector 
                v-model="form.mobile"
                input-id="mobile"
                input-name="mobile"
                input-type="tel"
                :required="true"
                placeholder="3001234567"
              />
            </div>

            <!-- Email -->
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">Email (Optional)</label>
              <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                  </svg>
                </div>
                <input
                  v-model="form.email"
                  type="email"
                  class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                  placeholder="your@email.com"
                />
              </div>
            </div>

            <!-- Password -->
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
              <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                  </svg>
                </div>
                <input
                  v-model="form.password"
                  :type="showPassword ? 'text' : 'password'"
                  required
                  class="w-full pl-10 pr-12 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                  placeholder="Create a password"
                />
                <button
                  type="button"
                  @click="showPassword = !showPassword"
                  class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600"
                >
                  <svg v-if="showPassword" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                  </svg>
                  <svg v-else class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                  </svg>
                </button>
              </div>
            </div>

            <!-- Confirm Password -->
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">Confirm Password</label>
              <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                  </svg>
                </div>
                <input
                  v-model="form.password_confirmation"
                  :type="showConfirmPassword ? 'text' : 'password'"
                  required
                  class="w-full pl-10 pr-12 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                  placeholder="Confirm your password"
                />
                <button
                  type="button"
                  @click="showConfirmPassword = !showConfirmPassword"
                  class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600"
                >
                  <svg v-if="showConfirmPassword" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                  </svg>
                  <svg v-else class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                  </svg>
                </button>
              </div>
            </div>

            <!-- Error Message -->
            <div v-if="error" class="bg-red-50 border border-red-200 rounded-xl p-4">
              <div class="flex">
                <svg class="w-5 h-5 text-red-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-sm text-red-600">{{ error }}</p>
              </div>
            </div>

            <!-- Register Button -->
            <button
              type="submit"
              :disabled="loading"
              :class="[
                'w-full py-3 rounded-xl font-semibold text-lg transition-all duration-300 transform hover:scale-105 relative overflow-hidden',
                loading
                  ? 'bg-gray-400 cursor-not-allowed'
                  : 'bg-gradient-to-r from-green-600 to-emerald-600 text-white shadow-lg hover:shadow-xl'
              ]"
            >
              <span v-if="loading" class="flex items-center justify-center">
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Creating Account...
              </span>
              <span v-else>Create Account</span>
            </button>

            <!-- Login Link -->
            <div class="text-center">
              <p class="text-gray-600">
                Already have an account?
                <button @click="switchToLogin" class="text-green-600 hover:text-green-700 font-semibold transition-colors">
                  Sign in here
                </button>
              </p>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, reactive, computed, onMounted, onUnmounted } from 'vue'
import FlagSelector from '@/components/FlagSelector.vue'

export default {
  name: 'RegisterModal',
  components: { FlagSelector },
  props: {
    show: {
      type: Boolean,
      default: false
    }
  },
  emits: ['close', 'register-success', 'switch-to-login'],
  setup(props, { emit }) {
    const form = reactive({
      name: '',
      mobile: '',
      email: '',
      password: '',
      password_confirmation: ''
    })
    const loading = ref(false)
    const error = ref('')
    const showPassword = ref(false)
    const showConfirmPassword = ref(false)
    
    // OTP Verification state
    const showOtpVerification = ref(false)
    const otpDigits = ref(['', '', '', '', '', ''])
    const verifying = ref(false)
    const resending = ref(false)
    const hasOtpError = ref(false)
    const otpErrorMessage = ref('')
    const timeLeft = ref(300) // 5 minutes in seconds
    const timer = ref(null)
    const pendingUserId = ref(null)

    const handleRegister = async () => {
      loading.value = true
      error.value = ''

      // Validate passwords match
      if (form.password !== form.password_confirmation) {
        error.value = 'Passwords do not match'
        loading.value = false
        return
      }

      try {
        const response = await window.axios.post('/auth/register', {
          name: form.name,
          mobile: form.mobile,
          email: form.email,
          password: form.password,
          password_confirmation: form.password_confirmation,
          role: 'farmer' // Force role to be farmer for store registration
        })

        if (response.data.success) {
          // Check if OTP verification is required
          if (response.data.requires_verification) {
            // Show OTP verification screen
            pendingUserId.value = response.data.user_id
            showOtpVerification.value = true
            startTimer()
            return
          }

          // If no verification required (debug mode), proceed with login
          if (response.data.user.role !== 'farmer') {
            error.value = 'Registration failed. Only customer accounts can be created through this portal.'
            return
          }

          // Store token and user data
          localStorage.setItem('token', response.data.token)
          localStorage.setItem('user', JSON.stringify(response.data.user))
          
          // Emit success event
          emit('register-success', response.data.user)
          
          // Close modal
          emit('close')
          
          // Show success message
          window.showSuccess('Account created successfully! Welcome to our platform!')
        } else {
          error.value = response.data.message || 'Registration failed'
        }
      } catch (err) {
        console.error('Registration error:', err)
        error.value = err.response?.data?.message || 'Registration failed. Please try again.'
      } finally {
        loading.value = false
      }
    }

    // Computed properties
    const formattedMobile = computed(() => {
      if (!form.mobile) return ''
      // Format mobile number for display
      const mobile = form.mobile.replace(/\D/g, '')
      if (mobile.startsWith('92')) {
        return `+${mobile}`
      } else if (mobile.startsWith('0')) {
        return `+92${mobile.substring(1)}`
      }
      return `+92${mobile}`
    })

    const isOtpComplete = computed(() => {
      return otpDigits.value.every(digit => digit !== '')
    })

    // OTP Methods
    const formatTime = (seconds) => {
      const mins = Math.floor(seconds / 60)
      const secs = seconds % 60
      return `${mins}:${secs.toString().padStart(2, '0')}`
    }

    const startTimer = () => {
      timer.value = setInterval(() => {
        if (timeLeft.value > 0) {
          timeLeft.value--
        } else {
          clearInterval(timer.value)
        }
      }, 1000)
    }

    const stopTimer = () => {
      if (timer.value) {
        clearInterval(timer.value)
        timer.value = null
      }
    }

    const handleOtpInput = (index, event) => {
      const value = event.target.value
      
      // Only allow digits
      if (!/^\d*$/.test(value)) {
        event.target.value = otpDigits.value[index]
        return
      }

      otpDigits.value[index] = value
      hasOtpError.value = false
      otpErrorMessage.value = ''

      // Auto-focus next input
      if (value && index < 5) {
        const nextInput = document.querySelector(`[ref="otpInput${index + 1}"]`)
        if (nextInput) {
          nextInput.focus()
        }
      }
    }

    const handleOtpKeydown = (index, event) => {
      // Handle backspace
      if (event.key === 'Backspace' && !otpDigits.value[index] && index > 0) {
        const prevInput = document.querySelector(`[ref="otpInput${index - 1}"]`)
        if (prevInput) {
          prevInput.focus()
        }
      }
    }

    const handleOtpPaste = (event) => {
      event.preventDefault()
      const pastedData = event.clipboardData.getData('text').replace(/\D/g, '')
      
      if (pastedData.length === 6) {
        otpDigits.value = pastedData.split('')
        // Focus the last input
        const lastInput = document.querySelector('[ref="otpInput5"]')
        if (lastInput) {
          lastInput.focus()
        }
      }
    }

    const handleVerifyOtp = async () => {
      if (!isOtpComplete.value) return

      verifying.value = true
      hasOtpError.value = false
      otpErrorMessage.value = ''

      try {
        const otp = otpDigits.value.join('')
        const response = await window.axios.post('/auth/verify-otp', {
          user_id: pendingUserId.value,
          otp: otp
        })

        if (response.data.success) {
          // Stop timer
          stopTimer()
          
          // Store token and user data
          localStorage.setItem('token', response.data.token)
          localStorage.setItem('user', JSON.stringify(response.data.user))
          
          // Emit success event
          emit('register-success', response.data.user)
          
          // Close modal
          emit('close')
          
          // Show success message
          window.showSuccess('Phone verified! Account created successfully!')
        } else {
          hasOtpError.value = true
          otpErrorMessage.value = response.data.message || 'Invalid verification code'
        }
      } catch (error) {
        console.error('OTP verification error:', error)
        hasOtpError.value = true
        otpErrorMessage.value = error.response?.data?.message || 'Verification failed. Please try again.'
      } finally {
        verifying.value = false
      }
    }

    const handleResendOtp = async () => {
      resending.value = true
      hasOtpError.value = false
      otpErrorMessage.value = ''

      try {
        const response = await window.axios.post('/auth/resend-otp', {
          user_id: pendingUserId.value
        })

        if (response.data.success) {
          // Reset timer
          timeLeft.value = 300
          startTimer()
          
          // Clear OTP inputs
          otpDigits.value = ['', '', '', '', '', '']
          
          // Focus first input
          const firstInput = document.querySelector('[ref="otpInput0"]')
          if (firstInput) {
            firstInput.focus()
          }
        } else {
          hasOtpError.value = true
          otpErrorMessage.value = response.data.message || 'Failed to resend code'
        }
      } catch (error) {
        console.error('Resend OTP error:', error)
        hasOtpError.value = true
        otpErrorMessage.value = error.response?.data?.message || 'Failed to resend code. Please try again.'
      } finally {
        resending.value = false
      }
    }

    const handleBackToRegistration = () => {
      stopTimer()
      showOtpVerification.value = false
      otpDigits.value = ['', '', '', '', '', '']
      hasOtpError.value = false
      otpErrorMessage.value = ''
      timeLeft.value = 300
    }

    const closeModal = () => {
      // Reset OTP state when closing
      stopTimer()
      showOtpVerification.value = false
      otpDigits.value = ['', '', '', '', '', '']
      hasOtpError.value = false
      otpErrorMessage.value = ''
      timeLeft.value = 300
      emit('close')
    }

    const switchToLogin = () => {
      emit('switch-to-login')
    }

    // Cleanup on unmount
    onUnmounted(() => {
      stopTimer()
    })

    return {
      form,
      loading,
      error,
      showPassword,
      showConfirmPassword,
      showOtpVerification,
      otpDigits,
      verifying,
      resending,
      hasOtpError,
      otpErrorMessage,
      timeLeft,
      formattedMobile,
      isOtpComplete,
      formatTime,
      handleRegister,
      handleOtpInput,
      handleOtpKeydown,
      handleOtpPaste,
      handleVerifyOtp,
      handleResendOtp,
      handleBackToRegistration,
      closeModal,
      switchToLogin
    }
  }
}
</script>
