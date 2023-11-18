<template>
  <MainContainer>
    <HeaderContainer>
      <HeaderText> โปรดเลือกเมนูสุดคุ้ม </HeaderText>
      <!-- debug by pooh -->
      <!-- <h1>email : {{ auth.getUser?.email }}</h1>
      <h1>password :{{ auth.getUser?.surname }}</h1> -->
<!--      <h1>all :{{ auth.getUser }}</h1>-->
      <!-- <template v-if="token.getStatus">
        <button class="text-red-500" @click.prevent="auth.logout()">Logout</button>
        <h1>{{ token.getStatus }}</h1>
      </template> -->

      <!-- category dropdown button -->
<!--      <ButtonDropdownScroll title="หมวดหมู่อาหาร" :items="categories" class="mb-4" />-->
      <!-- end -->
      <ButtonBorder @click="navigateTo('/admins/createMenu')" v-if="auth.getUser.role === 'admin'">+ เพิ่มเมนู</ButtonBorder>
      <button @click="sendOrder" class="border-2 border-black hover:border-red-600 ease-in-out duration-300 hover:text-red-600 rounded py-1.5 px-4 font-semibold"
              v-if="auth.getUser.role === 'customer'" :href="'/carts/' + table_id">
        <slot><i class="bi bi-cart-plus mr-2"></i>ราคารวม : {{ price }}</slot>
      </button>
    </HeaderContainer>
    <hr />

        <ContentContainer>
            <!-- menu container -->
            <!-- Category Tag -->
            <GridContainer>
                <!-- menu item card -->
<!--                <MenuItemCard-->
<!--                    v-for="menu in menus" :menu="menu"-->
<!--                    :edit_menu="auth.getUser.role === 'admin'? '/admins/editMenu' : null">-->
<!--                    <template v-slot:title>-->
<!--                        &lt;!&ndash; สลัดผักรวมมิตร &ndash;&gt;-->
<!--                        {{ menu.name }}-->
<!--                    </template>-->
<!--                    <template v-slot:price>-->
<!--                        &lt;!&ndash; ราคา ฿55 บาท &ndash;&gt;-->
<!--                        {{ menu.price }}-->
<!--                    </template>-->

<!--                </MenuItemCard>-->
              <div v-for="menu in menus">
                <div
                    class="relative border border-red-600 rounded-xl shadow-lg w-72 h-auto mt-4 p-4 flex flex-col justify-center items-center">
                  <img
                      class="rounded-xl object-cover h-[256px] w-full"
                      :src="'http://localhost' + menu.imgPath || ''"
                      alt="" loading="lazy"/>

                  <!-- edit menu button for admin -->
                  <a v-if="auth.getUser.role === 'admin'? '/admins/editMenu' : false" :href="auth.getUser.role === 'admin'? `/admins/menus/${menu.id}` : null"
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
                    <p class="mt-2 text-xl font-light">
                      {{ menu.description }}
                    </p>

                  </div>
                </div>
              </div>
                <!-- end -->
            </GridContainer>
            <!-- end menu container -->
        </ContentContainer>

    </MainContainer>
</template>

<!--<script lang="js">-->
<!--export default {-->
<!--  data() {-->
<!--    definePageMeta({-->
<!--      middleware: ["auth2"],-->
<!--    });-->
<!--    const auth = useAuthStore();-->
<!--    const token = useTokenStore();-->
<!--    -->
<!--  },-->
<!--};-->
<!--</script>-->
<script setup lang="js">

const counter = ref([]);
const table_id = 3;
const auth = useAuthStore();
if (auth.getUser.role !== 'admin') {
  navigateTo('/')
}
let data = null;
console.log(auth.getUser.role)
async function getPrice() {
  data = await $fetch(`http://localhost/api/order/${auth.getUser.id}/status/ordering`);
  if (data) {
    let totalPrice = 0;
    data.menus?.forEach(menu => {
      totalPrice += menu.price * menu.pivot?.quantity;
      if (menu.pivot) {
        counter.value[menu.id] = menu.pivot.quantity;
      } else {
        menu.pivot = {
          quantity: 0
        }
        counter.value[menu.id] = 0;
      }
    });
    return totalPrice;
  } else {
    return 0
  }
}

const menus = await $fetch("http://localhost/api/menu", {
      method: "GET",
      headers: {
        Accept: "application/json",
      },
});
</script>
