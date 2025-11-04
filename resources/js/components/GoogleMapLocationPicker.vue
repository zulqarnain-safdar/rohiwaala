<template>
  <div class="google-map-location-picker">
    <div v-if="!readonly && !hideLocationControls" class="mb-4">
      <label class="block text-sm font-semibold text-gray-700 mb-2">
        Select Farm Location
      </label>
      <div class="flex space-x-2 mb-2">
        <button
          @click="getCurrentLocation"
          type="button"
          class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200 flex items-center"
        >
          <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 111.314 0z"></path>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
          </svg>
          Use Current Location
        </button>
        <button
          @click="searchLocation"
          type="button"
          class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors duration-200 flex items-center"
        >
          <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
          </svg>
          Search Location
        </button>
      </div>
      <input
        ref="searchInputRef"
        v-model="searchQuery"
        @keyup.enter="searchLocation"
        type="text"
        placeholder="Search for a location..."
        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
      />
    </div>
    
    <div class="relative">
      <div ref="mapContainer" class="w-full h-96 rounded-xl border border-gray-300"></div>
      <div v-if="loading" class="absolute inset-0 flex items-center justify-center bg-white bg-opacity-75 rounded-xl">
        <div class="flex items-center space-x-2">
          <svg class="animate-spin h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          <span class="text-gray-600">Loading map...</span>
        </div>
      </div>
    </div>
    
    <div class="mt-4 grid grid-cols-2 gap-4">
      <div>
        <label class="block text-sm font-semibold text-gray-700 mb-2">Latitude</label>
        <input
          v-model="coordinates.lat"
          type="number"
          step="any"
          readonly
          class="w-full px-4 py-3 border border-gray-300 rounded-xl bg-gray-50 text-gray-600"
        />
      </div>
      <div>
        <label class="block text-sm font-semibold text-gray-700 mb-2">Longitude</label>
        <input
          v-model="coordinates.lng"
          type="number"
          step="any"
          readonly
          class="w-full px-4 py-3 border border-gray-300 rounded-xl bg-gray-50 text-gray-600"
        />
      </div>
    </div>
    
    <div v-if="selectedAddress" class="mt-4 p-3 bg-green-50 border border-green-200 rounded-xl">
      <p class="text-sm text-green-800">
        <strong>Selected Location:</strong> {{ selectedAddress }}
      </p>
    </div>
  </div>
</template>

<script>
import { ref, onMounted, onUnmounted, watch, nextTick } from 'vue'

export default {
  name: 'GoogleMapLocationPicker',
  props: {
    modelValue: {
      type: Object,
      default: () => ({ lat: null, lng: null })
    },
    initialLat: {
      type: Number,
      default: 31.5204 // Default to Lahore, Pakistan
    },
    initialLng: {
      type: Number,
      default: 74.3587
    },
    readonly: {
      type: Boolean,
      default: false
    },
    hideLocationControls: {
      type: Boolean,
      default: false
    }
  },
  emits: ['update:modelValue'],
  setup(props, { emit }) {
    const mapContainer = ref(null)
    const searchInputRef = ref(null)
    const loading = ref(true)
    const searchQuery = ref('')
    const selectedAddress = ref('')
    const coordinates = ref({
      lat: props.modelValue.lat || props.initialLat,
      lng: props.modelValue.lng || props.initialLng
    })
    
    let map = null
    let marker = null
    let geocoder = null
    let placesService = null
    let searchBox = null
    let mapClickListener = null
    let markerDragListener = null

    // Helper to compare numbers with precision to avoid loops
    const isSameCoord = (a, b) => Number(a).toFixed(6) === Number(b).toFixed(6)
    
    // Helper to ensure marker is in correct readonly state
    const ensureMarkerReadonlyState = () => {
      if (marker) {
        const shouldBeDraggable = !props.readonly
        marker.setDraggable(shouldBeDraggable)
        console.log('Ensured marker draggable state:', shouldBeDraggable, 'for readonly:', props.readonly)
      }
    }

    // Initialize Google Maps
    const initMap = () => {
      if (!window.google) {
        console.error('Google Maps API not loaded')
        loading.value = false
        return
      }

      console.log('Initializing map with readonly:', props.readonly)

      const mapOptions = {
        center: { lat: coordinates.value.lat, lng: coordinates.value.lng },
        zoom: 15,
        mapTypeId: 'roadmap',
        styles: [
          {
            featureType: 'poi',
            elementType: 'labels',
            stylers: [{ visibility: 'off' }]
          }
        ],
        // Disable interactions when in readonly mode
        draggable: !props.readonly,
        zoomControl: !props.readonly,
        scrollwheel: !props.readonly,
        disableDoubleClickZoom: props.readonly,
        streetViewControl: !props.readonly,
        mapTypeControl: !props.readonly,
        fullscreenControl: !props.readonly
      }

      map = new google.maps.Map(mapContainer.value, mapOptions)
      geocoder = new google.maps.Geocoder()
      placesService = new google.maps.places.PlacesService(map)

      // Create marker
      marker = new google.maps.Marker({
        position: { lat: coordinates.value.lat, lng: coordinates.value.lng },
        map: map,
        draggable: !props.readonly,
        title: 'Farm Location'
      })
      
      console.log('Created marker with draggable:', !props.readonly, 'readonly prop:', props.readonly)
      
      // Ensure marker is in correct readonly state
      ensureMarkerReadonlyState()
      
      // Double-check marker draggable state after a short delay
      setTimeout(() => {
        ensureMarkerReadonlyState()
      }, 100)

      // Add click listener to map (only if not readonly)
      if (!props.readonly) {
        mapClickListener = map.addListener('click', (event) => {
          const lat = event.latLng.lat()
          const lng = event.latLng.lng()
          updateLocation(lat, lng, false)
        })

        // Add drag listener to marker
        markerDragListener = marker.addListener('dragend', (event) => {
          const lat = event.latLng.lat()
          const lng = event.latLng.lng()
          updateLocation(lat, lng, false)
        })

        // Initialize search box using scoped input
        if (searchInputRef.value) {
          searchBox = new google.maps.places.SearchBox(searchInputRef.value)
          searchBox.addListener('places_changed', handleSearchResults)
        }
      }

      loading.value = false
    }

    // Update location coordinates and address
    // emitChange=false indicates user-driven change (emit to parent)
    const updateLocation = (lat, lng, fromParent = false) => {
      const newLat = parseFloat(Number(lat).toFixed(6))
      const newLng = parseFloat(Number(lng).toFixed(6))

      if (isSameCoord(coordinates.value.lat, newLat) && isSameCoord(coordinates.value.lng, newLng)) {
        // No effective change
        return
      }

      coordinates.value.lat = newLat
      coordinates.value.lng = newLng
      
      // Update marker position
      if (marker) {
        marker.setPosition({ lat: newLat, lng: newLng })
        // Ensure marker is still in correct readonly state after position update
        ensureMarkerReadonlyState()
      }
      
      // Update map center
      if (map) {
        map.setCenter({ lat: newLat, lng: newLng })
      }
      
      // Get address from coordinates
      getAddressFromCoordinates(newLat, newLng)
      
      // Emit updated coordinates only if change did not originate from parent
      if (!fromParent) {
        emit('update:modelValue', { lat: newLat, lng: newLng })
      }
    }

    // Get address from coordinates
    const getAddressFromCoordinates = (lat, lng) => {
      if (!geocoder) return
      
      geocoder.geocode({ location: { lat, lng } }, (results, status) => {
        if (status === 'OK' && results[0]) {
          selectedAddress.value = results[0].formatted_address
        }
      })
    }

    // Helper functions for notifications
    const showInfo = (message) => {
      console.info(message)
      // You can replace this with your preferred notification system
    }
    
    const showWarning = (message) => {
      console.warn(message)
      // You can replace this with your preferred notification system
    }

    // Get current location using geolocation
    const getCurrentLocation = () => {
      if (!navigator.geolocation) {
        showInfo('Geolocation is not supported by this browser.')
        return
      }

      loading.value = true
      navigator.geolocation.getCurrentPosition(
        (position) => {
          const lat = position.coords.latitude
          const lng = position.coords.longitude
          updateLocation(lat, lng, false)
          loading.value = false
          // Emit event to parent component
          emit('get-current-location', { lat, lng })
        },
        (error) => {
          console.error('Error getting location:', error)
          showWarning('Unable to retrieve your location. Please try again or select manually.')
          loading.value = false
          // Emit error event to parent component
          emit('get-current-location-error', error)
        },
        {
          enableHighAccuracy: true,
          timeout: 10000,
          maximumAge: 300000
        }
      )
    }

    // Search for location
    const searchLocation = () => {
      if (!searchQuery.value.trim()) return
      if (!geocoder) return
      
      loading.value = true
      geocoder.geocode({ address: searchQuery.value }, (results, status) => {
        if (status === 'OK' && results[0]) {
          const location = results[0].geometry.location
          const lat = location.lat()
          const lng = location.lng()
          updateLocation(lat, lng, false)
          selectedAddress.value = results[0].formatted_address
        } else {
          showWarning('Location not found. Please try a different search term.')
        }
        loading.value = false
      })
    }

    // Handle search results from search box
    const handleSearchResults = () => {
      const places = searchBox.getPlaces()
      if (!places || places.length === 0) return

      const place = places[0]
      if (place.geometry && place.geometry.location) {
        const lat = place.geometry.location.lat()
        const lng = place.geometry.location.lng()
        updateLocation(lat, lng, false)
        selectedAddress.value = place.formatted_address || place.name
      }
    }

    // Watch for prop changes coming from parent (avoid emit loop)
    watch(() => props.modelValue, (newValue) => {
      if (newValue && newValue.lat && newValue.lng) {
        if (!isSameCoord(coordinates.value.lat, newValue.lat) || !isSameCoord(coordinates.value.lng, newValue.lng)) {
          updateLocation(newValue.lat, newValue.lng, true)
        }
      }
    }, { deep: true })

    // Watch for readonly prop changes and update map settings
    watch(() => props.readonly, (newReadonly) => {
      if (map) {
        console.log('Updating map readonly settings to:', newReadonly)
        map.setOptions({
          draggable: !newReadonly,
          zoomControl: !newReadonly,
          scrollwheel: !newReadonly,
          disableDoubleClickZoom: newReadonly,
          streetViewControl: !newReadonly,
          mapTypeControl: !newReadonly,
          fullscreenControl: !newReadonly
        })
        
        if (marker) {
          ensureMarkerReadonlyState()
          
          // Double-check after a short delay
          setTimeout(() => {
            ensureMarkerReadonlyState()
          }, 50)
        }

        // Remove event listeners when switching to readonly mode
        if (newReadonly) {
          if (mapClickListener) {
            google.maps.event.removeListener(mapClickListener)
            mapClickListener = null
          }
          if (markerDragListener) {
            google.maps.event.removeListener(markerDragListener)
            markerDragListener = null
          }
        } else {
          // Add event listeners when switching to editable mode
          if (!mapClickListener) {
            mapClickListener = map.addListener('click', (event) => {
              const lat = event.latLng.lat()
              const lng = event.latLng.lng()
              updateLocation(lat, lng, false)
            })
          }
          if (!markerDragListener && marker) {
            markerDragListener = marker.addListener('dragend', (event) => {
              const lat = event.latLng.lat()
              const lng = event.latLng.lng()
              updateLocation(lat, lng, false)
            })
          }
        }
      }
    })

    // Load Google Maps API
    const loadGoogleMapsAPI = () => {
      if (window.google && window.google.maps) {
        initMap()
        return
      }

      const existing = document.querySelector('script[data-google-maps-loader]')
      if (existing) {
        // Another instance is loading; wait for callback
        window.initGoogleMaps = initMap
        return
      }

      const script = document.createElement('script')
      script.src = `https://maps.googleapis.com/maps/api/js?key=${import.meta.env.VITE_GOOGLE_MAPS_API_KEY}&libraries=places&callback=initGoogleMaps`
      script.async = true
      script.defer = true
      script.setAttribute('data-google-maps-loader', 'true')
      
      window.initGoogleMaps = initMap
      document.head.appendChild(script)
    }

    onMounted(() => {
      nextTick(() => {
        loadGoogleMapsAPI()
        
        // Automatically get current location if readonly and controls are hidden
        if (props.readonly && props.hideLocationControls) {
          setTimeout(() => {
            getCurrentLocation()
          }, 1000) // Wait for map to initialize
        }
      })
    })

    onUnmounted(() => {
      if (window.initGoogleMaps) {
        delete window.initGoogleMaps
      }
      
      // Clean up event listeners
      if (mapClickListener) {
        google.maps.event.removeListener(mapClickListener)
      }
      if (markerDragListener) {
        google.maps.event.removeListener(markerDragListener)
      }
    })

    return {
      mapContainer,
      searchInputRef,
      loading,
      searchQuery,
      selectedAddress,
      coordinates,
      getCurrentLocation,
      searchLocation
    }
  }
}
</script>

<style scoped>
.google-map-location-picker {
  @apply w-full;
}

/* Custom marker styles */
:deep(.gm-style-iw) {
  border-radius: 8px;
}

:deep(.gm-style-iw-c) {
  border-radius: 8px;
}

/* Hide Google Maps UI elements we don't need */
:deep(.gm-style-cc) {
  display: none;
}

:deep(.gm-style .gm-style-iw-d) {
  overflow: hidden !important;
}
</style>
