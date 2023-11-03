<template>
    <div class="custom-number-input h-10 w-32 flex flex-col items-center justify-center">
        <label for="custom-input-number" class="w-full text-gray-700 text-base text-center font-semibold">
          <slot name="counter_title">
            {{ title }}
          </slot>
        </label>
        <div class="flex flex-row h-10 w-full border rounded-lg relative bg-transparent mt-1">
            <button @click="down()" data-action="decrement" class="text-gray-600 hover:text-gray-700 hover:bg-gray-100 h-full w-20 rounded-l cursor-pointer outline-none">
                <span class="m-auto text-2xl font-thin">−</span>
            </button>
            <input 
            type="number" 
            class="outline-none focus:outline-none text-center w-full font-semibold text-md hover:text-black focus:text-black  md:text-basecursor-default flex items-center text-gray-700  outline-none" 
            name="custom-input-number"
            v-model="counter.number"
            @input="checkMinValue">
            <button @click="up()" data-action="increment" class="text-gray-600 hover:text-gray-700 hover:bg-gray-100 h-full w-20 rounded-r cursor-pointer">
                <span class="m-auto text-2xl font-thin">+</span>
            </button>
        </div>
    </div>
</template>

<script>
export default {
  props: ['title', 'initialNumber']
}
</script>

<script setup>
const counter = reactive({number: 1})

// increase จำนวนเมื่อกดปุ่ม +
function up() {
  counter.number++
}

// decrease จำนวนเมื่อกดปุ่ม -
function down() {
  if (counter.number > 1) {
    counter.number--
  }
}

// ห้ามไม่ให้ user ใส่ตัวเลขติดลบ (-)
function checkMinValue() {
  if (counter.number < 1) {
    counter.number = 1
  }
}
</script>

<style scoped lang="scss">
  input[type='number']::-webkit-inner-spin-button,
  input[type='number']::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
  }

  .custom-number-input input:focus {
    outline: none !important;
  }

  .custom-number-input button:focus {
    outline: none !important;
  }
</style>