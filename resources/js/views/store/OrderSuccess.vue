<template>
  <div class="min-h-screen bg-gradient-to-br from-green-50 via-emerald-50 to-teal-100 flex items-center justify-center">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="bg-white/90 backdrop-blur-sm rounded-3xl shadow-2xl border border-white/20 p-8 text-center">
        <!-- Success Icon -->
        <div class="mx-auto w-20 h-20 bg-gradient-to-r from-green-500 to-emerald-600 rounded-full flex items-center justify-center mb-6 animate-pulse">
          <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
          </svg>
        </div>

        <!-- Success Message -->
        <h1 class="text-3xl font-bold text-gray-900 mb-4">Order Placed Successfully!</h1>
        <p class="text-lg text-gray-600 mb-8">
          Thank you for choosing RohiWala.pk. Your order has been received and a technician will be assigned shortly.
        </p>

        <!-- Order Details -->
        <div v-if="order" class="bg-gray-50 rounded-2xl p-6 mb-8 text-left">
          <h2 class="text-xl font-bold text-gray-900 mb-4">Order Details</h2>
          
          <div class="space-y-3">
            <div class="flex justify-between">
              <span class="text-gray-600">Order Number:</span>
              <span class="font-semibold text-gray-900">{{ order.order_number }}</span>
            </div>
            
            <div class="space-y-2">
              <div class="flex justify-between">
                <span class="text-gray-600">Subtotal:</span>
                <span class="font-semibold">₨{{ orderSubtotal.toFixed(2) }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-600">Visit Fee:</span>
                <span class="font-semibold">₨{{ orderVisitFee.toFixed(2) }}</span>
              </div>
              <div class="flex justify-between border-t pt-2">
                <span class="text-gray-600 font-semibold">Total Amount:</span>
                <span class="font-bold text-green-600">₨{{ orderTotal.toFixed(2) }}</span>
              </div>
            </div>
            
            <div class="flex justify-between">
              <span class="text-gray-600">Status:</span>
              <span :class="getStatusBadgeClass(order.status)" class="px-3 py-1 rounded-full text-sm font-semibold">
                {{ order.status }}
              </span>
            </div>
            
            <div class="flex justify-between">
              <span class="text-gray-600">Order Date:</span>
              <span class="font-semibold text-gray-900">{{ formatDate(order.created_at) }}</span>
            </div>
          </div>

          <!-- Items -->
          <div class="mt-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-3">Order Items</h3>
            <div class="space-y-2">
              <div v-for="item in order.order_items" :key="item.id" class="flex justify-between items-center py-2 border-b border-gray-200 last:border-b-0">
                <div>
                  <p class="font-medium text-gray-900">{{ item.bull_semen.name }}</p>
                  <p class="text-sm text-gray-600">{{ item.bull_semen.breed.name }}</p>
                </div>
                <div class="text-right">
                  <p class="font-semibold text-gray-900">₨{{ item.total_price }}</p>
                  <p class="text-sm text-gray-600">Qty: {{ item.quantity }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Next Steps -->
        <div class="bg-blue-50 rounded-2xl p-6 mb-8">
          <h2 class="text-xl font-bold text-gray-900 mb-4">What's Next?</h2>
          
          <div class="space-y-4">
            <div class="flex items-start space-x-3">
              <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                <span class="text-sm font-semibold text-blue-600">1</span>
              </div>
              <div>
                <h3 class="font-semibold text-gray-900">Technician Assignment</h3>
                <p class="text-sm text-gray-600">A qualified technician will be assigned to your order within 24 hours.</p>
              </div>
            </div>
            
            <div class="flex items-start space-x-3">
              <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                <span class="text-sm font-semibold text-blue-600">2</span>
              </div>
              <div>
                <h3 class="font-semibold text-gray-900">Contact & Scheduling</h3>
                <p class="text-sm text-gray-600">The technician will contact you to schedule the insemination service.</p>
              </div>
            </div>
            
            <div class="flex items-start space-x-3">
              <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                <span class="text-sm font-semibold text-blue-600">3</span>
              </div>
              <div>
                <h3 class="font-semibold text-gray-900">Service Delivery</h3>
                <p class="text-sm text-gray-600">Professional artificial insemination service will be provided at your farm.</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
          <button @click="goToDashboard" 
                  class="bg-gradient-to-r from-green-600 to-emerald-600 text-white px-8 py-3 rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
            Go to Dashboard
          </button>
          
          <button @click="goToProducts" 
                  class="border-2 border-green-600 text-green-600 px-8 py-3 rounded-xl hover:bg-green-600 hover:text-white transition-all duration-200 font-semibold">
            Browse More Products
          </button>
        </div>

        <!-- Contact Info -->
        <div class="mt-8 pt-6 border-t border-gray-200">
          <p class="text-sm text-gray-600 mb-2">Need help or have questions?</p>
          <div class="flex justify-center space-x-6 text-sm">
            <a href="tel:+923001234567" class="text-green-600 hover:text-green-800 font-medium">
              📞 +92 300 1234567
            </a>
            <a href="mailto:info@rohiwala.pk" class="text-green-600 hover:text-green-800 font-medium">
              ✉️ info@rohiwala.pk
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, computed, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'

export default {
  name: 'OrderSuccess',
  setup() {
    const router = useRouter()
    const route = useRoute()
    const order = ref(null)
    const visitFee = 50.00

    // Computed properties for order breakdown
    const orderSubtotal = computed(() => {
      if (!order.value || !order.value.order_items) return 0
      return order.value.order_items.reduce((total, item) => {
        return total + (parseFloat(item.unit_price) || 0) * item.quantity
      }, 0)
    })

    const orderVisitFee = computed(() => {
      return visitFee
    })

    const orderTotal = computed(() => {
      return orderSubtotal.value + orderVisitFee.value
    })

    const loadOrder = async () => {
      try {
        const orderId = route.params.id
        const response = await window.axios.get(`/orders/${orderId}`)
        order.value = response.data.order
      } catch (error) {
        console.error('Error loading order:', error)
      }
    }

    const getStatusBadgeClass = (status) => {
      const classes = {
        pending: 'bg-yellow-100 text-yellow-800',
        assigned: 'bg-blue-100 text-blue-800',
        in_progress: 'bg-purple-100 text-purple-800',
        completed: 'bg-green-100 text-green-800',
        cancelled: 'bg-red-100 text-red-800'
      }
      return classes[status] || 'bg-gray-100 text-gray-800'
    }

    const formatDate = (date) => {
      return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
      })
    }

    const goToDashboard = () => {
      router.push('/farmer/dashboard')
    }

    const goToProducts = () => {
      router.push('/')
    }

    onMounted(() => {
      loadOrder()
    })

    return {
      order,
      visitFee,
      orderSubtotal,
      orderVisitFee,
      orderTotal,
      getStatusBadgeClass,
      formatDate,
      goToDashboard,
      goToProducts
    }
  }
}
</script>
