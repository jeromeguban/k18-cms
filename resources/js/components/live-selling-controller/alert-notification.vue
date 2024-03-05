<template>
  <div class="blink" style="z-index: 200;">
    <div v-if="show"
      style="z-index: 200;"
      class="animate-slide-in alert-bidder-box flex justify-center items-center flex-col shadow-md rounded-bl-lg rounded-tl-lg p-12 text-blue-500 text-center" :class="animationClass">
        <div class="flex items-center justify-center text-white">
            <!-- <i class="fa-sharp fa-solid fa-trophy-star fa-5x mr-10"></i> -->
            <span style="font-size: 1.6vw">
              <span class="font-semibold p-2 rounded bg-white alert-text mr-4" style="font-size:2vw">
                {{ sorted_notifications[0].fullname }}
              </span>
              - {{ sorted_notifications[0].notification }}
            </span>
        </div>
    </div>
  </div>
</template>
  
<script>
import Notification from '../../Core/Notification'
  export default {
    props: {
      show: {
        type: Boolean,
        default: false
      },
    },
    data() {
      return {
        notification: new Notification(this.$route.params.id),
        animationClass: '',
      };
    },
    computed: {
        'sorted_notifications'() {
            return this.notification.notifications
            .filter(notification => notification.notification != 'has joined the event') 
            .sort((a, b) => {
                return new Date(b.created_at) - new Date(a.created_at);
            });
        },
    },
    watch: {
      'show'(){
        if(this.show) {
          setTimeout(() => {
            app.$emit('close-alert-notification')
          }, 6000);
        }
      }
    },
    methods: {
      showBox() {
        this.show = true;
      },
    },
  };
</script>
  
<style >
  .alert-bidder-box {
    position: absolute;
    bottom: 100px;
    background-color: #EF4444;
    right: 0;
    transform: translateX(100%);
    opacity: 0;
    z-index: 200;
  }

  .alert-text {
    color: #EF4444;
  }
    
  @keyframes slideIn {
    from {
      transform: translateX(100%);
      opacity: 0;
    }
    to {
      transform: translateX(0%);
      opacity: 1;
    }
  }
  
  .animate-slide-in {
    animation: slideIn 0.75s ease-in-out forwards;
    display: flex;
  }

  .blink {
      animation: blinker .7s linear infinite;
  }

  @keyframes blinker {
    50% {
        opacity: 40%;
    }
  }
</style>
  