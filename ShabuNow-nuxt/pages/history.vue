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
      <ContentContainer v-for="order in orders" class="w-full">
        <Table :datas="order.menus" :headers="tableHeaders" class="m-0 w-full">
          <template v-slot:title>
            {{ order.date_time }}
          </template>
        </Table>
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
let orders = await $fetch(`http://localhost/api/order/${auth.getUser.id}/non_status/ordering`);
// orders = await $fetch(`http://localhost/api/order/18/non_status/ordering`);
console.log(orders)
const orderTable = reactive([])
function categorizeStatus() {
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
    date.setHours(date.getHours() + 14);
    orderTable[index].push({
      order_id : order.id,
      status : order.status,
      date_time : date.toUTCString().split('GMT').join(''),
      menus : menus
    });
  })
}
categorizeStatus()
console.log(orderTable)

</script>
