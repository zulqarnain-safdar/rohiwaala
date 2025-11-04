<template>
  <div class="min-h-screen bg-gradient-to-br from-green-50 via-emerald-50 to-teal-100">
    <!-- Header -->
    <div class="bg-white/80 backdrop-blur-md shadow-lg border-b border-white/20 px-6 py-4">
      <div class="flex items-center justify-between">
        <div class="flex items-center space-x-4">
          <button @click="$router.go(-1)" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
          </button>
          <h1 class="text-2xl font-bold text-gray-900">Service Confirmation</h1>
        </div>
        <div class="text-sm text-gray-600">
          Confirm service completion and rate technician
        </div>
      </div>
    </div>

    <div class="p-6">
      <div class="max-w-4xl mx-auto">
        <!-- Service Details -->
        <div class="bg-white/70 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 p-6 mb-6">
          <h2 class="text-xl font-bold text-gray-900 mb-6">Service Details</h2>
          
          <div v-if="job" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label class="text-sm font-semibold text-gray-500">Order Number</label>
                <p class="text-lg font-medium">{{ job.order.order_number }}</p>
              </div>
              <div>
                <label class="text-sm font-semibold text-gray-500">Farm</label>
                <p class="text-lg font-medium">{{ job.order.cattle_farm.name }}</p>
              </div>
              <div>
                <label class="text-sm font-semibold text-gray-500">Technician</label>
                <div class="mt-1">
                  <p class="text-lg font-medium">{{ job.technician.user.name }}</p>
                  <div class="flex items-center space-x-4 mt-2">
                    <div class="flex items-center text-sm text-gray-600">
                      <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                      </svg>
                      <span>{{ job.technician.user.mobile || 'Phone not available' }}</span>
                    </div>
                    <div class="flex items-center text-sm text-gray-600">
                      <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                      </svg>
                      <span>{{ job.technician.user.email || 'Email not available' }}</span>
                    </div>
                  </div>
                  <div class="flex space-x-2 mt-2">
                    <button 
                      @click="callTechnician(job.technician)"
                      class="px-3 py-1 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 transition-colors text-sm flex items-center"
                    >
                      <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                      </svg>
                      Call
                    </button>
                    <button 
                      @click="messageTechnician(job.technician)"
                      class="px-3 py-1 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors text-sm flex items-center"
                    >
                      <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                      </svg>
                      Message
                    </button>
                  </div>
                </div>
              </div>
              <div>
                <label class="text-sm font-semibold text-gray-500">Visit Date</label>
                <p class="text-lg font-medium">{{ formatDateTime(job.actual_visit_at) }}</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Service Confirmation Form -->
        <div class="bg-white/70 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 p-6">
          <h2 class="text-xl font-bold text-gray-900 mb-6">Confirm Service</h2>
          
          <form @submit.prevent="submitConfirmation" class="space-y-6">
            <!-- Service Completed -->
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-3">Was the service completed successfully?</label>
              <div class="flex space-x-4">
                <label class="flex items-center">
                  <input
                    v-model="confirmationForm.service_completed"
                    type="radio"
                    :value="true"
                    class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300"
                  />
                  <span class="ml-2 text-sm text-gray-700">Yes, service was completed</span>
                </label>
                <label class="flex items-center">
                  <input
                    v-model="confirmationForm.service_completed"
                    type="radio"
                    :value="false"
                    class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300"
                  />
                  <span class="ml-2 text-sm text-gray-700">No, service was not completed</span>
                </label>
              </div>
            </div>

            <!-- Cow in Heat Status -->
            <div v-if="confirmationForm.service_completed">
              <label class="block text-sm font-semibold text-gray-700 mb-3">Was the cow in heat during the visit?</label>
              <div class="flex space-x-4">
                <label class="flex items-center">
                  <input
                    v-model="confirmationForm.cow_in_heat"
                    type="radio"
                    :value="true"
                    class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300"
                  />
                  <span class="ml-2 text-sm text-gray-700">Yes, cow was in heat</span>
                </label>
                <label class="flex items-center">
                  <input
                    v-model="confirmationForm.cow_in_heat"
                    type="radio"
                    :value="false"
                    class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300"
                  />
                  <span class="ml-2 text-sm text-gray-700">No, cow was not in heat</span>
                </label>
              </div>
            </div>


            <!-- Semen Straw Code -->
            <div v-if="confirmationForm.service_completed && confirmationForm.cow_in_heat">
              <label class="block text-sm font-semibold text-gray-700 mb-2">Semen Straw Code (Optional)</label>
              <input
                v-model="confirmationForm.semen_straw_code"
                type="text"
                class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                placeholder="Enter straw code if available"
              />
            </div>

            <!-- Rating -->
            <div v-if="confirmationForm.service_completed">
              <label class="block text-sm font-semibold text-gray-700 mb-3">Rate the Technician</label>
              <div class="flex space-x-1">
                <button
                  v-for="star in 5"
                  :key="star"
                  @click="confirmationForm.rating = star"
                  type="button"
                  class="text-2xl focus:outline-none"
                >
                  <svg
                    :class="star <= confirmationForm.rating ? 'text-yellow-400' : 'text-gray-300'"
                    class="w-8 h-8"
                    fill="currentColor"
                    viewBox="0 0 20 20"
                  >
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                  </svg>
                </button>
              </div>
            </div>

            <!-- Farmer Notes -->
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">Additional Notes</label>
              <textarea
                v-model="confirmationForm.farmer_notes"
                rows="4"
                class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                placeholder="Any additional comments about the service..."
              ></textarea>
            </div>

            <!-- Photo Upload -->
            <div v-if="confirmationForm.service_completed">
              <label class="block text-sm font-semibold text-gray-700 mb-2">Upload Photos (Optional)</label>
              <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-xl">
                <div class="space-y-1 text-center">
                  <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                  </svg>
                  <div class="flex text-sm text-gray-600">
                    <label for="file-upload" class="relative cursor-pointer bg-white rounded-md font-medium text-green-600 hover:text-green-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-green-500">
                      <span>Upload photos</span>
                      <input id="file-upload" name="file-upload" type="file" class="sr-only" multiple accept="image/*" @change="handleFileUpload" />
                    </label>
                    <p class="pl-1">or drag and drop</p>
                  </div>
                  <p class="text-xs text-gray-500">PNG, JPG, GIF up to 10MB each</p>
                </div>
              </div>
              
              <!-- Uploaded Photos Preview -->
              <div v-if="uploadedPhotos.length > 0" class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-4">
                <div v-for="(photo, index) in uploadedPhotos" :key="index" class="relative">
                  <img :src="photo.preview" class="w-full h-24 object-cover rounded-lg" />
                  <button
                    @click="removePhoto(index)"
                    type="button"
                    class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs"
                  >
                    ×
                  </button>
                </div>
              </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end space-x-4">
              <button
                type="button"
                @click="$router.go(-1)"
                class="px-6 py-3 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-colors font-medium"
              >
                Cancel
              </button>
              <button
                type="submit"
                :disabled="!canSubmit"
                class="px-6 py-3 bg-green-600 text-white rounded-xl hover:bg-green-700 disabled:bg-gray-300 disabled:cursor-not-allowed transition-colors font-medium"
              >
                Submit Confirmation
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Technician Details Modal -->
    <div v-if="showTechnicianModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
      <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full">
        <div class="p-6">
          <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Technician Details</h2>
            <button @click="showTechnicianModal = false" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
              <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
              </svg>
            </button>
          </div>

          <div v-if="selectedTechnician" class="space-y-6">
            <!-- Technician Profile -->
            <div class="text-center">
              <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
              </div>
              <h3 class="text-xl font-bold text-gray-900">{{ selectedTechnician.user.name }}</h3>
              <p class="text-gray-600">Assigned Technician</p>
            </div>

            <!-- Contact Information -->
            <div class="space-y-4">
              <div class="bg-gray-50 rounded-lg p-4">
                <h4 class="text-sm font-semibold text-gray-700 mb-3">Contact Information</h4>
                <div class="space-y-3">
                  <div class="flex items-center">
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                      <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                      </svg>
                    </div>
                    <div>
                      <p class="text-sm font-medium text-gray-900">Phone Number</p>
                      <p class="text-lg font-semibold text-gray-700">{{ selectedTechnician.user.mobile || 'Not available' }}</p>
                    </div>
                  </div>
                  
                  <div class="flex items-center">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                      <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                      </svg>
                    </div>
                    <div>
                      <p class="text-sm font-medium text-gray-900">Email Address</p>
                      <p class="text-sm text-gray-700">{{ selectedTechnician.user.email || 'Not available' }}</p>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Action Buttons -->
              <div class="flex space-x-3">
                <button
                  @click="initiateCall(selectedTechnician)"
                  :disabled="!selectedTechnician.user.mobile"
                  class="flex-1 flex items-center justify-center px-4 py-3 bg-green-600 text-white rounded-xl hover:bg-green-700 disabled:bg-gray-300 disabled:cursor-not-allowed transition-colors font-medium"
                >
                  <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                  </svg>
                  Call Now
                </button>
                
                <button
                  @click="initiateMessage(selectedTechnician)"
                  :disabled="!selectedTechnician.user.mobile"
                  class="flex-1 flex items-center justify-center px-4 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 disabled:bg-gray-300 disabled:cursor-not-allowed transition-colors font-medium"
                >
                  <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                  </svg>
                  Send Message
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, computed, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'

export default {
  name: 'ServiceConfirmation',
  setup() {
    const route = useRoute()
    const router = useRouter()
    const job = ref(null)
    const uploadedPhotos = ref([])
    const showTechnicianModal = ref(false)
    const selectedTechnician = ref(null)
    
    const confirmationForm = ref({
      service_completed: null,
      cow_in_heat: null,
      semen_straw_code: '',
      rating: 0,
      farmer_notes: ''
    })

    const canSubmit = computed(() => confirmationForm.value.service_completed !== null)

    const loadJob = async () => {
      try {
        const jobId = route.params.jobId
        const response = await window.axios.get(`/farmer/jobs/${jobId}`)
        job.value = response.data.job
      } catch (error) {
        console.error('Error loading job:', error)
        showError('Failed to load job details')
      }
    }

    const handleFileUpload = (event) => {
      const files = Array.from(event.target.files)
      files.forEach(file => {
        if (file.type.startsWith('image/')) {
          const reader = new FileReader()
          reader.onload = (e) => {
            uploadedPhotos.value.push({
              file,
              preview: e.target.result
            })
          }
          reader.readAsDataURL(file)
        }
      })
    }

    const removePhoto = (index) => {
      uploadedPhotos.value.splice(index, 1)
    }

    const submitConfirmation = async () => {
      try {
        const formData = new FormData()
        formData.append('service_completed', confirmationForm.value.service_completed)
        
        // Only send service-related fields if service was completed
        if (confirmationForm.value.service_completed) {
          formData.append('cow_in_heat', confirmationForm.value.cow_in_heat)
          formData.append('semen_straw_code', confirmationForm.value.semen_straw_code)
          
          // Only send rating if it's greater than 0
          if (confirmationForm.value.rating > 0) {
            formData.append('rating', confirmationForm.value.rating)
          }
          
          uploadedPhotos.value.forEach((photo, index) => {
            formData.append(`photos[${index}]`, photo.file)
          })
        }
        
        formData.append('farmer_notes', confirmationForm.value.farmer_notes)

        await window.axios.post(`/farmer/jobs/${route.params.jobId}/confirm`, formData, {
          headers: { 'Content-Type': 'multipart/form-data' }
        })

        showSuccess('Service confirmation submitted successfully!')
        router.push('/farmer/dashboard')
      } catch (error) {
        console.error('Error submitting confirmation:', error)
        showError('Failed to submit confirmation. Please try again.')
      }
    }

    const callTechnician = (technician) => {
      selectedTechnician.value = technician
      showTechnicianModal.value = true
    }
    
    const messageTechnician = (technician) => {
      selectedTechnician.value = technician
      showTechnicianModal.value = true
    }
    
    const initiateCall = (technician) => {
      if (technician.user.mobile) {
        window.open(`tel:${technician.user.mobile}`, '_self')
        showTechnicianModal.value = false
      } else {
        showInfo('Technician phone number not available')
      }
    }
    
    const initiateMessage = (technician) => {
      if (technician.user.mobile) {
        const message = `Hello ${technician.user.name}, I have a question about my order.`
        window.open(`sms:${technician.user.mobile}?body=${encodeURIComponent(message)}`, '_self')
        showTechnicianModal.value = false
      } else {
        showInfo('Technician phone number not available')
      }
    }

    onMounted(loadJob)

    return {
      job,
      confirmationForm,
      uploadedPhotos,
      canSubmit,
      handleFileUpload,
      removePhoto,
      submitConfirmation,
      callTechnician,
      messageTechnician,
      initiateCall,
      initiateMessage,
      showTechnicianModal,
      selectedTechnician,
      formatDateTime: (date) => {
        const d = new Date(date)
        return d.toLocaleString('en-GB', {
          day: '2-digit',
          month: '2-digit',
          year: 'numeric',
          hour: '2-digit',
          minute: '2-digit',
          hour12: false,
          timeZone: 'Asia/Karachi'
        })
      }
    }
  }
}
</script>
