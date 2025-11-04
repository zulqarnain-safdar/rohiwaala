<template>
  <Transition name="notification" appear>
    <div v-if="show" class="fixed top-4 right-4 z-50 max-w-sm w-full">
      <div :class="[
        'rounded-lg shadow-lg p-4 flex items-center',
        type === 'success' ? 'bg-green-500 text-white' : 
        type === 'error' ? 'bg-red-500 text-white' : 
        type === 'warning' ? 'bg-yellow-500 text-white' :
        'bg-blue-500 text-white'
      ]">
        <div class="flex-1">
          <p class="text-sm font-medium">{{ message }}</p>
        </div>
        <button @click="close" class="ml-4 text-white hover:text-gray-200 transition-colors">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
          </svg>
        </button>
      </div>
    </div>
  </Transition>
</template>

<script>
import { ref, onMounted } from 'vue'

export default {
  name: 'StoreNotification',
  props: {
    message: {
      type: String,
      required: true
    },
    type: {
      type: String,
      default: 'success',
      validator: (value) => ['success', 'error', 'warning', 'info'].includes(value)
    },
    duration: {
      type: Number,
      default: 5000
    }
  },
  setup(props) {
    const show = ref(true)

    const close = () => {
      show.value = false
    }

    onMounted(() => {
      if (props.duration > 0) {
        setTimeout(() => {
          close()
        }, props.duration)
      }
    })

    return {
      show,
      close
    }
  }
}
</script>

<style scoped>
.notification-enter-active {
  transition: all 0.3s ease-out;
}

.notification-leave-active {
  transition: all 0.3s ease-in;
}

.notification-enter-from {
  opacity: 0;
  transform: translateX(100%);
}

.notification-leave-to {
  opacity: 0;
  transform: translateX(100%);
}
</style>
