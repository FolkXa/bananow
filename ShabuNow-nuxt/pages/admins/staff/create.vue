
import { Input } from 'postcss';
<template>
  <MainContainer>
    <HeaderContainer>
      <HeaderText>สร้างเจ้าหน้าที่</HeaderText>
    </HeaderContainer>
    <hr />
    <ContentContainer>
      <div class="w-2/3 p-4 text-xl font-light border rounded-xl mt-8">

        <div class="overflow-hidden border h-[150px] flex flex-col lg:flex-row justify-around items-center rounded-xl bg-cover-category object-cover bg-center bg-no-repeat">
          <h1 class="text-xl lg:text-3xl font-semibold text-white z-10">
            เพิ่มพนักงานสตาฟ
          </h1>
          <div class="z-10 text-white text-6xl lg:text-8xl">
            <i class="bi bi-person-plus-fill"></i>
          </div>
          <div class="bg-cover-overlay"></div>
        </div>

        <form @submit.prevent="handleSubmit">
          <p class="mt-4">Username *</p>
          <InputField
              class="mt-0"
              type="text"
              name="username"
              placeholder="Username"
              v-model="form.username"
          />
          <span v-if="errors.username" class="text-red-500">{{
              errors.username[0]
            }}</span>
          <p class="mt-4">First Name*</p>
          <InputField
            class="mt-0"
            type="text"
            name="firstname"
            placeholder="Firstname"
            v-model="form.firstname"
          />
          <span v-if="errors.firstname" class="text-red-500">{{
              errors.firstname[0]
            }}</span>
          <p class="mt-4">Last Name*</p>
          <InputField
            class="mt-0"
            type="text"
            name="lastname"
            placeholder="Lastname"
            v-model="form.lastname"
          />
          <span v-if="errors.lastname" class="text-red-500">{{
              errors.lastname[0]
            }}</span>
          <p class="mt-2">Email*</p>
          <InputField
            class="mt-0"
            type="email"
            name="email"
            placeholder="Email"
            v-model="form.email"
          />
          <span v-if="errors.email" class="text-red-500">{{
              errors.email[0]
            }}</span>
          <p class="mt-2">Password*</p>
          <InputField
            class="mt-4"
            type="password"
            name="password"
            placeholder="Password"
            v-model="form.password"
          />
          <span v-if="errors.password" class="text-red-500">{{
              errors.password[0]
            }}</span>
          <p class="mt-2">Confirm Password*</p>
          <InputField
            class="mt-4"
            type="password"
            name="confirm-password"
            placeholder="Confirm password"
            v-model="form.password_confirmation"
          />
          <span v-if="errors.password_confirmation" class="text-red-500">{{
              errors.password_confirmation[0]
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
            <div v-if="item.imageUrl" id="preview" class="flex h-[360px] w-[360px] rounded-full ring-2 ring-white m-2 overflow-hidden mt-2">
              <img class="object-cover transition ease-in-out" v-if="item.imageUrl" :src="item.imageUrl" alt="" />
            </div>
          </div>
          <!-- <button class="bg-red-500 text-white rounded-xl ">Log in</button> -->
          <hr class="mt-4">
          <Button class="mt-4 w-full">
            <slot name="button"> สร้างบัญชีพนักงานสตาฟ </slot>
          </Button>
        </form>
      </div>
    </ContentContainer>
  </MainContainer>
</template>

<script setup lang="js">
import axios from "axios";

const auth = useAuthStore();
if (auth.getUser.role !== 'admin') {
  navigateTo('/');
}
const form = reactive({
  username: "",
  firstname: "",
  lastname: "",
  email: "",
  password: "",
  password_confirmation: "",
  imgPath: "",
  role : "staff"
});
const item = reactive( {
  imageUrl : null
})

const errors = ref([]);


function onChange(e) {
  const file = e.target.files[0]
  form.imgPath = file;
  item.imageUrl = URL.createObjectURL(file)
}

const handleSubmit = async () => {
  try {
    const { data } = await $fetch("http://localhost/api/staff/create ", {
      method: "POST",
      body: { ...form },
    });

    console.log(`auth_store_register`, data);
    navigateTo('/admins')
    // await axios.post('http://localhost/api/staff/create', form, {
    //   headers: {
    //     'Content-Type': 'multipart/form-data'
    //   }
    // }).then(response => {
    //   console.log(response)
    //   // navigateTo('/admins')
    // }).catch(error => {
    //   console.log(error.data.errors)
    //   errors.value = error.data.errors;
    // })
  } catch (error) {
    console.log(error.data.errors)
    errors.value = error.data.errors;
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