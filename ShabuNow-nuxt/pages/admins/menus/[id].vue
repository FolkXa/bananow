<template>
  <MainContainer>
    <HeaderContainer>
      <HeaderText>แก้ไขเมนู</HeaderText>
    </HeaderContainer>
    <hr />
    <form @submit.prevent="onSubmit()" >
      <ContentContainer>
        <div class="w-2/3 p-4 text-xl font-light border rounded-xl mt-8 ">

          <div class="overflow-hidden border h-[150px] flex flex-col lg:flex-row justify-around items-center rounded-xl bg-cover-category object-cover bg-center bg-no-repeat">
            <h1 class="text-xl lg:text-3xl font-semibold text-white z-10">
              แก้ไขเมนู
            </h1>
            <div class="z-10 text-white text-6xl lg:text-8xl">
              <i class="fa-solid fa-bowl-food"></i>
            </div>
            <div class="bg-cover-overlay"></div>
          </div>

          <InputField
              class="mt-4"
              placeholder="ชื่อเมนู"
              type="text"
              name="name"
              v-model="menu.name"
              @input="checkName"
          />
          <span v-if="errors.name" class="text-red-500">{{
              errors.name
            }}</span>
          <InputField
              class="mt-4"
              placeholder="ราคา (THB)"
              type="number"
              name="price"
              v-model="menu.price"
              @input="checkPrice"
          />
          <select v-model="menu.status"
              class="mt-4 text-base focus:ring-red-600 focus:border-red-600 focus:ring-1 focus:outline-none p-2.5 block bg-white border border-slate-300 rounded-md w-full py-3 pl-9 pr-3"
          >
            <option v-for="option in options" :value="option">
              {{ option }}
            </option>
          </select>

          <textarea
              row="2"
              class="mt-4 text-base block p-2.5 w-full bg-white border border-slate-300 rounded-lg focus:ring-red-600 focus:border-red-600 focus:ring-1 focus:outline-none"
              placeholder="คำอธิบายเพิ่มเติม"
              v-model="menu.description"
              @input="checkDescription(menu.description)"
          ></textarea>
          <span v-if="errors.description" class="text-red-500">{{
              errors.description
            }}</span>
          <div class="mx-auto my-8">
            <label
                class="mt-4 mb-1 block text-lg font-medium text-gray-700"
            >อัพโหลดรูปภาพ</label
            >
            <input
                id="example1"
                type="file"
                class="mt-2 block w-full text-sm file:mr-4 file:rounded-md file:border-0 file:bg-slate-700 file:py-2 file:px-4 file:text-sm file:font-semibold file:text-white hover:file:bg-slate-900 focus:outline-none disabled:pointer-events-none disabled:opacity-60"
            />
          </div>
          <hr class="mt-4">
          <Button class="mt-4 w-full">
            <slot name="button">ยืนยัน</slot>
          </Button>
        </div>
      </ContentContainer>
    </form>
  </MainContainer>
</template>

<script setup lang="js">
import {useRoute} from "vue-router";
import {useAuthStore} from "~/stores/auth";
import {formToJSON} from "axios";

// function onFileChanged(event) {
//   console.log(event)
// }

const auth = useAuthStore();
const route = useRoute();

const response = await $fetch(`http://localhost/api/menu/${route.params.id}`)
const menu = reactive(response);
const options = ['available', 'outofstock']

function checkName() {
  if (menu.name.length > 20) {
    menu.name = menu.name.substring(0, 20)
  }
}
function checkPrice() {
  if (menu.price > 1000) {
    menu.price = 1000
  }
  if (menu.price <= 10) {
    menu.price = 10
  }
}
function checkDescription() {
  if (menu.description.length > 30) {
    menu.description = menu.description.substring(0, 30)
  }
}
const errors = reactive({
  name: null,
  description: null
})
async function onSubmit() {
  if (menu.name.length === 0) {
    errors.name = 'ห้ามว่าง';
  } else {
    errors.name = null;
  }
  if (menu.description.length === 0) {
    errors.description = "ห้ามว่าง";
  } else {
    errors.description = null;
  }
  const name = menu.name;
  const price = menu.price;
  const imgPath = menu.imgPath;
  const description = menu.description;
  const status = menu.status;
  if (errors.name === null && errors.description === null) {
    const response = await $fetch(`http://localhost/api/menu/update/${route.params.id}`, {
      method : 'POST',
      body : { name, price, imgPath, description, status }
    })
    navigateTo('/admins/editMenu')
  }
}
</script>
<style>
.bg-cover-category {
  position: relative;
  background-image: url('../../../assets/img/cover1.jpg');
  background-position: center;
}
.bg-cover-overlay {
  background-color: rgba(0, 0, 0, .35);
  width: 100%;
  height: 100%;
  position: absolute;
}
</style>