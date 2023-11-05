<template>
  <div
    class="relative border border-red-600 rounded-xl shadow-lg w-72 h-auto mt-4 p-4 flex flex-col justify-center items-center">
    <img
      class="rounded-xl object-cover h-[256px] w-full"
      :src="menu.imgUrl || ''"
      alt=""/>

      <!-- edit menu button for admin -->
      <a v-if="edit_menu != null" :href="edit_menu"
      class="absolute top-2 right-2 ease-out duration-200 hover:-translate-y-1 hover:translate-x-1 shadow-lg hover:bg-red-500 bg-red-600 text-white px-2 py-1 rounded-xl">
        <i class="bi bi-pencil-square"></i>
        แก้ไข
      </a>
      <!-- end -->
      
    <div class="flex flex-col mt-4 justify-center items-center w-full">
      <p class="mt-2 text-2xl">
        {{ menu.name }}
      </p>
      <p class="mt-2 text-xl font-light">
        ฿
        {{ menu.price }}
      </p>
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
    </div>
  </div>
</template>

<script setup lang="js">

defineProps(['menu', 'edit_menu', 'title'])
// const updateCounterValue = defineEmits(['updateCounterValue']);
//const passValue = document.getElementsByName('pass')[0].childNodes[0];
const counter = reactive({number: 1})
// increase จำนวนเมื่อกดปุ่ม +
function up() {
  if (counter.number < 10) {
    counter.number++
  }
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
// watch(
//     () => counter.number,
//     (value) => {
//       updateCounterValue(value.toString()); // Emit the event with the updated value
//     }
// );
</script>
