<template>
    <div>
      <div class="lightbox fixed inset-0 flex justify-center items-center" style="z-index: 2000" v-if="visible" @click="hide">
          <div class="fixed right-0 top-0 text-white cursor-pointer text-4xl p-1 mr-2" @click.stop="hide">&times;</div>
          <div class="flex">
              <div class="cursor-pointer self-center px-8"
                   @click.stop="prev"
                   :class="{'invisible': ! hasPrev()}">
                  <svg class="pointer-events-none" fill="#fff" height="48" viewBox="0 0 24 24" width="48" xmlns="http://www.w3.org/2000/svg">
                      <path d="M15.41 16.09l-4.58-4.59 4.58-4.59L14 5.5l-6 6 6 6z"/>
                      <path d="M0-.5h24v24H0z" fill="none"/>
                  </svg>
              </div>
              <div class="lightbox-image" @click.stop="">
                  <img :src="images[index]">
              </div>
              <div class="cursor-pointer self-center px-8"
                   @click.stop="next"
                   :class="{'invisible': ! hasNext()}">
                  <svg class="pointer-events-none" fill="#fff" height="48" viewBox="0 0 24 24" width="48" xmlns="http://www.w3.org/2000/svg">
                      <path d="M8.59 16.34l4.58-4.59-4.58-4.59L10 5.75l6 6-6 6z"/>
                      <path d="M0-.25h24v24H0z" fill="none"/>
                  </svg>
              </div>
          </div>
      </div>
  </div>
</template>

<script>
export default {
    props: {
        images: {
            type: Array,
            default: () => [],
        },
    },
    data() {
        return {
            visible: false,
            index: 0,
        };
    },
    watch: {
      'visible'() {
          let body = document.querySelector('html');
          return (this.visible ? body.style.overflowY = 'hidden' : body.style.overflowY = 'scroll');
      }
    },
    created() {
      app.$on('open-lightbox', (data)=>{
              this.index = data.index
              this.visible = true;
      });
    },
    methods: {
        show() {
            this.visible = true;
        },
        hide() {
            this.visible = false;
            this.index = 0;
        },
        hasNext() {
            return this.index + 1 < this.images.length;
        },
        hasPrev() {
            return this.index - 1 >= 0;
        },
        prev() {
            if (this.hasPrev()) {
                this.index -= 1;
            }
        },
        next() {
            if (this.hasNext()) {
                this.index += 1;
            }
        },
        onKeydown(e) {
            if (this.visible) {
                switch (e.key) {
                    case 'ArrowRight':
                        this.next();
                        break;
                    case 'ArrowLeft':
                        this.prev();
                        break;
                    case 'ArrowDown':
                    case 'ArrowUp':
                    case ' ':
                        e.preventDefault();
                        break;
                    case 'Escape':
                        this.hide();
                        break;
                }
            }
        },
    },
    mounted() {
        window.addEventListener('keydown', this.onKeydown)
    },
    destroyed() {
        window.removeEventListener('keydown', this.onKeydown)
    },
};
</script>

<style scoped>
.lightbox {
    background: rgba(0, 0, 0, 0.8);
}

.lightbox-image img {
    width: auto;
    height: auto;
    max-width: 100%;
    max-height: calc(100vh - 90px);
}
</style>