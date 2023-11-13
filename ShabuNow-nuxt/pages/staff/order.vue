<template>
  <MainContainer>
    <HeaderContainer>
      <HeaderText> รายการสั่งซื้อของลูกค้า </HeaderText>
      <!-- debug by pooh -->
      <!-- <h1>email : {{ auth.getUser?.email }}</h1>
      <h1>password :{{ auth.getUser?.surname }}</h1> -->
      <!--      <h1>all :{{ auth.getUser }}</h1>-->
      <!-- <template v-if="token.getStatus">
        <button class="text-red-500" @click.prevent="auth.logout()">Logout</button>
        <h1>{{ token.getStatus }}</h1>
      </template> -->

      <!-- category dropdown button -->
      <ButtonDropdown title="status" :items="status"></ButtonDropdown>
    </HeaderContainer>
    <hr />

    <ContentContainer v-for="orders in orderTable">
      <!-- menu container -->
      <!-- Category Tag -->
      <HeaderContainer class="w-full">
        <div class="text-2xl font-medium m-4 py-1.5 px-4 rounded-lg bg-red-600 text-white">
          <h1>สถานะ : {{orders[0].status }}</h1>
        </div>

      </HeaderContainer>
      <ContentContainer v-for="order in orders" class="w-full border-2 border-black border-separate border-spacing-3 rounded-xl p-4">
        <Table :datas="order.menus" :headers="tableHeaders" class="m-0 w-full">
          <template v-slot:title>
            {{ 'id:'+ order.id +  ' receiving time : ' + order.date_time }}
          </template>
        </Table>
        {{ order.user }}
        <p class="text-xl">{{'ชื่อลูกค้า : ' + order.user?.firstname + ' ' + order.user?.lastname
          + '   เบอร์โทรศัพท์ : ' + order.user?.phone}}</p>
        <p v-if="order.user?.contacts" class="md-6 text-xl">{{ 'ช่องทางติดต่ออื่นๆ : ' + order.user?.contacts }}</p>
        <div v-if="order.status === 'pending'" class="flex flex-row md-4 mt-3">
          <ButtonBorder @click="accept(order.id)"> Accept </ButtonBorder>
          <ButtonBorder @click="reject(order.id)" class="ml-4"> Reject </ButtonBorder>
        </div>
        <div v-if="order.status === 'in_queue'" class="flex flex-row md-4">
          <ButtonBorder @click="ready(order.id)"> Ready </ButtonBorder>
          <ButtonBorder @click="reject(order.id)" class="ml-4"> Reject </ButtonBorder>
        </div>
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

const status = []
status.push({
  title: 'pending',
  link: '/staff/orders/1'
});

status.push({
  title: 'in_queue',
  link: '/staff/orders/2'
});

status.push({
  title: 'ready',
  link: '/staff/orders/3'
});

status.push({
  title: 'done',
  link: '/staff/orders/4'
});
status.push({
  title: 'rejected',
  link: '/staff/orders/5'
});


const tableHeaders = [
  'รายการ',
  'จำนวน',
  'ราคา',
]
const auth = useAuthStore();
let orders = await $fetch(`http://localhost/api/order/non_status/ordering`);
// orders = await $fetch(`http://localhost/api/order/18/non_status/ordering`);
console.log(orders)
orders.sort((a, b) => {
  return new Date(a.receiving_time) - new Date(b.receiving_time);
});

let orderTable = reactive([])
function categorizeStatus() {
  if (!orders) {
    return
  }
  let index = -1;
  let status = ['pending', 'in_queue', 'ready', 'done', 'rejected']
  orders.forEach(order => {
    let menus = [];
    let allPrice = 0;
    let allQuantity = 0;
    index = status.indexOf(order.status)

    if (!orderTable[index]) {
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
    let date = new Date(order.order_date)
    orderTable[index].push({
      id : order.id,
      status : order.status,
      user : order.user,
      date_time : date.toUTCString().split('GMT').join(''),
      menus : menus
    });
  })
}
// .toUTCString().split('GMT').join('')
async function accept(id) {
  const response = await $fetch(`http://localhost/api/order/${id}/in_queue`, {
    method: 'POST'
  });
  location.reload();
}
async function reject(id) {
  const response = await $fetch(`http://localhost/api/order/${id}/rejected`, {
    method: 'POST'
  });
  location.reload();
}

async function ready(id) {
  const response = await $fetch(`http://localhost/api/order/${id}/ready`, {
    method: 'POST'
  });
  location.reload();
}

categorizeStatus()
console.log(orderTable)
</script>
