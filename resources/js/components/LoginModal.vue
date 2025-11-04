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
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
              </svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Customer Login</h2>
            <p class="text-gray-600">Sign in to continue with your order</p>
            
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
                  :ref="el => setOtpRef(el, index)"
                  v-model="otpDigits[index]"
                  type="text"
                  maxlength="1"
                  class="w-12 h-12 text-center text-xl font-bold border-2 border-gray-300 rounded-lg focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-all duration-200"
                  :class="{ 'border-red-500': hasOtpError }"
                  @input="handleOtpInput(index, $event)"
                  @keydown="handleOtpKeydown(index, $event)"
                  @paste="handleOtpPaste"
                  autocomplete="one-time-code"
                  inputmode="numeric"
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

            <!-- Back to Login -->
            <div class="text-center">
              <button
                @click="handleBackToLogin"
                class="text-sm text-gray-600 hover:text-gray-800 font-medium"
              >
                ← Back to Login
              </button>
            </div>
          </div>

          <!-- Forgot Password Flow -->
          <div v-else-if="showForgotPassword" class="space-y-6">
            <div class="text-center">
              <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0-1.657-1.343-3-3-3S6 9.343 6 11v1H5a2 2 0 00-2 2v4a2 2 0 002 2h8a2 2 0 002-2v-4a2 2 0 00-2-2h-1v-1zM8 15h2"/>
                </svg>
              </div>
              <h3 class="text-lg font-semibold text-gray-900 mb-2">Reset your password</h3>
              <p class="text-sm text-gray-600">We'll send a 6-digit code to your WhatsApp/SMS.</p>
            </div>

            <!-- Step 1: Request OTP -->
            <div v-if="fpStep === 1" class="space-y-4">
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Mobile Number</label>
                <FlagSelector v-model="fpMobile" input-id="fp_mobile_modal" input-name="fp_mobile_modal" input-type="tel" :required="true" placeholder="3001234567" />
              </div>
              <div>
                <button type="button" :disabled="fpLoading || !fpMobile" @click="fpRequestCode" class="w-full py-3 rounded-xl text-white bg-green-600 hover:bg-green-700 font-semibold disabled:opacity-50">{{ fpLoading ? 'Sending...' : 'Send Code' }}</button>
              </div>
              <div v-if="fpError" class="bg-red-50 border border-red-200 rounded-xl p-3 text-sm text-red-700">{{ fpError }}</div>
              <div v-if="fpMessage" class="bg-emerald-50 border border-emerald-200 rounded-xl p-3 text-sm text-emerald-700">{{ fpMessage }}</div>
            </div>

            <!-- Step 2: Verify OTP -->
            <div v-else-if="fpStep === 2" class="space-y-4">
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Enter Verification Code</label>
                <div class="flex justify-center space-x-3">
                  <input
                    v-for="(digit, index) in fpOtpDigits"
                    :key="`fp-${index}`"
                    :ref="el => setFpOtpRef(el, index)"
                    v-model="fpOtpDigits[index]"
                    type="text"
                    maxlength="1"
                    class="w-12 h-12 text-center text-xl font-bold border-2 border-gray-300 rounded-lg focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-all duration-200"
                    :class="{ 'border-red-500': fpHasError }"
                    @input="fpHandleOtpInput(index, $event)"
                    @keydown="fpHandleOtpKeydown(index, $event)"
                    @paste="fpHandleOtpPaste"
                    autocomplete="one-time-code"
                    inputmode="numeric"
                  />
                </div>
                <div v-if="fpHasError" class="mt-2 text-sm text-red-600 text-center">{{ fpError }}</div>
              </div>
              <div class="flex gap-2">
                <button type="button" :disabled="fpLoading || !isFpOtpComplete" @click="fpVerifyCode" class="flex-1 py-3 rounded-xl text-white bg-green-600 hover:bg-green-700 font-semibold disabled:opacity-50">{{ fpLoading ? 'Verifying...' : 'Verify Code' }}</button>
                <button type="button" :disabled="fpLoading" @click="fpStep = 1" class="px-4 py-3 rounded-xl border">Back</button>
              </div>
            </div>

            <!-- Step 3: Reset Password -->
            <div v-else-if="fpStep === 3" class="space-y-4">
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">New Password</label>
                <input v-model="fpPassword" :type="fpShowPassword ? 'text' : 'password'" class="w-full px-3 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500" placeholder="New password" />
              </div>
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Confirm Password</label>
                <input v-model="fpPasswordConfirmation" :type="fpShowPassword ? 'text' : 'password'" class="w-full px-3 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500" placeholder="Confirm password" />
              </div>
              <div class="flex items-center"><input id="fp_show" type="checkbox" v-model="fpShowPassword" class="mr-2" /><label for="fp_show" class="text-sm text-gray-700">Show password</label></div>
              <div class="flex gap-2">
                <button type="button" :disabled="fpLoading || !canSubmitFpPassword" @click="fpResetPassword" class="flex-1 py-3 rounded-xl text-white bg-green-600 hover:bg-green-700 font-semibold disabled:opacity-50">{{ fpLoading ? 'Saving...' : 'Reset Password' }}</button>
                <button type="button" :disabled="fpLoading" @click="fpStep = 2" class="px-4 py-3 rounded-xl border">Back</button>
              </div>
              <div v-if="fpError" class="bg-red-50 border border-red-200 rounded-xl p-3 text-sm text-red-700">{{ fpError }}</div>
              <div v-if="fpMessage" class="bg-emerald-50 border border-emerald-200 rounded-xl p-3 text-sm text-emerald-700">{{ fpMessage }}</div>
            </div>

            <div class="text-center">
              <button @click="closeForgotPassword" class="text-sm text-gray-600 hover:text-gray-800 font-medium">← Back to Login</button>
            </div>
          </div>

          <!-- Login Form -->
          <form v-else @submit.prevent="handleLogin" class="space-y-6">
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
                @keydown.enter.prevent="handleLogin"
              />
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
                  placeholder="Enter your password"
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
              <div class="flex justify-end mt-2">
                <button type="button" @click="openForgotPassword" class="text-sm text-green-600 hover:text-green-800 font-medium">Forgot password?</button>
              </div>
            </div>

            <!-- Remember Me Checkbox -->
            <div class="flex items-center">
              <input
                id="remember_me_modal"
                v-model="rememberMe"
                type="checkbox"
                class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded transition-colors duration-200 cursor-pointer"
              />
              <label for="remember_me_modal" class="ml-2 block text-sm text-gray-700 cursor-pointer select-none">
                Remember me
              </label>
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

            <!-- Login Button -->
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
                Signing In...
              </span>
              <span v-else>Sign In</span>
            </button>

            <!-- Register Link -->
            <div class="text-center">
              <p class="text-gray-600">
                Don't have an account?
                <button @click="switchToRegister" class="text-green-600 hover:text-green-700 font-semibold transition-colors">
                  Register here
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
import { ref, reactive, computed, onMounted, onUnmounted, watch, nextTick } from 'vue'
import FlagSelector from '@/components/FlagSelector.vue'

export default {
  name: 'LoginModal',
  components: { FlagSelector },
  props: {
    show: {
      type: Boolean,
      default: false
    }
  },
  emits: ['close', 'login-success', 'switch-to-register'],
  setup(props, { emit }) {
    const form = reactive({
      mobile: '',
      password: ''
    })
    const loading = ref(false)
    const error = ref('')
    const showPassword = ref(false)
    const rememberMe = ref(false)
    
    // OTP Verification state
    const showOtpVerification = ref(false)
    const otpDigits = ref(['', '', '', '', '', ''])
    const otpInputs = ref([])
    const verifying = ref(false)
    const resending = ref(false)
    const hasOtpError = ref(false)
    const otpErrorMessage = ref('')
    const timeLeft = ref(300) // 5 minutes in seconds
    const timer = ref(null)
    const pendingUserId = ref(null)

    // Forgot password state
    const showForgotPassword = ref(false)
    const fpStep = ref(1)
    const fpMobile = ref('')
    const fpUserId = ref(null)
    const fpOtpDigits = ref(['', '', '', '', '', ''])
    const fpOtpInputs = ref([])
    const fpPassword = ref('')
    const fpPasswordConfirmation = ref('')
    const fpShowPassword = ref(false)
    const fpLoading = ref(false)
    const fpError = ref('')
    const fpMessage = ref('')

    const handleLogin = async () => {
      loading.value = true
      error.value = ''

      try {
        const response = await window.axios.post('/auth/login', {
          mobile: form.mobile,
          password: form.password,
          remember_me: rememberMe.value
        })

        if (response.data.success) {
          // Check if user is a farmer (customer)
          if (response.data.user.role !== 'farmer') {
            error.value = 'Access denied. Only customers can login through this portal.'
            return
          }

          // Do not persist raw passwords client-side. Use browser password manager instead.
          localStorage.removeItem('remembered_credentials_modal')

          // Store token and user data
          localStorage.setItem('token', response.data.token)
          localStorage.setItem('user', JSON.stringify(response.data.user))
          
          // Emit success event
          emit('login-success', response.data.user)
          
          // Close modal
          emit('close')
          
          // Show success message
          window.showSuccess('Login successful! Welcome back!')
        } else {
          error.value = response.data.message || 'Login failed'
        }
      } catch (err) {
        console.error('Login error:', err)
        
        // Check if verification is required
        if (err.response?.data?.requires_verification) {
          // Show OTP verification screen
          pendingUserId.value = err.response.data.user_id
          showOtpVerification.value = true
          startTimer()
          return
        }
        
        error.value = err.response?.data?.message || 'Login failed. Please try again.'
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

    const isFpOtpComplete = computed(() => fpOtpDigits.value.every(d => d !== ''))
    const canSubmitFpPassword = computed(() => fpPassword.value && fpPassword.value === fpPasswordConfirmation.value && fpPassword.value.length >= 8)

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

    const focusInput = (i) => {
      const el = otpInputs.value[i]
      if (el) el.focus()
    }

    const setOtpRef = (el, index) => {
      if (el) {
        otpInputs.value[index] = el
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
        nextTick(() => focusInput(index + 1))
      }
    }

    const handleOtpKeydown = (index, event) => {
      // Handle backspace
      if (event.key === 'Backspace') {
        if (otpDigits.value[index]) {
          otpDigits.value[index] = ''
          return
        }
        if (index > 0) {
          nextTick(() => focusInput(index - 1))
        }
      }
    }

    const handleOtpPaste = (event) => {
      event.preventDefault()
      const pastedData = event.clipboardData.getData('text').replace(/\D/g, '')
      
      if (pastedData.length >= 1) {
        const digits = pastedData.slice(0, 6).split('')
        for (let i = 0; i < 6; i++) {
          otpDigits.value[i] = digits[i] || ''
        }
        nextTick(() => focusInput(Math.min(digits.length, 6) - 1))
      }
    }

    // Forgot password helpers
    const openForgotPassword = () => {
      showForgotPassword.value = true
      fpStep.value = 1
      fpMobile.value = ''
      fpError.value = ''
      fpMessage.value = ''
    }

    const closeForgotPassword = () => {
      showForgotPassword.value = false
      fpStep.value = 1
      fpMobile.value = ''
      fpUserId.value = null
      fpOtpDigits.value = ['', '', '', '', '', '']
      fpPassword.value = ''
      fpPasswordConfirmation.value = ''
      fpShowPassword.value = false
      fpError.value = ''
      fpMessage.value = ''
    }

    const focusFpInput = (i) => {
      const el = fpOtpInputs.value[i]
      if (el) el.focus()
    }

    const setFpOtpRef = (el, index) => {
      if (el) fpOtpInputs.value[index] = el
    }

    const fpHandleOtpInput = (index, event) => {
      const value = event.target.value
      if (!/^\d*$/.test(value)) {
        event.target.value = fpOtpDigits.value[index]
        return
      }
      fpOtpDigits.value[index] = value
      fpError.value = ''
      if (value && index < 5) {
        nextTick(() => focusFpInput(index + 1))
      }
    }

    const fpHandleOtpKeydown = (index, event) => {
      if (event.key === 'Backspace') {
        if (fpOtpDigits.value[index]) {
          fpOtpDigits.value[index] = ''
          return
        }
        if (index > 0) nextTick(() => focusFpInput(index - 1))
      }
    }

    const fpHandleOtpPaste = (event) => {
      event.preventDefault()
      const pastedData = event.clipboardData.getData('text').replace(/\D/g, '')
      if (pastedData.length >= 1) {
        const digits = pastedData.slice(0, 6).split('')
        for (let i = 0; i < 6; i++) fpOtpDigits.value[i] = digits[i] || ''
        nextTick(() => focusFpInput(Math.min(digits.length, 6) - 1))
      }
    }

    const fpRequestCode = async () => {
      fpLoading.value = true
      fpError.value = ''
      fpMessage.value = ''
      try {
        const resp = await window.axios.post('/auth/forgot-password/request-otp', { mobile: fpMobile.value })
        if (resp.data?.success) {
          fpUserId.value = resp.data.user_id
          fpMessage.value = 'Verification code sent.'
          fpStep.value = 2
          nextTick(() => focusFpInput(0))
        } else {
          fpError.value = resp.data?.message || 'Failed to send OTP'
        }
      } catch (e) {
        fpError.value = e.response?.data?.message || 'Failed to send OTP'
      } finally {
        fpLoading.value = false
      }
    }

    const fpVerifyCode = async () => {
      if (!isFpOtpComplete.value) return
      fpLoading.value = true
      fpError.value = ''
      fpMessage.value = ''
      try {
        const otp = fpOtpDigits.value.join('')
        const resp = await window.axios.post('/auth/forgot-password/verify-otp', { user_id: fpUserId.value, otp })
        if (resp.data?.success) {
          fpMessage.value = 'Code verified. Set a new password.'
          fpStep.value = 3
        } else {
          fpError.value = resp.data?.message || 'Invalid or expired code'
        }
      } catch (e) {
        fpError.value = e.response?.data?.message || 'Invalid or expired code'
      } finally {
        fpLoading.value = false
      }
    }

    const fpResetPassword = async () => {
      if (!canSubmitFpPassword.value) return
      fpLoading.value = true
      fpError.value = ''
      fpMessage.value = ''
      try {
        const otp = fpOtpDigits.value.join('')
        const resp = await window.axios.post('/auth/forgot-password/reset', {
          user_id: fpUserId.value,
          otp,
          password: fpPassword.value,
          password_confirmation: fpPasswordConfirmation.value
        })
        if (resp.data?.success) {
          fpMessage.value = 'Password reset successfully. Please sign in.'
          setTimeout(() => {
            closeForgotPassword()
          }, 1200)
        } else {
          fpError.value = resp.data?.message || 'Failed to reset password'
        }
      } catch (e) {
        fpError.value = e.response?.data?.message || 'Failed to reset password'
      } finally {
        fpLoading.value = false
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
          
          // Do not persist raw passwords client-side.
          localStorage.removeItem('remembered_credentials_modal')
          
          // Store token and user data
          localStorage.setItem('token', response.data.token)
          localStorage.setItem('user', JSON.stringify(response.data.user))
          
          // Emit success event
          emit('login-success', response.data.user)
          
          // Close modal
          emit('close')
          
          // Show success message
          window.showSuccess('Phone verified! Login successful!')
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
          nextTick(() => focusInput(0))
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

    const handleBackToLogin = () => {
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

    const switchToRegister = () => {
      emit('switch-to-register')
    }

    // Prefill mobile from server-set cookie (remember_mobile)
    const loadSavedCredentials = () => {
      try {
        const cookieStr = document.cookie || ''
        const parts = cookieStr.split(';').map(c => c.trim())
        const entry = parts.find(p => p.startsWith('remember_mobile='))
        if (entry) {
          const value = decodeURIComponent(entry.split('=')[1] || '')
          if (value) {
            form.mobile = value
            rememberMe.value = true
          }
        }
      } catch (err) {
        console.error('Error reading remember cookie:', err)
      }
      if (!form.mobile) {
        window.axios.get('/auth/remembered-mobile')
          .then(res => {
            if (res.data?.mobile) {
              form.mobile = res.data.mobile
              rememberMe.value = true
            }
          })
          .catch(() => {})
      }
    }

    // Load saved credentials when modal opens
    onMounted(() => {
      if (props.show) {
        loadSavedCredentials()
      }
    })

    // Watch for modal opening to load credentials
    watch(() => props.show, (newValue) => {
      if (newValue) {
        loadSavedCredentials()
      }
    })

    // Cleanup on unmount
    onUnmounted(() => {
      stopTimer()
    })

    return {
      form,
      loading,
      error,
      showPassword,
      rememberMe,
      // Login verification
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
      handleLogin,
      handleOtpInput,
      handleOtpKeydown,
      handleOtpPaste,
      handleVerifyOtp,
      handleResendOtp,
      handleBackToLogin,
      closeModal,
      switchToRegister,
      setOtpRef,
      // Forgot password
      showForgotPassword,
      openForgotPassword,
      closeForgotPassword,
      fpStep,
      fpMobile,
      fpLoading,
      fpMessage,
      fpError,
      fpOtpDigits,
      setFpOtpRef,
      fpHandleOtpInput,
      fpHandleOtpKeydown,
      fpHandleOtpPaste,
      isFpOtpComplete,
      fpPassword,
      fpPasswordConfirmation,
      fpShowPassword,
      canSubmitFpPassword,
      fpRequestCode,
      fpVerifyCode,
      fpResetPassword
    }
  }
}
</script>
