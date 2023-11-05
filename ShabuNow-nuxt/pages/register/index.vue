<template>
  <section class="bg-gray-50 min-h-screen flex items-center justify-center">
    <!-- login container -->
    <div class="bg-gray-100 flex rounded-2xl shadow-lg max-w-3xl p-5">
      <!-- form -->
      <div class="sm:w-1/2 px-8">
        <h1 class="font-bold text-2xl">Register</h1>
        <form @submit.prevent="handleSubmit" action="" class="flex flex-col gap-4 mt-0">
          <InputField
            class="p-2 rounded-xl mt-4"
            type="text"
            name="username"
            placeholder="Username"
            v-model="form.username"
          />
          <span v-if="errors.username" class="text-red-500">{{
            errors.username[0]
          }}</span>
          <InputField
            class="p-2 rounded-xl"
            type="text"
            name="firstname"
            placeholder="Firstname"
            v-model="form.firstname"
          />
          <span v-if="errors.firstname" class="text-red-500">{{
            errors.firstname[0]
          }}</span>
          <InputField
            class="p-2 rounded-xl"
            type="text"
            name="surname"
            placeholder="Surname"
            v-model="form.surname"
          />
          <span v-if="errors.surname" class="text-red-500">{{
            errors.surname[0]
          }}</span>
          <InputField
            class="p-2 rounded-xl"
            type="email"
            name="email"
            placeholder="Email"
            v-model="form.email"
          />
          <span v-if="errors.email" class="text-red-500">{{
            errors.email[0]
          }}</span>
          <InputField
            class="p-2 rounded-xl"
            type="password"
            name="password"
            placeholder="Password"
            v-model="form.password"
          />
          <span v-if="errors.password" class="text-red-500">{{
            errors.password[0]
          }}</span>
          <InputField
            class="p-2 rounded-xl"
            type="password"
            name="confirmPassword"
            placeholder="Confirm password"
            v-model="form.password_confirmation"
          />
          <span v-if="errors.password_confirmation" class="text-red-500">{{
            errors.password_confirmation[0]
          }}</span>
          <InputField
              class="p-2 rounded-xl"
              type="phone"
              name="phone"
              placeholder="Phone Number"
              v-model="form.phone"
          />
          <span v-if="errors.phone" class="text-red-500">{{
              errors.phone[0]
            }}</span>
          <div class="mx-auto mt-2">
            <label
              for="example1"
              class="mb-1 block text-sm font-medium text-gray-700"
              >อัพโหลดรูปภาพ</label>
            <input
              id="example1"
              type="file"
              class="mt-2 block w-full text-sm file:mr-4 file:rounded-md file:border-0 file:bg-slate-700 file:py-2 file:px-4 file:text-sm file:font-semibold file:text-white hover:file:bg-slate-900 focus:outline-none disabled:pointer-events-none disabled:opacity-60"
              name="photos"
            />
            <span v-if="errors.photos" class="text-red-500">{{
              errors.photos[0]
            }}</span>
          </div>
          <!-- Register Button -->
          <Button>
            <slot name="button"> สมัครสมาชิก </slot>
          </Button>
          <br />
          <!-- Login Here -->
          <p class="mb-8">
            สมัครบัญชีไว้แล้ว?
            <a href="/login" class="underline">เข้าสู่ระบบที่นี่</a>
          </p>
        </form>
      </div>
      <!-- image -->
      <div class="w-1/2 sm:block hidden">
        <img
          class="rounded-2xl"
          src="https://images.unsplash.com/photo-1549048085-bab2f1f49f65?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2487&q=80"
          alt=""
        />
      </div>
    </div>
  </section>
</template>

<script setup>
const auth = useAuthStore();
const token = useTokenStore();
const form = reactive({
  username: "",
  firstname: "",
  surname: "",
  email: "",
  password: "",
  password_confirmation: "",
  photos: "",
});
const errors = ref([]);
const handleSubmit = async () => {
  try {
    await auth.register(form);
    // const { data } = await $fetch("http://localhost/api/login", {
    //   method: "POST",
    //   body: { ...form },
    // });
  } catch (error) {
    // console.log(error.data.errors.email[0]);
    errors.value = error.data.errors;
  }
};
definePageMeta({
  middleware: ["guest"],
  layout: "no-navbar",
});
</script>
