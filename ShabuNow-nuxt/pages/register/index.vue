<template>
  <section class="bg-gray-50 min-h-screen flex items-center justify-center">
    <!-- login container -->
    <div class="bg-gray-100 flex rounded-2xl shadow-lg max-w-3xl p-5">
      <!-- form -->
      <div class="sm:w-1/2 px-8">
        <h1 class="font-bold text-2xl">Register</h1>
        <form @submit.prevent="handleSubmit" action="" class="flex flex-col gap-4 mt-0">
          <p class="mt-2">Username*</p>
          <InputField
            class="m-0 rounded-xl"
            type="text"
            name="username"
            placeholder="Username"
            v-model="form.username"
          />
          <span v-if="errors.username" class="text-red-500">{{
            errors.username[0]
          }}</span>
          <p class="m-0">First Name*</p>
          <InputField
            class="p-0 rounded-xl m-0"
            type="text"
            name="firstname"
            placeholder="First Name"
            v-model="form.firstname"
          />
          <span v-if="errors.firstname" class="text-red-500">{{
            errors.firstname[0]
          }}</span>
          <p class="mt-0">Last Name*</p>
          <InputField
            class="p-0 rounded-xl mt-0"
            type="text"
            name="lastname"
            placeholder="Last Name"
            v-model="form.lastname"
          />
          <span v-if="errors.lastname" class="text-red-500">{{
            errors.surname[0]
          }}</span>
          <p class="mt-0">Email*</p>
          <InputField
            class="p-0 rounded-xl"
            type="email"
            name="email"
            placeholder="Email"
            v-model="form.email"
          />
          <span v-if="errors.email" class="text-red-500">{{
            errors.email[0]
          }}</span>
          <p class="mt-0">Password*</p>
          <InputField
            class="p-0 rounded-xl"
            type="password"
            name="password"
            placeholder="Password"
            v-model="form.password"
          />
          <span v-if="errors.password" class="text-red-500">{{
            errors.password[0]
          }}</span>
          <p class="mt-0">Confirm Password*</p>
          <InputField
            class="p-0 rounded-xl"
            type="password"
            name="confirmPassword"
            placeholder="Confirm Password"
            v-model="form.password_confirmation"
          />
          <span v-if="errors.password_confirmation" class="text-red-500">{{
            errors.password_confirmation[0]
          }}</span>
          <p>Phone Number*</p>
          <InputField
              class="p-0 rounded-xl"
              type="text"
              name="phone"
              placeholder="Phone Number"
              v-model="form.phone"
              @input="formatPhoneNumber"
          />
          <span v-if="errors.phone" class="text-red-500">{{
              errors.phone[0]
            }}</span>
          <div class="mx-auto mt-2">
            <label
              for="example1"
              class="mb-1 block text-sm font-medium text-gray-700"
              >Upload image</label>
            <input
              id="example1"
              type="file"
              class="mt-2 block w-full text-sm file:mr-4 file:rounded-md file:border-0 file:bg-slate-700 file:py-2 file:px-4 file:text-sm file:font-semibold file:text-white hover:file:bg-slate-900 focus:outline-none disabled:pointer-events-none disabled:opacity-60"
              name="image"
              @change="onFileChange"
            />
            <span v-if="errors.imgPath" class="text-red-500">{{
              errors.imgPath[0]
            }}</span>
          </div>
          <!-- Register Button -->
          <Button>
            <slot name="button"> Register </slot>
          </Button>
          <br />
          <!-- Login Here -->
          <p class="mb-8">
            have an account yet?
            <a href="/login" class="underline">Login Here</a>
          </p>
        </form>
      </div>
      <!-- image -->
      <div class="w-1/2 sm:block hidden pt-14">
        <img
          class="rounded-2xl"
          src="https://images.unsplash.com/photo-1549048085-bab2f1f49f65?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2487&q=80"
          alt=""
        />
        <div v-if="urlSelectedFile" class="flex h-[360px] w-[360px] rounded-full ring-2 ring-white m-2 overflow-hidden">
          <img class="object-cover opacity-100 transition ease-in-out hover:opacity-80"
               :src="urlSelectedFile" alt="">
        </div>
      </div>
    </div>
  </section>
</template>

<script setup lang="js">
import axios from "axios";

const auth = useAuthStore();
const token = useTokenStore();
const form = reactive({
  username: "",
  email: "",
  password: "",
  password_confirmation: "",
  phone : "",
  role : "customer",
  firstname: "",
  lastname: ""
});

const errors = ref([]);

function formatPhoneNumber() {
  // Remove any non-numeric characters from the input
  form.phone = form.phone.replace(/\D/g, '');

  // Format the phone number as desired (e.g., add dashes)
  if (form.phone.length === 10) {
    form.phone = form.phone.replace(/(\d{3})(\d{3})(\d{4})/, '$1-$2-$3');
  }
}
const handleSubmit = async () => {
  try {
    // const response = await auth.register(username, firstname, surname, email, password, password_confirmation, photos);
    const response = await $fetch("http://localhost/api/register ", {

      method: "POST",
      body: { ...form },
    });

    console.log('res : ',response)
    await uploadImage(response.data.user.id);
    // const response = await auth.register(form);
    //   method: "POST",
    //   body: { ...form },
    // });

    navigateTo('/login')
  } catch (error) {
    console.log(error);
    errors.value = error.data.errors;
  }
};

function onFileChange(event) {
  selectedFile.value = event.target.files[0];

  if (selectedFile.value) {
    const reader = new FileReader()

    reader.onload = (e) => {
      urlSelectedFile.value = e.target.result
    }

    reader.readAsDataURL(selectedFile.value);
  }
}
const selectedFile = ref();
const urlSelectedFile = ref();
async function uploadImage(id) {
  if (!selectedFile.value) {
    return
  }
  const formData = new FormData();
  formData.append("imgPath", selectedFile.value);

  axios.post(`http://localhost/api/customer/${id}/uploadImage`, formData,             {
    headers: {
      'Content-Type': 'multipart/form-data'
    }
  }).then(response => {
    console.log(response)
  }).catch(error => {
    throw error
  });
}
definePageMeta({
  middleware: ["guest"],
  layout: "no-navbar",
});
</script>
