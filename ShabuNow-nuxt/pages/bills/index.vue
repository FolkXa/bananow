<template>
    <MainContainer>
        <HeaderContainer>
            <HeaderText>
                รายการที่สั่ง
            </HeaderText>
        </HeaderContainer>
        <hr>
        <ContentContainer>
            <Table :datas="menuTable" :headers="tableHeaders" class="mt-8">
                <template v-slot:title>
                    {{ auth.getUser.username }}
                </template>
            </Table>

            <!-- Total Price -->
            <TotalPrice>
                <div class="flex justify-center items-center">
                    <h1 class="text-2xl text-gray-700 mr-2">ยอดรวมทั้งหมด:</h1>
                    <span id="discount_price" class="text-red-600 font-semibold text-3xl ml-2 ease-in duration-150">฿ {{ price }}</span>
                </div>
                
<!--                <p class="ml-3 font-normal text-lg text-gray-700 mt-2">-->
<!--                    <i class="bi bi-coin mr-2 text-yellow-500"></i>-->
<!--                    แต้มที่ใช้ไปทั้งหมด : 50 คะแนน-->
<!--                </p>-->
              
                <!-- Add menu button -->
                <Button class="mt-8 py-4 w-full md:w-auto">
                    <i class="bi bi-cash mr-1"></i>
                    ยืนยันการสั่งซื้อ
                </Button>
            </TotalPrice>
                
        </ContentContainer>
    </MainContainer>
</template>

<script lang="js">
export default {
    data() {
        return {
            tableHeaders: [
                'รายการ',
                'จำนวน',
                'ราคา',
            ],
            tableDatas: [
                ['Spaghetti', '3', '450'],
                ['Energy Drink', '3', '80'],
                ['Ice Cream Chocolate', '1', '70'],
            ]
        }
    }
}
</script>

<script setup lang="js">
import {useAuthStore} from "~/stores/auth";

const auth = useAuthStore();
let order;
const menuTable = []
async function getPrice() {
  order = await $fetch(`http://localhost/api/order/${auth.getUser.id}/ordering`);
  if (order) {
    let totalPrice = 0;
    order.menus?.forEach(menu => {
      let priceMenu = menu.price * menu.pivot?.quantity;
      totalPrice += priceMenu;
      menuTable.push({
        name: menu.name,
        quantity : menu.pivot?.quantity,
        sumPrice : priceMenu
      })
    });
    return totalPrice;
  } else {
    return 0
  }
}
const price = await getPrice();
</script>