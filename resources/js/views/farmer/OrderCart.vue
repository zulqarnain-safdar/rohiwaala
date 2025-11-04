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
          <h1 class="text-2xl font-bold text-gray-900">Order Cart</h1>
        </div>
        <div class="text-sm text-gray-600">
          Review your semen selection
        </div>
      </div>
    </div>

    <div class="p-6">
      <div class="max-w-4xl mx-auto">
        <!-- Cart Items -->
        <div v-if="cartItems.length > 0" class="space-y-6">
          <div class="bg-white/70 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Selected Semen</h2>
            
            <div class="space-y-4">
              <div
                v-for="(item, index) in cartItems"
                :key="index"
                class="flex items-center space-x-4 p-4 bg-gray-50 rounded-xl"
              >
                <!-- Semen Info -->
                <div class="flex-1">
                  <h3 class="font-semibold text-gray-900">{{ item.semen.name }}</h3>
                  <p class="text-sm text-gray-600">{{ item.semen.breed.name }} • {{ item.semen.bull_name }}</p>
                  <p class="text-sm text-green-600 font-medium">₨{{ item.semen.price }} per straw</p>
                </div>

                <!-- Quantity Controls -->
                <div class="flex items-center space-x-2">
                  <button
                    @click="decrementQuantity(index)"
                    :disabled="item.quantity <= 1"
                    class="w-8 h-8 rounded-full bg-gray-200 hover:bg-gray-300 disabled:bg-gray-100 disabled:cursor-not-allowed flex items-center justify-center"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                    </svg>
                  </button>
                  <span class="w-8 text-center font-medium">{{ item.quantity }}</span>
                  <button
                    @click="incrementQuantity(index)"
                    :disabled="item.semen && Number(item.semen.quantity_available) ? item.quantity >= Number(item.semen.quantity_available) : false"
                    class="w-8 h-8 rounded-full bg-gray-200 hover:bg-gray-300 disabled:bg-gray-100 disabled:cursor-not-allowed flex items-center justify-center"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                  </button>
                </div>

                <!-- Total Price -->
                <div class="text-right">
                  <p class="font-semibold text-gray-900">₨{{ (item.semen.price * item.quantity).toFixed(2) }}</p>
                </div>

                <!-- Remove Button -->
                <button
                  @click="removeItem(index)"
                  class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                >
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                  </svg>
                </button>
              </div>
            </div>
          </div>

          <!-- Cow Details Form -->
          <div class="bg-white/70 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Cow Details</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div v-for="(item, index) in cartItems" :key="index" class="space-y-4">
                <h3 class="font-semibold text-gray-900">{{ item.semen.name }}</h3>
                
                <!-- Heat Start Time - Full Width on Top -->
                <div class="mb-3">
                  <label class="block text-xs font-medium text-gray-600 mb-1.5">Heat Start Time *</label>
                  <input
                    v-model="item.heat_started_at"
                    type="datetime-local"
                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                  />
                </div>
                
                <!-- Cow Type and Cow Age - Two Columns Below -->
                <div class="grid grid-cols-2 gap-3">
                  <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">Cow Type (optional)</label>
                    <select
                      v-model="item.cow_type"
                      class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                    >
                      <option value="">Select cow type</option>
                      <option value="heifer">Heifer</option>
                      <option value="cow">Cow</option>
                    </select>
                  </div>

                  <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">Cow Age (months)</label>
                    <input
                      v-model.number="item.cow_age"
                      type="number"
                      min="1"
                      class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                      placeholder="Enter age"
                    />
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Farm Selection -->
          <div class="bg-white/70 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Select Farm</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
              <div
                v-for="farm in farms"
                :key="farm.id"
                @click="selectedFarm = farm.id"
                :class="[
                  'p-4 border-2 rounded-xl cursor-pointer transition-all duration-200',
                  selectedFarm === farm.id
                    ? 'border-green-500 bg-green-50'
                    : 'border-gray-200 hover:border-gray-300'
                ]"
              >
                <h3 class="font-semibold text-gray-900">{{ farm.name }}</h3>
                <p class="text-sm text-gray-600">{{ farm.father_name ? `Father: ${farm.father_name}` : '' }}</p>
                <p class="text-sm text-gray-500">Cows: {{ farm.number_of_cows }}</p>
                <p class="text-sm text-gray-500">Location: {{ farm.latitude }}, {{ farm.longitude }}</p>
              </div>
            </div>
          </div>

          <!-- Technician Selection -->
          <div class="bg-white/70 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Select Technician</h2>
            
            <!-- Selection Method Toggle -->
            <div class="mb-6">
              <div class="flex space-x-4">
                <button
                  @click="technicianSelectionMethod = 'nearby'"
                  :class="[
                    'px-4 py-2 rounded-lg font-medium transition-colors',
                    technicianSelectionMethod === 'nearby'
                      ? 'bg-green-600 text-white'
                      : 'bg-gray-200 text-gray-700 hover:bg-gray-300'
                  ]"
                >
                  Nearby Technicians (30km)
                </button>
                <button
                  @click="technicianSelectionMethod = 'manual'"
                  :class="[
                    'px-4 py-2 rounded-lg font-medium transition-colors',
                    technicianSelectionMethod === 'manual'
                      ? 'bg-green-600 text-white'
                      : 'bg-gray-200 text-gray-700 hover:bg-gray-300'
                  ]"
                >
                  Manual Search
                </button>
              </div>
            </div>

            <!-- Nearby Technicians -->
            <div v-if="technicianSelectionMethod === 'nearby'" class="space-y-4">
              <div v-if="nearbyTechnicians.length === 0 && !loadingNearbyTechnicians" class="text-center py-8">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <p class="mt-2 text-gray-500">No technicians found within 30km radius</p>
                <p class="text-sm text-gray-400">Try manual search instead</p>
              </div>
              
              <div v-if="loadingNearbyTechnicians" class="text-center py-8">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-green-600 mx-auto"></div>
                <p class="mt-2 text-gray-500">Finding nearby technicians...</p>
              </div>

              <div v-if="nearbyTechnicians.length > 0" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div
                  v-for="technician in nearbyTechnicians"
                  :key="technician.id"
                  @click="selectedTechnician = technician.id"
                  :class="[
                    'p-4 border-2 rounded-xl cursor-pointer transition-all duration-200',
                    selectedTechnician === technician.id
                      ? 'border-green-500 bg-green-50'
                      : 'border-gray-200 hover:border-gray-300'
                  ]"
                >
                  <div class="flex items-start justify-between">
                    <div class="flex-1">
                      <h3 class="font-semibold text-gray-900">{{ technician.name }}</h3>
                      <p class="text-sm text-gray-600">{{ technician.mobile_1 }}</p>
                      <p class="text-sm text-gray-500">{{ technician.years_of_experience }} years experience</p>
                      <p class="text-sm text-gray-500">{{ technician.district }}, {{ technician.province }}</p>
                    </div>
                    <div class="text-right">
                      <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        {{ technician.distance }}km away
                      </span>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Manual Search -->
            <div v-if="technicianSelectionMethod === 'manual'" class="space-y-4">
              <!-- Search Filters -->
              <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-2">Province</label>
                  <select
                    v-model="searchFilters.province"
                    @change="onProvinceChange"
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                  >
                    <option value="">All Provinces</option>
                    <option v-for="province in getProvinces()" :key="province" :value="province">
                      {{ province }}
                    </option>
                  </select>
                </div>

                <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-2">Division</label>
                  <select
                    v-model="searchFilters.division"
                    @change="onDivisionChange"
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                  >
                    <option value="">All Divisions</option>
                    <option v-for="division in getDivisionsForProvince(searchFilters.province)" :key="division" :value="division">
                      {{ division }}
                    </option>
                  </select>
                </div>

                <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-2">District</label>
                  <select
                    v-model="searchFilters.district"
                    @change="onDistrictChange"
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                  >
                    <option value="">All Districts</option>
                    <option v-for="district in getDistrictsForDivision(searchFilters.division)" :key="district" :value="district">
                      {{ district }}
                    </option>
                  </select>
                </div>

                <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-2">Tehsil/City</label>
                  <input
                    v-model="searchFilters.tehsil_city"
                    @input="loadTechnicians"
                    type="text"
                    placeholder="Enter tehsil/city"
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                  >
                </div>
              </div>

              <!-- Search Results -->
              <div v-if="manualTechnicians.length === 0 && !loadingManualTechnicians && hasSearched" class="text-center py-8">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <p class="mt-2 text-gray-500">No technicians found with selected criteria</p>
                <p class="text-sm text-gray-400">Try adjusting your search filters</p>
              </div>
              
              <div v-if="loadingManualTechnicians" class="text-center py-8">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-green-600 mx-auto"></div>
                <p class="mt-2 text-gray-500">Searching technicians...</p>
              </div>

              <div v-if="manualTechnicians.length > 0" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div
                  v-for="technician in manualTechnicians"
                  :key="technician.id"
                  @click="selectedTechnician = technician.id"
                  :class="[
                    'p-4 border-2 rounded-xl cursor-pointer transition-all duration-200',
                    selectedTechnician === technician.id
                      ? 'border-green-500 bg-green-50'
                      : 'border-gray-200 hover:border-gray-300'
                  ]"
                >
                  <div class="flex items-start justify-between">
                    <div class="flex-1">
                      <h3 class="font-semibold text-gray-900">{{ technician.name }}</h3>
                      <p class="text-sm text-gray-600">{{ technician.mobile_1 }}</p>
                      <p class="text-sm text-gray-500">{{ technician.years_of_experience }} years experience</p>
                      <p class="text-sm text-gray-500">{{ technician.city }}, {{ technician.tehsil }}, {{ technician.district }}, {{ technician.province }}</p>
                    </div>
                    <div class="text-right">
                      <span :class="getAvailabilityBadgeClass(technician.availability)" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium">
                        {{ technician.availability }}
                      </span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Order Summary -->
          <div class="bg-white/70 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Order Summary</h2>
            
            <div class="space-y-4">
              <div class="flex justify-between">
                <span class="text-gray-600">Semen Total</span>
                <span class="font-semibold">₨{{ subtotal.toFixed(2) }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-600">Visit Fee</span>
                <span class="font-semibold">₨{{ visitFee.toFixed(2) }}</span>
              </div>
              <div class="border-t pt-4">
                <div class="flex justify-between text-lg font-bold">
                  <span>Total</span>
                  <span class="text-green-600">₨{{ total.toFixed(2) }}</span>
                </div>
              </div>
            </div>

            <!-- Notes -->
            <div class="mt-6">
              <label class="block text-sm font-semibold text-gray-700 mb-2">Additional Notes</label>
              <textarea
                v-model="notes"
                rows="3"
                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                placeholder="Any additional notes for the technician..."
              ></textarea>
            </div>

            <!-- Place Order Button -->
            <div class="mt-6">
              <button
                @click="placeOrder"
                :disabled="!canPlaceOrder"
                class="w-full bg-green-600 text-white px-6 py-4 rounded-xl hover:bg-green-700 disabled:bg-gray-300 disabled:cursor-not-allowed transition-colors font-semibold text-lg"
              >
                Place Order
              </button>
            </div>
          </div>
        </div>

        <!-- Empty Cart -->
        <div v-else class="text-center py-12">
          <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6m8 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01"></path>
          </svg>
          <h3 class="mt-2 text-sm font-medium text-gray-900">Your cart is empty</h3>
          <p class="mt-1 text-sm text-gray-500">Add some semen to get started.</p>
          <div class="mt-6">
            <button
              @click="$router.push('/farmer/semen-catalog')"
              class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors"
            >
              Browse Semen Catalog
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, computed, onMounted, watch } from 'vue'
import { useRouter } from 'vue-router'

export default {
  name: 'OrderCart',
  setup() {
    const router = useRouter()
    const cartItems = ref([])
    const farms = ref([])
    const selectedFarm = ref(null)
    const notes = ref('')
    const visitFee = 50.00
    
    // Technician selection
    const technicianSelectionMethod = ref('nearby')
    const selectedTechnician = ref(null)
    const nearbyTechnicians = ref([])
    const manualTechnicians = ref([])
    const loadingNearbyTechnicians = ref(false)
    const loadingManualTechnicians = ref(false)
    const hasSearched = ref(false)
    
    // Search filters
    const searchFilters = ref({
      province: '',
      division: '',
      district: '',
      tehsil_city: ''
    })
    
    // Pakistan administrative divisions and districts data
    const pakistanData = {
      'Punjab': {
        divisions: ['Lahore', 'Rawalpindi', 'Faisalabad', 'Multan', 'Sargodha', 'Bahawalpur', 'Dera Ghazi Khan', 'Sahiwal', 'Gujranwala'],
        districts: {
          'Lahore': ['Lahore', 'Kasur', 'Nankana Sahib', 'Sheikhupura'],
          'Rawalpindi': ['Rawalpindi', 'Attock', 'Chakwal', 'Jhelum', 'Talagang'],
          'Faisalabad': ['Faisalabad', 'Chiniot', 'Jhang', 'Toba Tek Singh'],
          'Multan': ['Multan', 'Khanewal', 'Lodhran', 'Vehari'],
          'Sargodha': ['Sargodha', 'Khushab', 'Mianwali', 'Bhakkar'],
          'Bahawalpur': ['Bahawalpur', 'Bahawalnagar', 'Rahim Yar Khan'],
          'Dera Ghazi Khan': ['Dera Ghazi Khan', 'Layyah', 'Muzaffargarh', 'Rajanpur'],
          'Sahiwal': ['Sahiwal', 'Okara', 'Pakpattan'],
          'Gujranwala': ['Gujranwala', 'Gujrat', 'Hafizabad', 'Mandi Bahauddin', 'Narowal', 'Sialkot']
        },
        tehsilCities: {
          'Lahore': ['Lahore', 'Kasur', 'Nankana Sahib', 'Sheikhupura'],
          'Rawalpindi': ['Rawalpindi', 'Attock', 'Chakwal', 'Jhelum', 'Talagang'],
          'Faisalabad': ['Faisalabad', 'Chiniot', 'Jhang', 'Toba Tek Singh'],
          'Multan': ['Multan', 'Khanewal', 'Lodhran', 'Vehari'],
          'Sargodha': ['Sargodha', 'Khushab', 'Mianwali', 'Bhakkar'],
          'Bahawalpur': ['Bahawalpur', 'Bahawalnagar', 'Rahim Yar Khan'],
          'Dera Ghazi Khan': ['Dera Ghazi Khan', 'Layyah', 'Muzaffargarh', 'Rajanpur'],
          'Sahiwal': ['Sahiwal', 'Okara', 'Pakpattan'],
          'Gujranwala': ['Gujranwala', 'Gujrat', 'Hafizabad', 'Mandi Bahauddin', 'Narowal', 'Sialkot']
        }
      },
      'Sindh': {
        divisions: ['Karachi', 'Hyderabad', 'Sukkur', 'Larkana', 'Mirpur Khas', 'Shaheed Benazirabad'],
        districts: {
          'Karachi': ['Karachi East', 'Karachi West', 'Karachi South', 'Karachi Central', 'Malir', 'Korangi'],
          'Hyderabad': ['Hyderabad', 'Tando Allahyar', 'Tando Muhammad Khan', 'Matli', 'Badin', 'Tharparkar', 'Umerkot'],
          'Sukkur': ['Sukkur', 'Ghotki', 'Khairpur', 'Naushahro Feroze', 'Shaheed Benazirabad'],
          'Larkana': ['Larkana', 'Kamber Shahdadkot', 'Shikarpur', 'Jacobabad', 'Kashmore'],
          'Mirpur Khas': ['Mirpur Khas', 'Tharparkar', 'Umerkot', 'Sanghar'],
          'Shaheed Benazirabad': ['Nawabshah', 'Sanghar', 'Naushahro Feroze']
        },
        tehsilCities: {
          'Karachi East': ['Karachi East', 'Karachi West', 'Karachi South', 'Karachi Central', 'Malir', 'Korangi'],
          'Hyderabad': ['Hyderabad', 'Tando Allahyar', 'Tando Muhammad Khan', 'Matli', 'Badin', 'Tharparkar', 'Umerkot'],
          'Sukkur': ['Sukkur', 'Ghotki', 'Khairpur', 'Naushahro Feroze', 'Shaheed Benazirabad'],
          'Larkana': ['Larkana', 'Kamber Shahdadkot', 'Shikarpur', 'Jacobabad', 'Kashmore'],
          'Mirpur Khas': ['Mirpur Khas', 'Tharparkar', 'Umerkot', 'Sanghar'],
          'Nawabshah': ['Nawabshah', 'Sanghar', 'Naushahro Feroze']
        }
      },
      'KPK': {
        divisions: ['Peshawar', 'Mardan', 'Malakand', 'Hazara', 'Kohat', 'Bannu', 'Dera Ismail Khan'],
        districts: {
          'Peshawar': ['Peshawar', 'Charsadda', 'Nowshera'],
          'Mardan': ['Mardan', 'Swabi'],
          'Malakand': ['Malakand', 'Swat', 'Buner', 'Shangla', 'Upper Dir', 'Lower Dir'],
          'Hazara': ['Abbottabad', 'Mansehra', 'Battagram', 'Kohistan', 'Torghar'],
          'Kohat': ['Kohat', 'Hangu', 'Karak', 'Kurram', 'Orakzai'],
          'Bannu': ['Bannu', 'Lakki Marwat', 'North Waziristan', 'South Waziristan'],
          'Dera Ismail Khan': ['Dera Ismail Khan', 'Tank']
        },
        tehsilCities: {
          'Peshawar': ['Peshawar', 'Charsadda', 'Nowshera'],
          'Mardan': ['Mardan', 'Swabi'],
          'Malakand': ['Malakand', 'Swat', 'Buner', 'Shangla', 'Upper Dir', 'Lower Dir'],
          'Abbottabad': ['Abbottabad', 'Mansehra', 'Battagram', 'Kohistan', 'Torghar'],
          'Kohat': ['Kohat', 'Hangu', 'Karak', 'Kurram', 'Orakzai'],
          'Bannu': ['Bannu', 'Lakki Marwat', 'North Waziristan', 'South Waziristan'],
          'Dera Ismail Khan': ['Dera Ismail Khan', 'Tank']
        }
      },
      'Balochistan': {
        divisions: ['Quetta', 'Kalat', 'Makran', 'Sibi', 'Nasirabad', 'Zhob'],
        districts: {
          'Quetta': ['Quetta', 'Pishin', 'Killa Abdullah', 'Chagai'],
          'Kalat': ['Kalat', 'Mastung', 'Khuzdar', 'Lasbela', 'Awaran', 'Washuk'],
          'Makran': ['Kech', 'Gwadar', 'Panjgur'],
          'Sibi': ['Sibi', 'Kohlu', 'Dera Bugti', 'Barkhan'],
          'Nasirabad': ['Nasirabad', 'Jaffarabad', 'Jhal Magsi', 'Kachhi'],
          'Zhob': ['Zhob', 'Sherani', 'Musa Khel', 'Loralai', 'Duki', 'Harnai', 'Ziarat']
        },
        tehsilCities: {
          'Quetta': ['Quetta', 'Pishin', 'Killa Abdullah', 'Chagai'],
          'Kalat': ['Kalat', 'Mastung', 'Khuzdar', 'Lasbela', 'Awaran', 'Washuk'],
          'Kech': ['Kech', 'Gwadar', 'Panjgur'],
          'Sibi': ['Sibi', 'Kohlu', 'Dera Bugti', 'Barkhan'],
          'Nasirabad': ['Nasirabad', 'Jaffarabad', 'Jhal Magsi', 'Kachhi'],
          'Zhob': ['Zhob', 'Sherani', 'Musa Khel', 'Loralai', 'Duki', 'Harnai', 'Ziarat']
        }
      },
      'Islamabad': {
        divisions: ['Islamabad'],
        districts: {
          'Islamabad': ['Islamabad']
        },
        tehsilCities: {
          'Islamabad': ['Islamabad']
        }
      }
    }

    const normalizeItem = (item) => {
      const quantityAvailable = Number(item?.semen?.quantity_available)
      return {
        ...item,
        quantity: Math.max(1, parseInt(item.quantity || 1)),
        semen: {
          ...item.semen,
          price: Number(item?.semen?.price) || 0,
          quantity_available: Number.isFinite(quantityAvailable) ? quantityAvailable : Infinity
        }
      }
    }

    const persistCart = () => {
      localStorage.setItem('farmer_cart', JSON.stringify(cartItems.value))
      window.dispatchEvent(new CustomEvent('cartChanged'))
    }

    const subtotal = computed(() => {
      return cartItems.value.reduce((total, item) => {
        return total + (Number(item.semen.price) * Number(item.quantity))
      }, 0)
    })

    const total = computed(() => subtotal.value + visitFee)

    const canPlaceOrder = computed(() => {
      if (cartItems.value.length === 0) return false
      if (!selectedFarm.value) return false
      if (!selectedTechnician.value) return false
      
      return cartItems.value.every(item => 
        item.heat_started_at
      )
    })

    const loadFarms = async () => {
      try {
        const response = await window.axios.get('/farmer/farms')
        farms.value = response.data.farms
      } catch (error) {
        console.error('Error loading farms:', error)
      }
    }

    const loadNearbyTechnicians = async () => {
      if (!selectedFarm.value) return
      
      try {
        loadingNearbyTechnicians.value = true
        const farm = farms.value.find(f => f.id === selectedFarm.value)
        if (!farm) return
        
        const response = await window.axios.get('/technicians/nearby', {
          params: {
            latitude: farm.latitude,
            longitude: farm.longitude,
            radius: 30 // 30km radius
          }
        })
        nearbyTechnicians.value = response.data.technicians || []
      } catch (error) {
        console.error('Error loading nearby technicians:', error)
        nearbyTechnicians.value = []
      } finally {
        loadingNearbyTechnicians.value = false
      }
    }

    // Helper functions for location dropdowns
    const getProvinces = () => {
      return Object.keys(pakistanData)
    }

    const getDivisionsForProvince = (province) => {
      if (!province) {
        // If no province selected, show all divisions from all provinces
        const allDivisions = []
        for (const prov in pakistanData) {
          allDivisions.push(...pakistanData[prov].divisions)
        }
        return allDivisions.sort()
      }
      if (!pakistanData[province]) return []
      return pakistanData[province].divisions || []
    }

    const getDistrictsForDivision = (division) => {
      if (!division) {
        // If no division selected, show all districts from all divisions
        const allDistricts = []
        for (const province in pakistanData) {
          for (const div in pakistanData[province].districts) {
            allDistricts.push(...pakistanData[province].districts[div])
          }
        }
        return allDistricts.sort()
      }
      
      // Find the province that contains this division
      for (const province in pakistanData) {
        if (pakistanData[province].divisions.includes(division)) {
          return pakistanData[province].districts[division] || []
        }
      }
      return []
    }


    // Event handlers for cascading dropdowns
    const onProvinceChange = () => {
      searchFilters.value.division = ''
      searchFilters.value.district = ''
      loadTechnicians()
    }

    const onDivisionChange = () => {
      searchFilters.value.district = ''
      loadTechnicians()
    }

    const onDistrictChange = () => {
      loadTechnicians()
    }

    const loadTechnicians = async () => {
      try {
        loadingManualTechnicians.value = true
        hasSearched.value = true
        
        const params = new URLSearchParams()
        if (searchFilters.value.province) params.append('province', searchFilters.value.province)
        if (searchFilters.value.division) params.append('division', searchFilters.value.division)
        if (searchFilters.value.district) params.append('district', searchFilters.value.district)
        if (searchFilters.value.tehsil_city) params.append('tehsil_city', searchFilters.value.tehsil_city)
        params.append('status', 'active') // Only active technicians
        
        const response = await window.axios.get(`/technicians/search?${params}`)
        manualTechnicians.value = response.data.technicians || []
      } catch (error) {
        console.error('Error loading technicians:', error)
        manualTechnicians.value = []
      } finally {
        loadingManualTechnicians.value = false
      }
    }


    const getAvailabilityBadgeClass = (availability) => {
      const classes = {
        online: 'bg-green-100 text-green-800',
        offline: 'bg-gray-100 text-gray-800',
        on_leave: 'bg-yellow-100 text-yellow-800'
      }
      return classes[availability] || 'bg-gray-100 text-gray-800'
    }

    const setQuantity = (index, quantity) => {
      const item = normalizeItem(cartItems.value[index])
      const max = item.semen.quantity_available
      const newQty = Math.min(Math.max(1, parseInt(quantity || 1)), max)
      cartItems.value[index].quantity = newQty
      // Force reactivity by replacing array reference
      cartItems.value = cartItems.value.map((ci, i) => i === index ? { ...ci } : ci)
      persistCart()
    }

    const incrementQuantity = (index) => {
      const current = parseInt(cartItems.value[index].quantity || 1)
      setQuantity(index, current + 1)
    }

    const decrementQuantity = (index) => {
      const current = parseInt(cartItems.value[index].quantity || 1)
      setQuantity(index, current - 1)
    }

    const updateQuantity = (index, newQuantity) => {
      // Keep old API but delegate to setQuantity
      setQuantity(index, newQuantity)
    }

    const removeItem = (index) => {
      cartItems.value.splice(index, 1)
      cartItems.value = [...cartItems.value]
      persistCart()
    }

    const placeOrder = async () => {
      try {
        const orderData = {
          cattle_farm_id: selectedFarm.value,
          technician_id: selectedTechnician.value,
          order_items: cartItems.value.map(item => ({
            bull_semen_id: item.semen.id,
            quantity: parseInt(item.quantity),
            cow_type: item.cow_type,
            cow_age: item.cow_age,
            heat_started_at: item.heat_started_at
          })),
          notes: notes.value
        }

        const response = await window.axios.post('/orders', orderData)
        
        // Clear cart and localStorage
        cartItems.value = []
        localStorage.removeItem('farmer_cart')
        window.dispatchEvent(new CustomEvent('cartChanged'))
        
        router.push('/farmer/dashboard')
        showSuccess('Order placed successfully!')
      } catch (error) {
        console.error('Error placing order:', error)
        showError('Failed to place order. Please try again.')
      }
    }

    // Watchers
    watch(selectedFarm, (newFarmId) => {
      if (newFarmId && technicianSelectionMethod.value === 'nearby') {
        loadNearbyTechnicians()
      }
    })

    watch(technicianSelectionMethod, (newMethod) => {
      if (newMethod === 'nearby' && selectedFarm.value) {
        loadNearbyTechnicians()
      } else if (newMethod === 'manual') {
        hasSearched.value = false
        manualTechnicians.value = []
      }
    })

    onMounted(() => {
      loadFarms()
      const savedCart = localStorage.getItem('farmer_cart')
      if (savedCart) {
        const parsed = JSON.parse(savedCart).map(normalizeItem)
        cartItems.value = parsed
      }
    })

    return {
      cartItems,
      farms,
      selectedFarm,
      notes,
      visitFee,
      subtotal,
      total,
      canPlaceOrder,
      // quantity controls
      updateQuantity,
      incrementQuantity,
      decrementQuantity,
      removeItem,
      placeOrder,
      // Technician selection
      technicianSelectionMethod,
      selectedTechnician,
      nearbyTechnicians,
      manualTechnicians,
      loadingNearbyTechnicians,
      loadingManualTechnicians,
      hasSearched,
      searchFilters,
      pakistanData,
      getProvinces,
      getDivisionsForProvince,
      getDistrictsForDivision,
      onProvinceChange,
      onDivisionChange,
      onDistrictChange,
      loadNearbyTechnicians,
      loadTechnicians,
      getAvailabilityBadgeClass
    }
  }
}
</script>

