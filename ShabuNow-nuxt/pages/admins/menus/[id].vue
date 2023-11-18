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
          <p class="mt-2">Name*</p>
          <InputField
              class="mt-0"
              placeholder="ชื่อเมนู"
              type="text"
              name="name"
              v-model="menu.name"
              @input="checkName"
          />
          <span v-if="errors.name" class="text-red-500">{{
              errors.name[0]
            }}</span>
          <p class="mt-2">Price* (baht)</p>
          <InputField
              class="mt-0"
              placeholder="ราคา (THB)"
              type="number"
              name="price"
              v-model="menu.price"
              @input="checkPrice"
          />
          <p class="mt-2">Status*</p>
          <select v-model="menu.status"
                  class="mt-0 text-base focus:ring-red-600 focus:border-red-600 focus:ring-1 focus:outline-none p-2.5 block bg-white border border-slate-300 rounded-md w-full py-3 pl-9 pr-3"
          >
            <option v-for="option in options" :value="option">
              {{ option }}
            </option>
          </select>
          <p class="mt-2">Description*</p>
          <textarea
              row="2"
              class="mt-0 text-base block p-2.5 w-full bg-white border border-slate-300 rounded-lg focus:ring-red-600 focus:border-red-600 focus:ring-1 focus:outline-none"
              placeholder="คำอธิบายเพิ่มเติม"
              v-model="menu.description"
              @input="checkDescription(menu.description)"
          ></textarea>
          <span v-if="errors.description" class="text-red-500">{{
              errors.description[0]
            }}</span>
          <div class="mx-auto my-8">
            <label
                class="mt-4 mb-1 block text-lg font-medium text-gray-700"
            >อัพโหลดรูปภาพ</label
            >
            <input
                id="example1"
                accept="image/*"
                type="file"
                class="mt-2 block w-full text-sm file:mr-4 file:rounded-md file:border-0 file:bg-slate-700 file:py-2 file:px-4 file:text-sm file:font-semibold file:text-white hover:file:bg-slate-900 focus:outline-none disabled:pointer-events-none disabled:opacity-60"
                @change="onChange"
            />
            <div id="preview" class="w-1/2 mt-2">
              <img class="rounded-xl object-cover h-[256px] w-[256px]" v-if="item.imageUrl" :src="item.imageUrl" alt="" />
            </div>
          </div>
          <span v-if="message" class="text-red-500">{{
              message
            }}</span>
          <Button class="mt-4 w-full" type="submit">
            <slot name="button">ยืนยัน</slot>
          </Button>
        </div>
      </ContentContainer>
    </form>
  </MainContainer>
</template>
<script setup lang="js">

import axios from "axios";

const route = useRoute();
const auth = useAuthStore();
if (auth.getUser.role !== 'admin') {
  navigateTo('/');
}
const response = await $fetch(`http://localhost/api/menu/${route.params.id}`)
const menu = reactive(response);
const item = reactive( {
  imageUrl : menu.imgPath? 'http://localhost' + menu.imgPath : ''
})
menu.imgPath = '';
console.log(item.imageUrl)
const options = ['available', 'outofstock']

function onChange(e) {
  const file = e.target.files[0]
  menu.imgPath = file;
  item.imageUrl = URL.createObjectURL(file)
}
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
const errors = ref([]);
const message = ref();
async function onSubmit() {
  try {
    // const response = await $fetch(`http://localhost/api/menu/store`, {
    //   method : 'POST',
    //   body : { ...menu }
    // });
    // console.log(response)
    //
    await axios.post(`http://localhost/api/menu/update/${route.params.id}`, menu, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    }).then(response => {
      console.log(response)
      navigateTo('/admins/editMenu')
    }).catch(error => {
      throw error
    })
  } catch (error) {
    console.log('error : ' + error)
    if (error.data.errors) {
      errors.value = error.data.errors
    } else {
      message.value = error.data.message
    }
  }

  // if (menu.name.length === 0) {
  //   errors.name = 'ห้ามว่าง';
  // } else {
  //   errors.name = null;
  // }
  // if (menu.description.length === 0) {
  //   errors.description = "ห้ามว่าง";
  // } else {
  //   errors.description = null;
  // }
  // const name = menu.name;
  // const price = menu.price;
  // const imgPath = menu.imgPath;
  // const description = menu.description;
  // const status = menu.status;
  // if (errors.name === null && errors.description === null) {
  //   const response = await $fetch(`http://localhost/api/menu/store`, {
  //     method : 'POST',
  //     body : { ...menu }
  //   });
  //   console.log(response);
  // }
}
</script>
<style>
.bg-cover-category {
  position: relative;
  background-image: url('~/assets/img/cover1.jpg');
  background-position: center;
}
.bg-cover-overlay {
  background-color: rgba(0, 0, 0, .35);
  width: 100%;
  height: 100%;
  position: absolute;
}
</style>
