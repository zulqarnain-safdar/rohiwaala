<template>
  <div class="min-h-screen bg-gradient-to-br from-green-50 to-blue-50 flex items-center justify-center p-4">
    <div class="max-w-md w-full">
      <!-- Header -->
      <div class="text-center mb-8">
        <div class="mx-auto w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mb-4">
          <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
        </div>
        <h2 class="text-2xl font-bold text-gray-900 mb-2">Verify Your Phone</h2>
        <p class="text-gray-600">
          We've sent a verification code to<br>
          <span class="font-semibold text-green-600">{{ formattedMobile }}</span>
        </p>
      </div>

      <!-- OTP Form -->
      <div class="bg-white rounded-2xl shadow-xl p-8">
        <form @submit.prevent="handleVerifyOtp" class="space-y-6">
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
                :class="{ 'border-red-500': hasError }"
                @input="handleOtpInput(index, $event)"
                @keydown="handleOtpKeydown(index, $event)"
                @paste="handleOtpPaste"
                autocomplete="one-time-code"
                inputmode="numeric"
              />
            </div>
            <div v-if="hasError" class="mt-2 text-sm text-red-600 text-center">
              {{ errorMessage }}
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

          <!-- Submit Button -->
          <button
            type="submit"
            :disabled="!isOtpComplete || verifying"
            class="w-full bg-green-600 text-white py-3 px-4 rounded-xl font-semibold hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200"
          >
            {{ verifying ? 'Verifying...' : 'Verify & Continue' }}
          </button>

          <!-- Back to Login -->
          <div class="text-center">
            <button
              type="button"
              @click="handleBackToLogin"
              class="text-sm text-gray-600 hover:text-gray-800 font-medium"
            >
              ← Back to Login
            </button>
          </div>
        </form>
      </div>

      <!-- WhatsApp Info -->
      <div class="mt-6 bg-blue-50 border border-blue-200 rounded-xl p-4">
        <div class="flex items-start space-x-3">
          <div class="flex-shrink-0">
            <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="currentColor" viewBox="0 0 24 24">
              <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
            </svg>
          </div>
          <div class="text-sm text-blue-800">
            <p class="font-semibold">Check WhatsApp</p>
            <p>We've sent your verification code via WhatsApp. Please check your WhatsApp messages.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, computed, onMounted, onUnmounted, nextTick } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

export default {
  name: 'OtpVerificationPage',
  setup() {
    const router = useRouter()
    const route = useRoute()
    const authStore = useAuthStore()
    
    const otpDigits = ref(['', '', '', '', '', ''])
    const otpInputs = ref([])
    const verifying = ref(false)
    const resending = ref(false)
    const hasError = ref(false)
    const errorMessage = ref('')
    const timeLeft = ref(300) // 5 minutes in seconds
    const timer = ref(null)

    const userId = computed(() => route.query.user_id)
    const mobile = computed(() => route.query.mobile)

    const formattedMobile = computed(() => {
      if (!mobile.value) return ''
      // Format mobile number for display
      const phone = mobile.value.replace(/\D/g, '')
      if (phone.startsWith('92')) {
        return `+${phone}`
      } else if (phone.startsWith('0')) {
        return `+92${phone.substring(1)}`
      }
      return `+92${phone}`
    })

    const isOtpComplete = computed(() => {
      return otpDigits.value.every(digit => digit !== '')
    })

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
      hasError.value = false
      errorMessage.value = ''

      // Auto-focus next input
      if (value && index < 5) {
        nextTick(() => focusInput(index + 1))
      }
    }

    const handleOtpKeydown = (index, event) => {
      // Handle backspace
      if (event.key === 'Backspace') {
        if (otpDigits.value[index]) {
          // clear current and stay
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

    const handleVerifyOtp = async () => {
      if (!isOtpComplete.value) return

      verifying.value = true
      hasError.value = false
      errorMessage.value = ''

      try {
        const otp = otpDigits.value.join('')
        const result = await authStore.verifyOtp(userId.value, otp)

        if (result.success) {
          // Stop timer
          stopTimer()
          
          // Redirect to appropriate dashboard
          if (result.redirectTo) {
            router.push(result.redirectTo)
          } else {
            // Fallback redirect based on user role
            const role = authStore.userRole
            if (role === 'farmer') {
              router.push('/farmer/dashboard')
            } else if (role === 'technician') {
              router.push('/technician/dashboard')
            } else {
              router.push('/dashboard')
            }
          }
        } else {
          hasError.value = true
          errorMessage.value = result.message || 'Invalid verification code'
        }
      } catch (error) {
        console.error('OTP verification error:', error)
        hasError.value = true
        errorMessage.value = 'Verification failed. Please try again.'
      } finally {
        verifying.value = false
      }
    }

    const handleResendOtp = async () => {
      resending.value = true
      hasError.value = false
      errorMessage.value = ''

      try {
        const result = await authStore.resendOtp(userId.value)

        if (result.success) {
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
          hasError.value = true
          errorMessage.value = result.message || 'Failed to resend code'
        }
      } catch (error) {
        console.error('Resend OTP error:', error)
        hasError.value = true
        errorMessage.value = 'Failed to resend code. Please try again.'
      } finally {
        resending.value = false
      }
    }

    const handleBackToLogin = () => {
      stopTimer()
      router.push('/login')
    }

    onMounted(() => {
      startTimer()
      
      // Focus first input
      nextTick(() => focusInput(0))
    })

    onUnmounted(() => {
      stopTimer()
    })

    return {
      otpDigits,
      verifying,
      resending,
      hasError,
      errorMessage,
      timeLeft,
      formattedMobile,
      isOtpComplete,
      formatTime,
      handleOtpInput,
      handleOtpKeydown,
      handleOtpPaste,
      handleVerifyOtp,
      handleResendOtp,
      handleBackToLogin,
      setOtpRef
    }
  }
}
</script>

