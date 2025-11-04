<template>
  <div class="min-h-screen bg-gradient-to-br from-emerald-50 via-teal-50 to-cyan-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="relative max-w-md w-full">
      <div class="bg-white/80 backdrop-blur-md shadow-2xl rounded-3xl border border-white/20 overflow-hidden">
        <div class="px-8 pt-8 pb-6 text-center">
          <h2 class="text-2xl font-bold text-gray-900">Reset your password</h2>
          <p class="mt-2 text-sm text-gray-600">We will send a 6-digit code to your WhatsApp/SMS.</p>
        </div>

        <div class="px-8 pb-8 space-y-6">
          <!-- Step 1: Request OTP -->
          <div v-if="step === 1" class="space-y-4">
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">Mobile Number</label>
              <FlagSelector v-model="mobile" input-id="fp_mobile" input-name="fp_mobile" input-type="text" :required="true" placeholder="3001234567" />
            </div>
            <div>
              <button type="button" :disabled="loading || !mobile" @click="requestCode" class="w-full py-3 rounded-xl text-white bg-green-600 hover:bg-green-700 font-semibold disabled:opacity-50">{{ loading ? 'Sending...' : 'Send Code' }}</button>
            </div>
          </div>

          <!-- Step 2: Verify OTP -->
          <div v-else-if="step === 2" class="space-y-4">
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">Enter 6-digit code</label>
              <input v-model="otp" maxlength="6" class="block w-full px-3 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500" placeholder="123456" />
            </div>
            <div class="flex gap-2">
              <button type="button" :disabled="loading || otp.length !== 6" @click="verifyCode" class="flex-1 py-3 rounded-xl text-white bg-green-600 hover:bg-green-700 font-semibold disabled:opacity-50">{{ loading ? 'Verifying...' : 'Verify Code' }}</button>
              <button type="button" :disabled="loading" @click="step = 1" class="px-4 py-3 rounded-xl border">Back</button>
            </div>
          </div>

          <!-- Step 3: Reset Password -->
          <div v-else-if="step === 3" class="space-y-4">
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">New Password</label>
              <input v-model="password" :type="showPassword ? 'text' : 'password'" class="block w-full px-3 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500" placeholder="New password" />
            </div>
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">Confirm Password</label>
              <input v-model="passwordConfirmation" :type="showPassword ? 'text' : 'password'" class="block w-full px-3 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500" placeholder="Confirm password" />
            </div>
            <div class="flex items-center"><input id="show" type="checkbox" v-model="showPassword" class="mr-2" /><label for="show" class="text-sm text-gray-700">Show password</label></div>
            <div class="flex gap-2">
              <button type="button" :disabled="loading || !canSubmitPassword" @click="resetPassword" class="flex-1 py-3 rounded-xl text-white bg-green-600 hover:bg-green-700 font-semibold disabled:opacity-50">{{ loading ? 'Saving...' : 'Reset Password' }}</button>
              <button type="button" :disabled="loading" @click="step = 2" class="px-4 py-3 rounded-xl border">Back</button>
            </div>
          </div>

          <!-- Alerts -->
          <div v-if="error" class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl">{{ error }}</div>
          <div v-if="message" class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl">{{ message }}</div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import FlagSelector from '@/components/FlagSelector.vue'

export default {
  name: 'ForgotPassword',
  components: { FlagSelector },
  setup() {
    const router = useRouter()
    const auth = useAuthStore()

    const step = ref(1)
    const loading = ref(false)
    const error = ref('')
    const message = ref('')
    const mobile = ref('')
    const userId = ref(null)
    const otp = ref('')
    const password = ref('')
    const passwordConfirmation = ref('')
    const showPassword = ref(false)

    const canSubmitPassword = computed(() => password.value && password.value === passwordConfirmation.value && password.value.length >= 8)

    const requestCode = async () => {
      error.value = ''
      message.value = ''
      loading.value = true
      const res = await auth.requestPasswordResetOtp(mobile.value)
      loading.value = false
      if (res.success) {
        userId.value = res.userId
        message.value = 'Verification code sent.'
        step.value = 2
      } else {
        error.value = res.message
      }
    }

    const verifyCode = async () => {
      error.value = ''
      message.value = ''
      loading.value = true
      const res = await auth.verifyPasswordResetOtp(userId.value, otp.value)
      loading.value = false
      if (res.success) {
        message.value = 'Code verified. Set a new password.'
        step.value = 3
      } else {
        error.value = res.message
      }
    }

    const resetPassword = async () => {
      error.value = ''
      message.value = ''
      loading.value = true
      const res = await auth.resetPasswordWithOtp(userId.value, otp.value, password.value, passwordConfirmation.value)
      loading.value = false
      if (res.success) {
        message.value = 'Password reset successfully. You can now sign in.'
        setTimeout(() => router.push('/login'), 1200)
      } else {
        error.value = res.message
      }
    }

    return { step, loading, error, message, mobile, userId, otp, password, passwordConfirmation, showPassword, canSubmitPassword, requestCode, verifyCode, resetPassword }
  }
}
</script>


