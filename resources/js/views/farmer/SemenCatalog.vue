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
          <h1 class="text-2xl font-bold text-gray-900">Semen Catalog</h1>
        </div>
        <div class="flex items-center space-x-4">
          <div class="text-sm text-gray-600">
            Browse and select semen for your cattle
          </div>
          <button @click="goToCart" class="relative bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6m8 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01"></path>
            </svg>
            <span>View Cart</span>
            <span v-if="cartItemCount > 0" class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-6 w-6 flex items-center justify-center font-bold">
              {{ cartItemCount }}
            </span>
          </button>
        </div>
      </div>
    </div>

    <!-- Filters and Search -->
    <div class="p-6">
      <div class="bg-white/70 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <!-- Search -->
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Search</label>
            <div class="relative">
              <input
                v-model="searchQuery"
                type="text"
                placeholder="Search by name or description..."
                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
              />
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
              </div>
            </div>
          </div>

          <!-- Breed Filter -->
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Breed</label>
            <select
              v-model="selectedBreed"
              class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
            >
              <option value="">All Breeds</option>
              <option v-for="breed in breeds" :key="breed.id" :value="breed.id">
                {{ breed.name }}
              </option>
            </select>
          </div>

          <!-- Sort -->
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Sort By</label>
            <select
              v-model="sortBy"
              class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
            >
              <option value="name">Name</option>
              <option value="price">Price</option>
              <option value="created_at">Newest</option>
            </select>
          </div>
        </div>
      </div>

      <!-- Semen Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        <div
          v-for="semen in filteredSemen"
          :key="semen.id"
          class="group bg-white/70 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2"
        >
          <!-- Semen Image -->
          <div class="aspect-w-16 aspect-h-12 bg-gradient-to-br from-green-100 to-emerald-100">
            <div class="w-full h-48 flex items-center justify-center">
              <svg class="w-16 h-16 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
              </svg>
            </div>
          </div>

          <!-- Semen Details -->
          <div class="p-6">
            <div class="flex items-start justify-between mb-2">
              <h3 class="text-lg font-bold text-gray-900 group-hover:text-green-600 transition-colors">
                {{ semen.name }}
              </h3>
              <span class="bg-green-100 text-green-800 text-xs font-semibold px-2 py-1 rounded-full">
                {{ semen.breed.name }}
              </span>
            </div>

            <p class="text-gray-600 text-sm mb-4 line-clamp-2">
              {{ semen.description }}
            </p>

            <!-- Bull Details -->
            <div class="space-y-2 mb-4">
              <div class="flex justify-between text-sm">
                <span class="text-gray-500">Bull Name:</span>
                <span class="font-medium">{{ semen.bull_name }}</span>
              </div>
              <div class="flex justify-between text-sm">
                <span class="text-gray-500">Age:</span>
                <span class="font-medium">{{ semen.bull_age }} years</span>
              </div>
              <div class="flex justify-between text-sm">
                <span class="text-gray-500">Weight:</span>
                <span class="font-medium">{{ semen.bull_weight }} kg</span>
              </div>
            </div>

            <!-- Price -->
            <div class="flex items-center justify-between mb-4">
              <div>
                <span class="text-2xl font-bold text-green-600">₨{{ semen.price }}</span>
                <span class="text-gray-500 text-sm">per straw</span>
              </div>
              <div class="text-sm text-gray-500">
                Stock: {{ semen.quantity_available }}
              </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex space-x-2">
              <button
                @click="viewDetails(semen)"
                class="flex-1 bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition-colors font-medium"
              >
                View Details
              </button>
              <button
                @click="addToCart(semen)"
                :disabled="semen.quantity_available <= 0"
                class="flex-1 bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 disabled:bg-gray-300 disabled:cursor-not-allowed transition-colors font-medium"
              >
                Add to Cart
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div v-if="filteredSemen.length === 0" class="text-center py-12">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0112 15c-2.34 0-4.29-1.009-5.824-2.709M15 6.291A7.962 7.962 0 0012 4c-2.34 0-4.29 1.009-5.824 2.709"></path>
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">No semen found</h3>
        <p class="mt-1 text-sm text-gray-500">Try adjusting your search or filter criteria.</p>
      </div>
    </div>

    <!-- Semen Detail Modal -->
    <div v-if="selectedSemen" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
      <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6">
          <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-900">{{ selectedSemen.name }}</h2>
            <button @click="selectedSemen = null" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
              <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
              </svg>
            </button>
          </div>

          <div class="space-y-6">
            <!-- Basic Info -->
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="text-sm font-semibold text-gray-500">Breed</label>
                <p class="text-lg font-medium">{{ selectedSemen.breed.name }}</p>
              </div>
              <div>
                <label class="text-sm font-semibold text-gray-500">Price</label>
                <p class="text-lg font-medium text-green-600">₨{{ selectedSemen.price }} per straw</p>
              </div>
            </div>

            <!-- Bull Details -->
            <div>
              <h3 class="text-lg font-semibold text-gray-900 mb-3">Bull Information</h3>
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label class="text-sm font-semibold text-gray-500">Name</label>
                  <p class="text-lg font-medium">{{ selectedSemen.bull_name }}</p>
                </div>
                <div>
                  <label class="text-sm font-semibold text-gray-500">Age</label>
                  <p class="text-lg font-medium">{{ selectedSemen.bull_age }} years</p>
                </div>
                <div>
                  <label class="text-sm font-semibold text-gray-500">Weight</label>
                  <p class="text-lg font-medium">{{ selectedSemen.bull_weight }} kg</p>
                </div>
                <div>
                  <label class="text-sm font-semibold text-gray-500">Available Stock</label>
                  <p class="text-lg font-medium">{{ selectedSemen.quantity_available }} straws</p>
                </div>
              </div>
            </div>

            <!-- Description -->
            <div>
              <label class="text-sm font-semibold text-gray-500">Description</label>
              <p class="text-gray-700 mt-1">{{ selectedSemen.description }}</p>
            </div>

            <!-- Add to Cart -->
            <div class="flex space-x-4">
              <button
                @click="addToCart(selectedSemen)"
                :disabled="selectedSemen.quantity_available <= 0"
                class="flex-1 bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 disabled:bg-gray-300 disabled:cursor-not-allowed transition-colors font-medium"
              >
                Add to Cart
              </button>
              <button
                @click="selectedSemen = null"
                class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium"
              >
                Close
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, computed, onMounted, onUnmounted, onActivated } from 'vue'
import { useRouter } from 'vue-router'

export default {
  name: 'SemenCatalog',
  setup() {
    const router = useRouter()
    const semen = ref([])
    const breeds = ref([])
    const searchQuery = ref('')
    const selectedBreed = ref('')
    const sortBy = ref('name')
    const selectedSemen = ref(null)
    const loading = ref(false)
    const cartItems = ref([])

    const filteredSemen = computed(() => {
      let filtered = semen.value

      // Filter by search query
      if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase()
        filtered = filtered.filter(item => 
          item.name.toLowerCase().includes(query) ||
          item.description.toLowerCase().includes(query) ||
          item.bull_name.toLowerCase().includes(query)
        )
      }

      // Filter by breed
      if (selectedBreed.value) {
        filtered = filtered.filter(item => item.breed_id == selectedBreed.value)
      }

      // Sort
      filtered.sort((a, b) => {
        switch (sortBy.value) {
          case 'price':
            return a.price - b.price
          case 'created_at':
            return new Date(b.created_at) - new Date(a.created_at)
          default:
            return a.name.localeCompare(b.name)
        }
      })

      return filtered
    })

    const cartItemCount = computed(() => {
      return cartItems.value.reduce((total, item) => total + item.quantity, 0)
    })

    const loadSemen = async () => {
      try {
        loading.value = true
        const response = await window.axios.get('/products/semen')
        semen.value = response.data.semen.data
      } catch (error) {
        console.error('Error loading semen:', error)
      } finally {
        loading.value = false
      }
    }

    const loadBreeds = async () => {
      try {
        const response = await window.axios.get('/products/breeds')
        breeds.value = response.data.breeds
      } catch (error) {
        console.error('Error loading breeds:', error)
      }
    }

    const viewDetails = (semenItem) => {
      selectedSemen.value = semenItem
    }

    const addToCart = (semenItem) => {
      // Add to localStorage cart
      const cart = JSON.parse(localStorage.getItem('farmer_cart') || '[]')
      
      // Check if item already exists in cart
      const existingItem = cart.find(item => item.semen.id === semenItem.id)
      
      if (existingItem) {
        existingItem.quantity += 1
      } else {
        cart.push({
          semen: semenItem,
          quantity: 1,
          cow_type: '',
          cow_age: null,
          heat_started_at: ''
        })
      }
      
      localStorage.setItem('farmer_cart', JSON.stringify(cart))
      // Update reactive cart items
      cartItems.value = [...cart]
      selectedSemen.value = null
      
      // Show success message
      showSuccess('Added to cart successfully!')
    }

    const goToCart = () => {
      router.push('/farmer/cart')
    }

    const loadCartItems = () => {
      const cart = JSON.parse(localStorage.getItem('farmer_cart') || '[]')
      cartItems.value = cart
    }

    const handleStorageChange = (e) => {
      if (e.key === 'farmer_cart') {
        loadCartItems()
      }
    }

    const handleCartChange = () => {
      loadCartItems()
    }

    onMounted(() => {
      loadSemen()
      loadBreeds()
      loadCartItems()
      
      // Listen for storage changes (when cart is modified from other tabs/pages)
      window.addEventListener('storage', handleStorageChange)
      // Listen for custom cart change events (same page)
      window.addEventListener('cartChanged', handleCartChange)
    })

    onUnmounted(() => {
      window.removeEventListener('storage', handleStorageChange)
      window.removeEventListener('cartChanged', handleCartChange)
    })

    onActivated(() => {
      // Refresh cart count when component becomes active (e.g., navigating back from cart)
      loadCartItems()
    })

    return {
      semen,
      breeds,
      searchQuery,
      selectedBreed,
      sortBy,
      selectedSemen,
      loading,
      filteredSemen,
      cartItemCount,
      viewDetails,
      addToCart,
      goToCart
    }
  }
}
</script>

