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
          <h1 class="text-2xl font-bold text-gray-900">{{ pageTitle }}</h1>
        </div>
        <div class="flex items-center space-x-4">
          <button 
            @click="refreshData" 
            :disabled="loading"
            class="p-2 hover:bg-gray-100 rounded-lg transition-colors disabled:opacity-50"
          >
            <svg class="w-5 h-5 text-gray-600" :class="{ 'animate-spin': loading }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
            </svg>
          </button>
          <div class="text-sm text-gray-600">
            Last updated: {{ lastUpdated }}
          </div>
        </div>
      </div>
    </div>

    <div class="p-6">
      <div class="max-w-7xl mx-auto">
        <!-- Statistics Dashboard -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
          <!-- Total Cows -->
          <div class="bg-white/70 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 p-6">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm font-medium text-gray-600">Total Cows</p>
                <p class="text-3xl font-bold text-gray-900">{{ jobs.length }}</p>
              </div>
              <div class="p-3 bg-blue-100 rounded-full">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
              </div>
            </div>
          </div>

          <!-- Currently Pregnant -->
          <div class="bg-white/70 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 p-6">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm font-medium text-gray-600">Currently Pregnant</p>
                <p class="text-3xl font-bold text-green-600">{{ pregnantJobs.length }}</p>
              </div>
              <div class="p-3 bg-green-100 rounded-full">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                </svg>
              </div>
            </div>
          </div>

          <!-- Successfully Delivered -->
          <div class="bg-white/70 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 p-6">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm font-medium text-gray-600">Delivered</p>
                <p class="text-3xl font-bold text-blue-600">{{ deliveredJobs.length }}</p>
              </div>
              <div class="p-3 bg-blue-100 rounded-full">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
              </div>
            </div>
          </div>

          <!-- Success Rate -->
          <div class="bg-white/70 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 p-6">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm font-medium text-gray-600">Success Rate</p>
                <p class="text-3xl font-bold text-emerald-600">{{ successRate }}%</p>
              </div>
              <div class="p-3 bg-emerald-100 rounded-full">
                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                </svg>
              </div>
            </div>
          </div>
        </div>

        <!-- Pregnancy Notifications -->
        <div v-if="notifications.length > 0" class="mb-6">
          <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-2xl p-6">
            <div class="flex items-center justify-between mb-4">
              <div class="flex items-center space-x-3">
                <div class="p-2 bg-blue-100 rounded-full">
                  <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4.828 7l2.586 2.586a2 2 0 002.828 0L12 7M4.828 17l2.586-2.586a2 2 0 012.828 0L12 17"></path>
                  </svg>
                </div>
                <h3 class="text-lg font-semibold text-blue-800">Pregnancy Milestones</h3>
              </div>
              <button 
                @click="clearNotifications"
                class="text-sm text-blue-600 hover:text-blue-800 font-medium"
              >
                Mark as Read
              </button>
            </div>
            <div class="space-y-3">
              <div 
                v-for="notification in notifications" 
                :key="notification.job_id + notification.type"
                :class="[
                  'p-4 rounded-xl border',
                  notification.type === 'urgent' 
                    ? 'bg-red-50 border-red-200' 
                    : 'bg-white/70 border-blue-200'
                ]"
              >
                <div class="flex items-start space-x-3">
                  <div :class="[
                    'p-2 rounded-full',
                    notification.type === 'urgent' 
                      ? 'bg-red-100' 
                      : 'bg-green-100'
                  ]">
                    <svg 
                      class="w-4 h-4" 
                      :class="notification.type === 'urgent' ? 'text-red-600' : 'text-green-600'"
                      fill="none" 
                      stroke="currentColor" 
                      viewBox="0 0 24 24"
                    >
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                  </div>
                  <div class="flex-1">
                    <h4 :class="[
                      'font-semibold',
                      notification.type === 'urgent' ? 'text-red-800' : 'text-green-800'
                    ]">
                      {{ notification.title }}
                    </h4>
                    <p :class="[
                      'text-sm mt-1',
                      notification.type === 'urgent' ? 'text-red-700' : 'text-green-700'
                    ]">
                      {{ notification.message }}
                    </p>
                    <p class="text-xs text-gray-500 mt-2">
                      {{ notification.days }} days pregnant
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Upcoming Deliveries Alert -->
        <div v-if="stats.upcoming_deliveries && stats.upcoming_deliveries.length > 0" class="mb-6">
          <div class="bg-gradient-to-r from-yellow-50 to-orange-50 border border-yellow-200 rounded-2xl p-6">
            <div class="flex items-center space-x-3 mb-4">
              <div class="p-2 bg-yellow-100 rounded-full">
                <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
              </div>
              <h3 class="text-lg font-semibold text-yellow-800">Upcoming Deliveries</h3>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
              <div 
                v-for="delivery in stats.upcoming_deliveries" 
                :key="delivery.id"
                class="bg-white/70 rounded-xl p-4 border border-yellow-200"
              >
                <div class="flex items-center justify-between mb-2">
                  <h4 class="font-semibold text-gray-900">{{ delivery.order.order_number }}</h4>
                  <span class="text-sm text-yellow-700 font-medium">
                    {{ getDaysPregnant(delivery.actual_visit_at) }} days
                  </span>
                </div>
                <p class="text-sm text-gray-600 mb-2">{{ delivery.order.cattle_farm.name }}</p>
                <p class="text-sm text-yellow-700">
                  Expected: {{ getExpectedDelivery(delivery.actual_visit_at) }}
                </p>
              </div>
            </div>
          </div>
        </div>

        <!-- Filter Tabs -->
        <div class="bg-white/70 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 p-6 mb-6">
          <div class="flex flex-wrap gap-2">
            <button
              v-for="tab in tabs"
              :key="tab.id"
              @click="activeTab = tab.id"
              :class="[
                'px-4 py-2 rounded-lg font-medium transition-colors flex items-center space-x-2 cursor-pointer',
                activeTab === tab.id
                  ? 'bg-green-600 text-white'
                  : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100'
              ]"
            >
              <span>{{ tab.name }}</span>
              <span class="bg-white/20 text-xs px-2 py-1 rounded-full">
                {{ getTabCount(tab.id) }}
              </span>
            </button>
          </div>
        </div>

        <!-- Loading State -->
        <div v-if="loading" class="flex justify-center items-center py-12">
          <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-green-600"></div>
        </div>

        <!-- Empty State - No Records -->
        <div v-else-if="jobs.length === 0" class="text-center py-12">
          <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-6">
            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
          </div>
          <h3 class="text-xl font-semibold text-gray-900 mb-2">No Pregnancy Records Found</h3>
          <p class="text-gray-600 mb-6 max-w-md mx-auto">
            {{ currentOrder ? 'No pregnancy records found for this order.' : 'You don\'t have any pregnancy records yet. Complete some insemination services to start tracking pregnancies.' }}
          </p>
          <div class="space-x-4">
            <button 
              @click="refreshData"
              class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors"
            >
              Refresh Data
            </button>
            <button 
              v-if="!currentOrder"
              @click="$router.push('/farmer/dashboard')"
              class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors"
            >
              Go to Dashboard
            </button>
          </div>
        </div>

        <!-- Pending Cows Tab -->
        <div v-else-if="activeTab === 'pending'" class="space-y-6">
          <div class="bg-white/70 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 p-6">
            <div class="flex items-center justify-between mb-6">
              <h2 class="text-xl font-bold text-gray-900">Pending Cows</h2>
              <div v-if="currentOrder" class="text-sm text-gray-600 bg-blue-50 px-3 py-1 rounded-lg">
                Order: {{ currentOrder.order_number }}
              </div>
            </div>
            
            <div v-if="pendingJobs.length > 0" class="space-y-4">
              <div
                v-for="record in pendingJobs"
                :key="record.id"
                class="p-6 bg-gradient-to-r from-yellow-50 to-orange-50 rounded-xl border border-yellow-200 hover:shadow-lg transition-shadow"
              >
                <div class="flex items-start justify-between">
                  <div class="flex-1">
                    <div class="flex items-center space-x-4 mb-4">
                      <div>
                        <h3 class="text-lg font-semibold text-gray-900">
                          Cow {{ getCowNumber(record) }} - {{ record.order_item?.bull_semen?.bull_name || 'N/A' }}
                          <span class="text-sm text-gray-500">({{ record.order_item?.bull_semen?.breed?.name || 'N/A' }})</span>
                        </h3>
                        <p class="text-sm text-gray-600">{{ record.job.order.cattle_farm.name }}</p>
                        <p class="text-xs text-gray-500 mt-1">
                          Order: {{ record.job.order.order_number }}
                        </p>
                      </div>
                      <div class="flex items-center space-x-2">
                        <span class="bg-yellow-100 text-yellow-800 text-xs font-semibold px-2 py-1 rounded-full">
                          Pending
                        </span>
                        <span class="text-sm text-gray-500">
                          Since {{ formatDate(record.job.actual_visit_at) }}
                        </span>
                      </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                      <div>
                        <label class="text-sm font-semibold text-gray-500">Technician</label>
                        <p class="text-sm font-medium">{{ record.job.technician.user.name }}</p>
                      </div>
                      <div>
                        <label class="text-sm font-semibold text-gray-500">Days Since Insemination</label>
                        <p class="text-sm font-medium text-yellow-600 font-bold">
                          {{ getDaysPregnant(record.job.actual_visit_at) }} days
                        </p>
                      </div>
                      <div>
                        <label class="text-sm font-semibold text-gray-500">Status</label>
                        <p class="text-sm font-medium">Awaiting Confirmation</p>
                      </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex space-x-3">
                      <button
                        @click="markConceived(record)"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium"
                      >
                        Mark as Conceived
                      </button>
                      <button
                        @click="markMiscarried(record)"
                        class="px-4 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors font-medium"
                      >
                        Mark as Miscarried
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <div v-else class="text-center py-12">
              <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
              <h3 class="mt-2 text-sm font-medium text-gray-900">No pending cows</h3>
              <p class="mt-1 text-sm text-gray-500">No cows are currently awaiting pregnancy confirmation.</p>
            </div>
          </div>
        </div>

        <!-- Conceived Cows Tab -->
        <div v-else-if="activeTab === 'conceived'" class="space-y-6">
          <div class="bg-white/70 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 p-6">
            <div class="flex items-center justify-between mb-6">
              <h2 class="text-xl font-bold text-gray-900">Conceived Cows</h2>
              <div class="text-sm text-gray-600">
                Recently confirmed pregnancies
              </div>
            </div>
            
            <div v-if="conceivedJobs.length > 0" class="space-y-4">
              <div
                v-for="record in conceivedJobs"
                :key="record.id"
                class="p-6 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-200 hover:shadow-lg transition-shadow"
              >
                <div class="flex items-start justify-between">
                  <div class="flex-1">
                    <div class="flex items-center space-x-4 mb-4">
                      <div>
                        <h3 class="text-lg font-semibold text-gray-900">
                          Cow {{ getCowNumber(record) }} - {{ record.order_item?.bull_semen?.bull_name || 'N/A' }}
                          <span class="text-sm text-gray-500">({{ record.order_item?.bull_semen?.breed?.name || 'N/A' }})</span>
                        </h3>
                        <p class="text-sm text-gray-600">{{ record.job.order.cattle_farm.name }}</p>
                        <p class="text-xs text-gray-500 mt-1">
                          Order: {{ record.job.order.order_number }}
                        </p>
                      </div>
                      <div class="flex items-center space-x-2">
                        <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2 py-1 rounded-full">
                          Conceived
                        </span>
                        <span class="text-sm text-gray-500">
                          Since {{ formatDate(record.conception_date || record.job.actual_visit_at) }}
                        </span>
                      </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                      <div>
                        <label class="text-sm font-semibold text-gray-500">Technician</label>
                        <p class="text-sm font-medium">{{ record.job.technician.user.name }}</p>
                      </div>
                      <div>
                        <label class="text-sm font-semibold text-gray-500">Days Since Insemination</label>
                        <p class="text-sm font-medium text-blue-600 font-bold">
                          {{ getDaysPregnant(record.job.actual_visit_at) }} days
                        </p>
                      </div>
                      <div>
                        <label class="text-sm font-semibold text-gray-500">Conception Notes</label>
                        <p class="text-sm font-medium">{{ record.conception_notes || 'No notes' }}</p>
                      </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex space-x-3">
                      <button
                        @click="markPregnant(record)"
                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium"
                      >
                        Mark as Pregnant
                      </button>
                      <button
                        @click="markMiscarried(record)"
                        class="px-4 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors font-medium"
                      >
                        Mark as Miscarried
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <div v-else class="text-center py-12">
              <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
              <h3 class="mt-2 text-sm font-medium text-gray-900">No conceived cows</h3>
              <p class="mt-1 text-sm text-gray-500">No cows are currently marked as conceived.</p>
            </div>
          </div>
        </div>

        <!-- Pregnant Cows Tab -->
        <div v-else-if="activeTab === 'pregnant'" class="space-y-6">
          <div class="bg-white/70 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 p-6">
            <div class="flex items-center justify-between mb-6">
              <h2 class="text-xl font-bold text-gray-900">Pregnant Cows</h2>
              <div class="text-sm text-gray-600">
                Average: {{ stats.avg_days_pregnant || 0 }} days pregnant
              </div>
            </div>
            
            <div v-if="pregnantJobs.length > 0" class="space-y-4">
              <div
                v-for="record in pregnantJobs"
                :key="record.id"
                class="p-6 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl border border-green-200 hover:shadow-lg transition-shadow"
              >
                <div class="flex items-start justify-between">
                  <div class="flex-1">
                    <div class="flex items-center space-x-4 mb-4">
                      <div>
                        <h3 class="text-lg font-semibold text-gray-900">
                          Cow {{ getCowNumber(record) }} - {{ record.order_item?.bull_semen?.bull_name || 'N/A' }}
                          <span class="text-sm text-gray-500">({{ record.order_item?.bull_semen?.breed?.name || 'N/A' }})</span>
                        </h3>
                        <p class="text-sm text-gray-600">{{ record.job.order.cattle_farm.name }}</p>
                        <p class="text-xs text-gray-500 mt-1">
                          Order: {{ record.job.order.order_number }}
                        </p>
                      </div>
                      <div class="flex items-center space-x-2">
                        <span class="bg-green-100 text-green-800 text-xs font-semibold px-2 py-1 rounded-full">
                          Pregnant
                        </span>
                        <span class="text-sm text-gray-500">
                          Since {{ formatDate(record.pregnancy_date || record.job.actual_visit_at) }}
                        </span>
                      </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                      <div>
                        <label class="text-sm font-semibold text-gray-500">Technician</label>
                        <p class="text-sm font-medium">{{ record.job.technician.user.name }}</p>
                      </div>
                      <div>
                        <label class="text-sm font-semibold text-gray-500">Days Pregnant</label>
                        <p class="text-sm font-medium text-green-600 font-bold">
                          {{ getDaysPregnant(record.pregnancy_date || record.job.actual_visit_at) }} days
                        </p>
                      </div>
                      <div>
                        <label class="text-sm font-semibold text-gray-500">Expected Delivery</label>
                        <p class="text-sm font-medium">{{ getExpectedDelivery(record.pregnancy_date || record.job.actual_visit_at) }}</p>
                      </div>
                      <div>
                        <label class="text-sm font-semibold text-gray-500">Progress</label>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                          <div 
                            class="bg-green-600 h-2 rounded-full transition-all duration-300" 
                            :style="{ width: getPregnancyProgress(record.pregnancy_date || record.job.actual_visit_at) + '%' }"
                          ></div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">{{ getPregnancyProgress(record.pregnancy_date || record.job.actual_visit_at) }}% complete</p>
                      </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex space-x-3">
                      <button
                        @click="markConceived(record)"
                        v-if="!record.pregnancy_status || record.pregnancy_status === 'pending'"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium"
                      >
                        Mark as Conceived
                      </button>
                      <button
                        @click="markMiscarried(record)"
                        v-if="record.pregnancy_status === 'conceived' || record.pregnancy_status === 'pregnant'"
                        class="px-4 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors font-medium"
                      >
                        Mark as Miscarried
                      </button>
                      <button
                        @click="markDelivered(record)"
                        v-if="record.pregnancy_status === 'pregnant'"
                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium"
                      >
                        Mark as Delivered
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <div v-else class="text-center py-12">
              <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
              </svg>
              <h3 class="mt-2 text-sm font-medium text-gray-900">No pregnant cows</h3>
              <p class="mt-1 text-sm text-gray-500">No cows are currently marked as pregnant.</p>
            </div>
          </div>
        </div>

        <!-- Delivered Cows Tab -->
        <div v-else-if="activeTab === 'delivered'" class="space-y-6">
          <div class="bg-white/70 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Delivered Cows</h2>
            
            <div v-if="deliveredJobs.length > 0" class="space-y-4">
              <div
                v-for="record in deliveredJobs"
                :key="record.id"
                class="p-6 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-200 hover:shadow-lg transition-shadow"
              >
                <div class="flex items-start justify-between">
                  <div class="flex-1">
                    <div class="flex items-center space-x-4 mb-4">
                      <div>
                        <h3 class="text-lg font-semibold text-gray-900">
                          Cow {{ getCowNumber(record) }} - {{ record.order_item?.bull_semen?.bull_name || 'N/A' }}
                          <span class="text-sm text-gray-500">({{ record.order_item?.bull_semen?.breed?.name || 'N/A' }})</span>
                        </h3>
                        <p class="text-sm text-gray-600">{{ record.job.order.cattle_farm.name }}</p>
                        <p class="text-xs text-gray-500 mt-1">
                          Order: {{ record.job.order.order_number }}
                        </p>
                      </div>
                      <div class="flex items-center space-x-2">
                        <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2 py-1 rounded-full">
                          Delivered
                        </span>
                        <span class="text-sm text-gray-500">
                          {{ formatDate(record.delivery_date || record.updated_at) }}
                        </span>
                      </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                      <div>
                        <label class="text-sm font-semibold text-gray-500">Technician</label>
                        <p class="text-sm font-medium">{{ record.job.technician.user.name }}</p>
                      </div>
                      <div>
                        <label class="text-sm font-semibold text-gray-500">Delivery Notes</label>
                        <p class="text-sm font-medium">{{ record.delivery_notes || 'No notes' }}</p>
                      </div>
                      <div>
                        <label class="text-sm font-semibold text-gray-500">Pregnancy Duration</label>
                        <p class="text-sm font-medium">{{ getPregnancyDuration(record.job.actual_visit_at, record.delivery_date || record.updated_at) }} days</p>
                      </div>
                    </div>

                    <!-- Calf Photos -->
                    <div v-if="record.calf_image" class="mb-4">
                      <label class="text-sm font-semibold text-gray-500">Calf Photos</label>
                      <div class="mt-2 grid grid-cols-2 md:grid-cols-4 gap-4">
                        <img
                          v-for="(image, index) in record.calf_image.split(',')"
                          :key="index"
                          :src="getImageUrl((image || '').trim())"
                          class="w-full h-24 object-cover rounded-lg cursor-pointer hover:opacity-80 transition-opacity"
                          @click="viewImage(getImageUrl((image || '').trim()))"
                          @error="console.error('❌ Image failed to load:', getImageUrl((image || '').trim()))"
                          @load="console.log('✅ Image loaded successfully:', getImageUrl((image || '').trim()))"
                        />
                      </div>
                    </div>
                    <div v-else class="mb-4">
                      <p class="text-sm text-gray-500">No calf photos uploaded</p>
                      
                    </div>

                  </div>
                </div>
              </div>
            </div>
            
            <div v-else class="text-center py-12">
              <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
              <h3 class="mt-2 text-sm font-medium text-gray-900">No delivered cows</h3>
              <p class="mt-1 text-sm text-gray-500">No cows have been marked as delivered yet.</p>
            </div>
          </div>
        </div>

        <!-- Failed Pregnancies Tab -->
        <div v-else-if="activeTab === 'failed'" class="space-y-6">
          <div class="bg-white/70 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Failed Pregnancies</h2>
            
            <div v-if="failedJobs.length > 0" class="space-y-4">
              <div
                v-for="record in failedJobs"
                :key="record.id"
                class="p-6 bg-gradient-to-r from-red-50 to-rose-50 rounded-xl border border-red-200 hover:shadow-lg transition-shadow"
              >
                <div class="flex items-start justify-between">
                  <div class="flex-1">
                    <div class="flex items-center space-x-4 mb-4">
                      <div>
                        <h3 class="text-lg font-semibold text-gray-900">
                          Cow {{ getCowNumber(record) }} - {{ record.order_item?.bull_semen?.bull_name || 'N/A' }}
                          <span class="text-sm text-gray-500">({{ record.order_item?.bull_semen?.breed?.name || 'N/A' }})</span>
                        </h3>
                        <p class="text-sm text-gray-600">{{ record.job?.order?.cattle_farm?.name || 'N/A' }}</p>
                        <p class="text-xs text-gray-500 mt-1">
                          Order: {{ record.job?.order?.order_number || 'N/A' }}
                        </p>
                      </div>
                      <div class="flex items-center space-x-2">
                        <span class="bg-red-100 text-red-800 text-xs font-semibold px-2 py-1 rounded-full">
                          {{ record.pregnancy_status === 'miscarried' ? 'Miscarried' : 'Failed' }}
                        </span>
                        <span class="text-sm text-gray-500">
                          {{ formatDate(record.updated_at) }}
                        </span>
                      </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                      <div>
                        <label class="text-sm font-semibold text-gray-500">Technician</label>
                        <p class="text-sm font-medium">{{ record.job?.technician?.user?.name || 'N/A' }}</p>
                      </div>
                      <div>
                        <label class="text-sm font-semibold text-gray-500">Reason</label>
                        <p class="text-sm font-medium">{{ record.notes || 'No reason provided' }}</p>
                      </div>
                      <div>
                        <label class="text-sm font-semibold text-gray-500">Duration Before Failure</label>
                        <p class="text-sm font-medium">{{ getDaysPregnant(record.insemination_date) }} days</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <div v-else class="text-center py-12">
              <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
              <h3 class="mt-2 text-sm font-medium text-gray-900">No failed pregnancies</h3>
              <p class="mt-1 text-sm text-gray-500">No pregnancies have been marked as failed.</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modals -->
    <!-- Conception Modal -->
    <div v-if="showConceptionModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
      <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full">
        <div class="p-6">
          <h3 class="text-lg font-bold text-gray-900 mb-4">Mark as Conceived</h3>
          <form @submit.prevent="submitConception">
            <div class="mb-4">
              <label class="block text-sm font-semibold text-gray-700 mb-2">Conception Notes</label>
              <textarea
                v-model="conceptionForm.notes"
                rows="3"
                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                placeholder="Any notes about the conception..."
              ></textarea>
            </div>
            <div class="flex justify-end space-x-3">
              <button
                type="button"
                @click="showConceptionModal = false"
                class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors"
              >
                Cancel
              </button>
              <button
                type="submit"
                :disabled="submitting"
                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:bg-gray-300 disabled:cursor-not-allowed transition-colors"
              >
                {{ submitting ? 'Processing...' : 'Mark as Conceived' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Pregnancy Modal -->
    <div v-if="showPregnancyModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
      <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full">
        <div class="p-6">
          <h3 class="text-lg font-bold text-gray-900 mb-4">Mark as Pregnant</h3>
          <form @submit.prevent="submitPregnancy">
            <div class="mb-4">
              <label class="block text-sm font-semibold text-gray-700 mb-2">Pregnancy Notes</label>
              <textarea
                v-model="pregnancyForm.notes"
                rows="3"
                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                placeholder="Any notes about the pregnancy..."
              ></textarea>
            </div>
            <div class="flex justify-end space-x-3">
              <button
                type="button"
                @click="showPregnancyModal = false"
                class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors"
              >
                Cancel
              </button>
              <button
                type="submit"
                :disabled="submitting"
                class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 disabled:bg-gray-300 disabled:cursor-not-allowed transition-colors"
              >
                {{ submitting ? 'Processing...' : 'Mark as Pregnant' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Miscarriage Modal -->
    <div v-if="showMiscarriageModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
      <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full">
        <div class="p-6">
          <h3 class="text-lg font-bold text-gray-900 mb-4">Mark as Miscarried</h3>
          <form @submit.prevent="submitMiscarriage">
            <div class="mb-4">
              <label class="block text-sm font-semibold text-gray-700 mb-2">Notes</label>
              <textarea
                v-model="miscarriageForm.notes"
                rows="3"
                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                placeholder="Any additional notes about the miscarriage..."
              ></textarea>
            </div>
            <div class="flex justify-end space-x-3">
              <button
                type="button"
                @click="showMiscarriageModal = false"
                class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors"
              >
                Cancel
              </button>
              <button
                type="submit"
                :disabled="submitting"
                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 disabled:bg-gray-300 disabled:cursor-not-allowed transition-colors"
              >
                {{ submitting ? 'Processing...' : 'Mark as Miscarried' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Delivery Modal -->
    <div v-if="showDeliveryModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
      <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full">
        <div class="p-6">
          <h3 class="text-lg font-bold text-gray-900 mb-4">Mark as Delivered</h3>
          <form @submit.prevent="submitDelivery" @submit="console.log('📝 Form submitted!')">
            <div class="mb-4">
              <label class="block text-sm font-semibold text-gray-700 mb-2">Delivery Notes</label>
              <textarea
                v-model="deliveryForm.notes"
                rows="3"
                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                placeholder="Any notes about the delivery..."
              ></textarea>
            </div>
            <div class="mb-4">
              <label class="block text-sm font-semibold text-gray-700 mb-2">Calf Photos (Optional)</label>
              <input
                type="file"
                multiple
                accept="image/*"
                @change="handleCalfPhotoUpload"
                @input="console.log('📁 File input changed')"
                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
              />
              
              <!-- Photo Previews -->
              <div v-if="deliveryForm.photos.length > 0" class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-4">
                <div v-for="(photo, index) in deliveryForm.photos" :key="index" class="relative">
                  <img :src="photo.preview" class="w-full h-24 object-cover rounded-lg border border-gray-300" />
                  <button
                    @click="removeCalfPhoto(index)"
                    type="button"
                    class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600"
                  >
                    ×
                  </button>
                </div>
              </div>
            </div>
            <div class="flex justify-end space-x-3">
              <button
                type="button"
                @click="showDeliveryModal = false"
                class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors"
              >
                Cancel
              </button>
              <button
                type="submit"
                :disabled="submitting"
                class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 disabled:bg-gray-300 disabled:cursor-not-allowed transition-colors"
              >
                {{ submitting ? 'Processing...' : 'Mark as Delivered' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Image Viewer Modal -->
    <div v-if="showImageViewer" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center p-4 z-50">
      <div class="max-w-4xl max-h-full">
        <img :src="selectedImage" class="max-w-full max-h-full object-contain rounded-lg" />
        <button
          @click="showImageViewer = false"
          class="absolute top-4 right-4 bg-white bg-opacity-75 hover:bg-opacity-100 rounded-full p-2 transition-colors"
        >
          <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
          </svg>
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, computed, onMounted, onUnmounted } from 'vue'

export default {
  name: 'PregnancyTracking',
  setup() {
    const activeTab = ref('pending')
    const jobs = ref([])
    const stats = ref({})
    const notifications = ref([])
    const loading = ref(false)
    const submitting = ref(false)
    const lastUpdated = ref('')
    const showConceptionModal = ref(false)
    const showPregnancyModal = ref(false)
    const showMiscarriageModal = ref(false)
    const showDeliveryModal = ref(false)
    const showImageViewer = ref(false)
    const selectedJob = ref(null)
    const selectedImage = ref('')
    
    const conceptionForm = ref({
      notes: ''
    })
    
    const pregnancyForm = ref({
      notes: ''
    })
    
    const miscarriageForm = ref({
      notes: ''
    })
    
    const deliveryForm = ref({
      notes: '',
      photos: [] // This will store file objects with file, preview, name properties
    })

    const tabs = [
      { id: 'pending', name: 'Pending' },
      { id: 'conceived', name: 'Conceived' },
      { id: 'pregnant', name: 'Pregnant' },
      { id: 'delivered', name: 'Delivered' },
      { id: 'failed', name: 'Failed' }
    ]

    const conceivedJobs = computed(() => 
      jobs.value.filter(record => record.pregnancy_status === 'conceived')
    )

    const pregnantJobs = computed(() => 
      jobs.value.filter(record => record.pregnancy_status === 'pregnant')
    )

    const pendingJobs = computed(() => {
      const pending = jobs.value.filter(record => !record.pregnancy_status || record.pregnancy_status === 'pending')
      console.log('Pending jobs computed:', pending.length, 'out of', jobs.value.length)
      return pending
    })

    const deliveredJobs = computed(() => {
      const delivered = jobs.value.filter(record => record.pregnancy_status === 'delivered')
      console.log('🐄 Delivered jobs:', delivered.length, 'records')
      delivered.forEach(record => {
        console.log(`🐄 Record ${record.id} calf_image:`, record.calf_image)
      })
      return delivered
    })

    const failedJobs = computed(() => 
      jobs.value.filter(record => record.pregnancy_status === 'miscarried')
    )

    const currentOrder = computed(() => {
      const urlParams = new URLSearchParams(window.location.search)
      const jobId = urlParams.get('job')
      console.log('🔍 URL params debug:', {
        search: window.location.search,
        jobId: jobId,
        jobIdType: typeof jobId,
        allParams: Object.fromEntries(urlParams.entries())
      })
      return jobId ? { order_number: `Job ${jobId}` } : null
    })

    const successRate = computed(() => {
      const total = jobs.value.length
      if (total === 0) return 0
      const delivered = deliveredJobs.value.length
      return Math.round((delivered / total) * 100)
    })


    const pageTitle = computed(() => {
      const urlParams = new URLSearchParams(window.location.search)
      const jobId = urlParams.get('job')
      
      if (jobId) {
        return `Pregnancy Tracking - Job ${jobId}`
      }
      return 'Pregnancy Tracking'
    })

    const getTabCount = (tabId) => {
      switch (tabId) {
        case 'conceived': return conceivedJobs.value.length
        case 'pregnant': return pregnantJobs.value.length
        case 'pending': return pendingJobs.value.length
        case 'delivered': return deliveredJobs.value.length
        case 'failed': return failedJobs.value.length
        default: return 0
      }
    }

    const getCowNumber = (record) => {
      // Get the order items for this record's job
      const orderItems = record.job?.order?.order_items || []
      
      console.log('🐄 getCowNumber debug:', {
        recordId: record.id,
        orderItemId: record.order_item_id,
        orderItems: orderItems,
        orderItemsLength: orderItems.length
      })
      
      // Find the index of this record's order item
      const itemIndex = orderItems.findIndex(item => item.id === record.order_item_id)
      
      console.log('🐄 Item index found:', itemIndex)
      
      // Return the cow number (1-based index)
      const cowNumber = itemIndex >= 0 ? itemIndex + 1 : record.id
      console.log('🐄 Final cow number:', cowNumber)
      
      return cowNumber
    }

    const loadJobs = async () => {
      try {
        loading.value = true
        console.log('🔄 Starting loadJobs...')
        
        // Get job ID from URL query parameters
        const urlParams = new URLSearchParams(window.location.search)
        const jobId = urlParams.get('job')
        console.log('🔍 Job ID from URL:', jobId)
        
        let apiUrl = '/farmer/cow-insemination-records'
        if (jobId) {
          apiUrl += `?job_id=${jobId}`
          console.log('🔍 Loading data for specific job:', jobId)
        } else {
          console.log('🔍 Loading all pregnancy records for farmer')
        }
        console.log('🌐 API URL:', apiUrl)
        
        console.log('📡 Making API call...')
        const response = await window.axios.get(apiUrl)
        console.log('✅ API Response received:', response.data)
        console.log('📊 Records count:', response.data.records?.length || 0)
        
        jobs.value = response.data.records || []
        console.log('💾 Jobs value set:', jobs.value.length)
        console.log('📋 First job:', jobs.value[0])
        
        // Debug the data structure
        if (jobs.value.length > 0) {
          const firstRecord = jobs.value[0]
          console.log('🔍 First record structure:', {
            recordId: firstRecord.id,
            orderItemId: firstRecord.order_item_id,
            job: firstRecord.job,
            order: firstRecord.job?.order,
            orderItems: firstRecord.job?.order?.order_items
          })
        }
        
        if (jobs.value.length === 0) {
          if (jobId) {
            console.log('⚠️ No records found for job:', jobId)
          } else {
            console.log('⚠️ No pregnancy records found for farmer')
          }
        }
        
        lastUpdated.value = new Date().toLocaleTimeString()
        console.log('✅ loadJobs completed successfully')
      } catch (error) {
        console.error('❌ Error loading cow records:', error)
        console.error('❌ Error details:', error.response?.data || error.message)
        showError('Failed to load pregnancy data. Please try again.')
      } finally {
        loading.value = false
        console.log('🏁 loadJobs finished, loading set to false')
      }
    }

    const loadStats = async () => {
      try {
        const response = await window.axios.get('/farmer/pregnancy-stats')
        stats.value = response.data.stats
      } catch (error) {
        console.error('Error loading stats:', error)
      }
    }

    const loadNotifications = async () => {
      try {
        const response = await window.axios.get('/farmer/pregnancy-notifications')
        notifications.value = response.data.notifications || []
      } catch (error) {
        console.error('Error loading notifications:', error)
      }
    }

    const clearNotifications = () => {
      notifications.value = []
    }

    const refreshData = async () => {
      console.log('🔄 refreshData called, loading all data...')
      await Promise.all([loadJobs(), loadStats(), loadNotifications()])
      console.log('✅ refreshData completed')
    }

    const getDaysPregnant = (visitDate) => {
      const visit = new Date(visitDate)
      const now = new Date()
      return Math.floor((now - visit) / (1000 * 60 * 60 * 24))
    }

    const getExpectedDelivery = (visitDate) => {
      const visit = new Date(visitDate)
      const expected = new Date(visit.getTime() + (280 * 24 * 60 * 60 * 1000)) // 280 days
      return expected.toLocaleDateString()
    }

    const getPregnancyProgress = (visitDate) => {
      const days = getDaysPregnant(visitDate)
      const progress = Math.min((days / 280) * 100, 100)
      return Math.round(progress)
    }

    const getPregnancyDuration = (startDate, endDate) => {
      const start = new Date(startDate)
      const end = new Date(endDate)
      return Math.floor((end - start) / (1000 * 60 * 60 * 24))
    }

    // Helper function to get full image URL
    const getImageUrl = (imagePath) => {
      if (!imagePath) return ''
      if (imagePath.startsWith('http')) return imagePath
      
      // Preserve base path (e.g., /demo) when deployed under a subdirectory
      const currentPath = window.location.pathname
      const pathParts = currentPath.split('/').filter(part => part !== '')
      
      let basePath = ''
      if (pathParts.length > 0) {
        if (pathParts[0] === 'demo' || pathParts.includes('demo')) {
          basePath = '/demo'
        }
      }
      
      return `${window.location.origin}${basePath}/storage/${imagePath}`
    }

    const markConceived = (job) => {
      selectedJob.value = job
      showConceptionModal.value = true
    }

    const markPregnant = (job) => {
      selectedJob.value = job
      showPregnancyModal.value = true
    }

    const markMiscarried = (job) => {
      selectedJob.value = job
      showMiscarriageModal.value = true
    }

    const markDelivered = (record) => {
      console.log('🐄 Mark delivered called with record:', record)
      selectedJob.value = record
      showDeliveryModal.value = true
    }

    const addCalfPhotos = (job) => {
      selectedJob.value = job
      showDeliveryModal.value = true
    }

    const submitConception = async () => {
      try {
        submitting.value = true
        await window.axios.put(`/farmer/cow-insemination-records/${selectedJob.value.id}/conception`, {
          notes: conceptionForm.value.notes
        })
        
        showConceptionModal.value = false
        conceptionForm.value.notes = ''
        await refreshData()
        showSuccess('Marked as conceived successfully!')
      } catch (error) {
        console.error('Error marking conception:', error)
        showError('Failed to mark as conceived. Please try again.')
      } finally {
        submitting.value = false
      }
    }

    const submitPregnancy = async () => {
      try {
        submitting.value = true
        await window.axios.put(`/farmer/cow-insemination-records/${selectedJob.value.id}/pregnancy`, {
          notes: pregnancyForm.value.notes
        })
        
        showPregnancyModal.value = false
        pregnancyForm.value.notes = ''
        await refreshData()
        showSuccess('Marked as pregnant successfully!')
      } catch (error) {
        console.error('Error marking pregnancy:', error)
        showError('Failed to mark as pregnant. Please try again.')
      } finally {
        submitting.value = false
      }
    }

    const submitMiscarriage = async () => {
      try {
        submitting.value = true
        await window.axios.put(`/farmer/cow-insemination-records/${selectedJob.value.id}/miscarriage`, {
          notes: miscarriageForm.value.notes
        })
        
        showMiscarriageModal.value = false
        miscarriageForm.value.notes = ''
        await refreshData()
        showSuccess('Marked as miscarried successfully!')
      } catch (error) {
        console.error('Error marking miscarriage:', error)
        showError('Failed to mark as miscarried. Please try again.')
      } finally {
        submitting.value = false
      }
    }

    const submitDelivery = async () => {
      try {
        submitting.value = true
        console.log('🚀 Starting delivery submission')
        console.log('📋 Selected job:', selectedJob.value)
        console.log('📋 Selected job ID:', selectedJob.value?.id)
        
        const formData = new FormData()
        formData.append('notes', deliveryForm.value.notes)
        
        console.log('📤 Submitting delivery with', deliveryForm.value.photos.length, 'photos')
        console.log('📸 Photos array:', deliveryForm.value.photos)
        deliveryForm.value.photos.forEach((photo, index) => {
          if (photo && photo.file) {
            console.log(`📸 Adding photo ${index}:`, photo.name, photo.file)
            formData.append(`photos[${index}]`, photo.file)
          }
        })
        
        // Debug FormData contents
        console.log('📋 FormData contents:')
        for (let [key, value] of formData.entries()) {
          console.log(`${key}:`, value)
        }

        // Use POST with method override for better FormData support
        formData.append('_method', 'PUT')
        const response = await window.axios.post(`/farmer/cow-insemination-records/${selectedJob.value.id}/delivery`, formData)
        
        console.log('✅ Delivery response:', response.data)
        console.log('📸 Calf image in response:', response.data.record?.calf_image)
        
        showDeliveryModal.value = false
        deliveryForm.value.notes = ''
        deliveryForm.value.photos = []
        
        // Reset the file input
        const fileInput = document.querySelector('input[type="file"][accept="image/*"]')
        if (fileInput) {
          fileInput.value = ''
        }
        
        await refreshData()
        showSuccess('Marked as delivered successfully!')
      } catch (error) {
        console.error('Error marking delivery:', error)
        showError('Failed to mark as delivered. Please try again.')
      } finally {
        submitting.value = false
      }
    }

    const handleCalfPhotoUpload = (event) => {
      const files = Array.from(event.target.files)
      console.log('📸 Photos selected:', files.length, 'files')
      console.log('📸 Files details:', files)
      
      // Clear existing photos
      deliveryForm.value.photos = []
      
      // Process each file and store with file object, preview, and name
      files.forEach((file, index) => {
        // Validate file type
        if (!file.type.startsWith('image/')) {
          showError('Please select valid image files.')
          return
        }
        
        // Validate file size (10MB limit)
        if (file.size > 10 * 1024 * 1024) {
          showError('File size must be less than 10MB.')
          return
        }
        
        // Create a preview URL and store the file
        const reader = new FileReader()
        reader.onload = (e) => {
          deliveryForm.value.photos.push({
            file: file,
            preview: e.target.result,
            name: file.name
          })
          console.log(`📸 Photo ${index + 1} processed:`, deliveryForm.value.photos[deliveryForm.value.photos.length - 1])
        }
        reader.readAsDataURL(file)
      })
      
      console.log('📸 Updated deliveryForm.photos:', deliveryForm.value.photos)
    }

    const removeCalfPhoto = (index) => {
      deliveryForm.value.photos.splice(index, 1)
      console.log('📸 Removed photo at index', index, 'Remaining photos:', deliveryForm.value.photos.length)
    }

    const viewImage = (imageUrl) => {
      selectedImage.value = imageUrl
      showImageViewer.value = true
    }

    const formatDate = (date) => {
      return new Date(date).toLocaleDateString()
    }

    // Auto-refresh every 5 minutes
    let refreshInterval = null

    onMounted(() => {
      console.log('🚀 Component mounted, calling refreshData...')
      refreshData()
      refreshInterval = setInterval(refreshData, 5 * 60 * 1000) // 5 minutes
    })

    onUnmounted(() => {
      if (refreshInterval) {
        clearInterval(refreshInterval)
      }
    })

    return {
      activeTab,
      jobs,
      stats,
      notifications,
      loading,
      submitting,
      lastUpdated,
      showConceptionModal,
      showPregnancyModal,
      showMiscarriageModal,
      showDeliveryModal,
      showImageViewer,
      selectedJob,
      selectedImage,
      conceptionForm,
      pregnancyForm,
      miscarriageForm,
      deliveryForm,
      tabs,
      conceivedJobs,
      pregnantJobs,
      pendingJobs,
      deliveredJobs,
      failedJobs,
      successRate,
      currentOrder,
      pageTitle,
      getTabCount,
      getCowNumber,
      loadJobs,
      loadStats,
      loadNotifications,
      clearNotifications,
      refreshData,
      getDaysPregnant,
      getExpectedDelivery,
      getPregnancyProgress,
      getPregnancyDuration,
      markConceived,
      markPregnant,
      markMiscarried,
      markDelivered,
      addCalfPhotos,
      submitConception,
      submitPregnancy,
      submitMiscarriage,
      submitDelivery,
      handleCalfPhotoUpload,
      removeCalfPhoto,
      viewImage,
      getImageUrl,
      formatDate
    }
  }
}
</script>