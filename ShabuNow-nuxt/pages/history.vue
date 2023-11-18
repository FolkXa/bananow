<template>
  <MainContainer>
    <HeaderContainer>
      <HeaderText> ประวัติการทำรายการ </HeaderText>
      <!-- debug by pooh -->
      <!-- <h1>email : {{ auth.getUser?.email }}</h1>
      <h1>password :{{ auth.getUser?.surname }}</h1> -->
      <!--      <h1>all :{{ auth.getUser }}</h1>-->
      <!-- <template v-if="token.getStatus">
        <button class="text-red-500" @click.prevent="auth.logout()">Logout</button>
        <h1>{{ token.getStatus }}</h1>
      </template> -->

      <!-- category dropdown button -->
    </HeaderContainer>
    <hr />

    <ContentContainer v-for="orders in orderTable">
      <!-- menu container -->
      <!-- Category Tag -->
      <HeaderContainer class="w-full">
        <div class="text-2xl font-medium m-4 py-1.5 px-4 rounded-lg bg-red-600 text-white">
          <h1>สถานะ : {{ orders[0].status }}</h1>
        </div>

      </HeaderContainer>
      <ContentContainer v-for="order in orders" class="w-full border border-gray-200 p-3 rounded">
        <Table :datas="order.menus" :headers="tableHeaders" class="m-0 w-full">
          <template v-slot:title>
            Order ที่ {{ number - order.index }} , เวลารับของ : {{ order.date_time }}
          </template>
        </Table>
        <p class="text-xl">วันที่สั่ง : {{ order.order_date }}</p>
        <p v-if="order.detail" class="text-xl">รายละเอียดเพิ่มเติม : {{order.detail}}</p>
        <span v-if="order.note" class="text-xl text-red-500 font-red">หมายเหตุ : {{ order.note }}</span>
        <hr/>
        <!-- end -->
      </ContentContainer>
      <!-- end menu container -->
    </ContentContainer>

  </MainContainer>
</template>

<script lang="js">
export default {
  data() {
    definePageMeta({
      middleware: ["auth2"],
    });
  },
};
</script>
<script setup lang="js">

const tableHeaders = [
  'รายการ',
  'จำนวน',
  'ราคา',
]
const auth = useAuthStore();
if (auth.getUser.role !== 'customer') {
  navigateTo('/');
}
let orders = null
// orders = await $fetch(`http://localhost/api/order/18/non_status/ordering`);
const orderTable = reactive([])
let number = 1;
const intervalId = ref();
async function categorizeStatus(s) {
  clearInterval(intervalId.value)
  let orders = await $fetch(`http://localhost/api/order/${auth.getUser.id}${s}`);
  if (!orders) {
    return
  }
  let index = -1;
  let status = []
  orders.forEach(order => {
    let menus = [];
    let allPrice = 0;
    let allQuantity = 0;
    if (!status.includes(order.status)) {
      status.push(order.status)
      index++
      orderTable[index] = []
    }

    order.menus.forEach(menu => {
      allPrice += menu.price * menu.pivot.quantity;
      allQuantity += menu.pivot.quantity
      menus.push({
        name: menu.name,
        quantity: menu.pivot.quantity,
        sumPrice: menu.price * menu.pivot.quantity
      });
    });
    menus.push({
      name: "รวม",
      quantity: allQuantity,
      sumPrice: allPrice
    })
    let date = new Date(order.receiving_time);
    let order_date = new Date(order.order_date);
    order_date.setHours(order_date.getHours() + 7)
    date.setHours(date.getHours() + 7);
    orderTable[index].push({
      index : number,
      detail : order.detail,
      order_id : order.id,
      status : order.status,
      note : order.note,
      date_time : date.toUTCString().split('GMT').join(''),
      order_date : order_date.toUTCString().split('GMT').join(''),
      menus : menus
    });
    number++
  })
  intervalId.value = setInterval(() => categorizeStatus(s), 5000)
}
console.log(orderTable)
categorizeStatus('/non_status/ordering')
</script>
