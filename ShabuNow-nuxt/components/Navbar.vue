<template>
  <!-- navbar container -->
  <nav
    class="lg:flex flex-row justify-between items-center px-4 w-auto shadow-sm ease-out duration-500 lg:h-[80px]"
    :class="[open ? 'h-[80px]' : 'h-[550px]']"
  >
    <!-- Logo & toggle -->
    <div class="flex justify-between items-center">
      <a href="/" class="flex justify-center items-center">
        <div>
          <img
            class="w-[64px] md:w-[64px] ease-out duration-300"
            src="~/assets/img/logo.png"
            alt="ShabuNow"
          />
        </div>
        <h1 class="text-2xl md:text-xl text-red-600 font-bold ease-out duration-300">
          Shabu<span class="text-black">Now</span>
        </h1>
      </a>
      <!-- Toggle Menu -->
      <span
        @click="menuOpen()"
        class="lg:hidden hover:text-red-600 ease-in-out duration-300 cursor-pointer"
      >
        <i
          class="text-4xl"
          :class="[open ? 'bi bi-justify-left' : 'bi bi-x-lg']"
        ></i>
      </span>
    </div>

    <!-- Links -->
    <div
      class="w-full flex flex-col lg:flex lg:flex-row justify-end items-center text-center text-lg lg:text-sm xl:text-base rounded-lg ease-out duration-300 lg:opacity-100"
      :class="[open ? 'hidden' : 'block']"
    >
      <ul class="lg:flex justify-around items-center w-auto mr-2">
        <li class="lg:m-0 m-4" v-for="link in links">
          <a v-if="auth.getUser.role === link.role"
            :href="link.link"
            class="hover:border hover:border-red-600 hover:border-2 hover:text-red-600 cursor-pointer rounded-xl px-1 py-1.5 mx-2">
            {{ link.name }}
          </a>
        </li>
      </ul>

      <!-- about user -->
      <div class="flex justify-center items-center w-auto">
        <div
          class="flex items-center flex-col lg:flex-row border-t-2 lg:border-l-2 lg:border-t-0 pt-5 lg:pt-0 lg:pl-2"
          >

          <a v-if="auth.getUser.role === 'admin'"
          class="flex justify-center items-center text-gray-600 hover:text-red-500 mb-4 lg:mb-0 ease-out duration-300"
          href="/admins">
          <i class="bi bi-house-gear-fill text-2xl mx-2"></i>
          <!-- <i class="bi bi-gear-fill md:text-xl text-2xl mx-2"></i> -->
          </a>

          <!-- welcome user -->
          <a
            href="/accounts"
            class="mx-4 flex items-center text-red-600 hover:text-red-500 ease-out duration-300"
          >
            <i class="bi bi-person-fill md:text-xl text-2xl mr-2"></i>
            <div class="">
              <p>สวัสดีคุณ,</p>
              <p>{{ auth.getUser.username }}</p>
              <!-- <p>Guest1175</p> -->
            </div>
          </a>
          <!-- end of welcome user-->

          <!-- login and register -->
          <a v-if="!auth.getUser"
          href="/login"
          class="bg-gray-100 p-2 mt-4 lg:mt-0 rounded flex items-center border-2 hover:border-red-600 cursor-pointer text-red-600 hover:text-red-500 ease-out duration-300"
          >
            <i class="bi bi-lock-fill md:text-xl text-2xl mr-2"></i>
            <div class="flex flex-col justify-center items-start">
              <p>เข้าสู่ระบบ</p>
              <p>/สมัครสมาชิก</p>
            </div>
          </a>
          <!-- end of login and register -->

          <!-- logout btn -->
          <Button @click.prevent="auth.logout()" class="lg:mx-4 mt-4 lg:mt-0 flex items-center">
            <i class="bi bi-box-arrow-right md:text-xl text-2xl mr-2"></i>
            <p class="">ออกจากระบบ</p>
          </Button>
          <!-- end of logout btn -->

        </div>
      </div>
    </div>
  </nav>
</template>

<script>
export default {
    setup() {
        const auth = useAuthStore()
        const open = ref(false);
        const links = [
            {name : "เลือกเมนู" , link : "/home", role: 'customer'},
            {name : "ตะกร้าสินค้า" , link : "/carts", role: ''},
            {name : "รายการที่สั่ง" , link : "/history", role: 'customer'},
        ];

    function menuOpen() {
      open.value = !open.value;
    }

    return { links, open, menuOpen, auth };
  },
};
</script>

<style lang="scss" scoped>
* {
  font-family: "Kanit", sans-serif;
}
ul > li > a {
  transition: 0.2s ease-out;
}
</style>
