<template>
<MainContainer>
    <HeaderContainer>
        <HeaderText>
            เมนู : {{menu.name}}
        </HeaderText>
    </HeaderContainer>
    <hr>
    <ContentContainer>
        <div class="my-8 w-full flex flex-col lg:flex-row justify-start items-center lg:items-start">
            <div class="flex flex-col justify-between items-center w-2/3">
                <div class="w-full flex flex-col justify-between items-start">
                    <img 
                    width="500"
                    height="500"
                    :src="menu.imgPath"
                    alt="Salad"
                    class="rounded-lg object-cover w-full">
                    <div class="my-4 border-y">
                        <div class="text-2xl mt-4 font-medium py-1.5 px-4 rounded-lg border-2 text-red-600 border-red-600">
                            <h1>ราคา ฿ {{menu.price}} บาท</h1>
                        </div>
                        <hr class="my-4">
                        <h1 class="text-xl mt-2 underline">รายละเอียดเมนู</h1>
                        <p class="my-4 text-lg text-gray-800">
                            {{ menu.description }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="px-4 lg:px-8 py-4 w-2/3">
                <!-- status menu option for chef role -->
                <div v-if="auth.getUser.role === 'admin'" class="flex flex-col justify-start items-start text-lg">
                    <h1 class="text-xl lg:text-2xl text-red-600">ปรับเปลี่ยนสถานะของเมนู</h1>
                    <select
                    class="mt-4 text-base focus:ring-red-600 focus:border-red-600 focus:ring-1 focus:outline-none p-2.5 block bg-white border border-slate-300 rounded-md w-full py-3 pl-9 pr-3"
                    >
                      <option value="available">พร้อมเสิร์ฟ (READY)</option>
                      <option value="canceled">ยกเลิกชั่วคราว (NOT READY)</option>
                    </select>
                    <Button class="mt-4 w-full">
                        ยืนยันเปลี่ยนสถานะ
                    </Button>
                </div>

                <!-- for customer -->
                <counter-button class="w-full" title="จำนวน">

                </counter-button>
                <div v-if="auth.getUser.role === 'customer'" class="flex flex-col justify-start items-start text-lg">
                    <Button @click="addMenu()" class="mt-4 w-full">
                        <i class="bi bi-cart-plus mr-2"></i>
                        เพิ่มเข้าตะกร้า
                    </Button>
                </div>

            </div>

        </div>
    </ContentContainer>
</MainContainer>
</template>

<script setup lang="js">
import {useRoute} from "vue-router";
import {useAuthStore} from "~/stores/auth";

const auth = useAuthStore();
const route = useRoute();
const menu = await $fetch(`http://localhost/api/menu/${route.params.id}`)
console.log(menu)

async function addMenu() {
  if (auth.getUser.role !== 'customer') {
    return
  }
  const menu_id = route.params.id;
  const quantity = document.getElementsByName('custom-input-number')[0].value
  console.log(menu_id, quantity)
  const response = await $fetch(`http://localhost/api/order/${route.params.table_id}/addMenu`, {
    method: 'POST',
    body: { menu_id, quantity }
  });
  console.log(response)
  window.location.href = '/'
}

</script>