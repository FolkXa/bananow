<template>
  <MainContainer>
    <HeaderContainer>
      <HeaderText> โปรดเลือกเมนูสุดคุ้ม </HeaderText>
      <!-- debug by pooh -->
      <!-- <h1>email : {{ auth.getUser?.email }}</h1>
      <h1>password :{{ auth.getUser?.surname }}</h1> -->
      <h1>all :{{ auth.getUser }}</h1>
      <!-- <template v-if="token.getStatus">
        <button class="text-red-500" @click.prevent="auth.logout()">Logout</button>
        <h1>{{ token.getStatus }}</h1>
      </template> -->

      <!-- category dropdown button -->
<!--      <ButtonDropdownScroll title="หมวดหมู่อาหาร" :items="categories" class="mb-4" />-->
      <!-- end -->
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
                      :src="menu.imgUrl || ''"
                      alt=""/>

                  <!-- edit menu button for admin -->
                  <a v-if="auth.getUser.role === 'admin'? '/admins/editMenu' : false" :href="auth.getUser.role === 'admin'? '/admins/editMenu' : null"
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
                    <div class="w-full">
                      <div class="custom-number-input h-10 w-full flex flex-col items-center justify-center ">
                        <label for="custom-input-number" class="w-full text-gray-700 text-base text-center font-semibold">
                          <slot name="counter_title">
                          </slot>
                        </label>
                        <div class="flex flex-row h-10 w-full border rounded-lg relative bg-transparent mt-1">
                          <button @click="down(menu.id)" data-action="decrement" class="text-gray-600 hover:text-gray-700 hover:bg-gray-100 h-full w-20 rounded-l cursor-pointer outline-none">
                            <span class="m-auto text-2xl font-thin">−</span>
                          </button>
                          <input
                              class="outline-none focus:outline-none text-center w-full font-semibold text-md hover:text-black focus:text-black  md:text-basecursor-default flex items-center text-gray-700  outline-none"
                              name="custom-input-number"
                              v-model="counter[menu.id]"
                              @input="checkMinValue(menu.id)">
                          <button @click="up(menu.id)" data-action="increment" class="text-gray-600 hover:text-gray-700 hover:bg-gray-100 h-full w-20 rounded-r cursor-pointer">
                            <span class="m-auto text-2xl font-thin">+</span>
                          </button>
                        </div>
                      </div>
                    </div>
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
if (auth.getUser.role === 'admin') {
  navigateTo('/admins/editMenu')
} else if (auth.getUser.role === 'staff') {
  navigateTo('/staff/order')
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
function up(id) {
  if (counter.value[id] < 10) {
    counter.value[id]++
    addPrice(id)
  }
}
function down(id) {
  if (counter.value[id] > 0) {
    counter.value[id]--
    downPrice(id)
  }
}
function checkMinValue(id) {
  if (counter.value[id] < 1) {
    counter.value[id] = 1
  }
}

const menus = await $fetch("http://localhost/api/menu/available", {
      method: "GET",
      headers: {
        Accept: "application/json",
      },
});
function addPrice(menu_id) {
  price.value += menus[menu_id-1].price;
}
function downPrice(menu_id) {
  price.value -= menus[menu_id-1].price;
}

for (const menu of menus) {
  counter.value[menu.id] = 0;
}

const price = ref(0);
price.value = await getPrice();
console.log(auth.getUser)
if (menus) {
  menus[1].pivot = {
    quantity: 0
  }
}
async function sendOrder() {
  data = await $fetch(`http://localhost/api/order/${auth.getUser.id}/status/ordering`);
  const order_id = data.id? data.id : 0;
  const menuSender = [];
  menus.forEach(item => {
    if (counter.value[item.id]) {
      menuSender[item.id] = counter.value[item.id];
    }
  });
  const response = await $fetch(`http://localhost/api/order/${auth.getUser.id}/allStore`, {
    method: 'POST',
    body: {order_id, menuSender}
  })
  console.log(response)
  window.location.href ='/bills'
}
</script>
