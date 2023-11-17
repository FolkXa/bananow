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
      <div class="relative z-10">
        <p>ค้นหาออเดอร์ลูกค้า (ชื่อลูกค้า)</p>
        <input
            class="border border-red-500 rounded text-xl pb-1"
            type="text"
            name="search"
            placeholder="search..."
            v-model="search"
        >
        <Button @click="filterName" class="mr-3">Search</Button>
        <ButtonBorder @click="isOpen = !isOpen" class="relative">
          <div class="block flex justify-center items-center">
            <p class="mr-2">สถานะ</p>
            <i class="bi bi-caret-down-fill"></i>
          </div>
        </ButtonBorder>
        <!-- submenu -->
        <transition name="fade" appear>
          <ul v-if="isOpen" class="bg-white mt-1 rounded-lg p-2 border border-black absolute left-0 w-full">
            <li v-for="(item, i) in status" :key="i" class="py-2 px-4 border-b rounded-lg hover:bg-gray-200">
              <a @click="filter(item.title)" class="block py-2 hover:text-red-600">{{ item.title=== undefined? item.name : item.title }}</a>
            </li>
          </ul>
        </transition>
      </div>
    </HeaderContainer>
    <hr />

    <ContentContainer v-for="orders in orderTable" name="status">
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
            {{ 'id : '+ order.id +  ' เวลามารับของ : ' + order.date_time }}
          </template>
        </Table>
        <p v-if="order.detail" class="text-xl">รายละเอียดเพิ่มเติม : {{order.detail}}</p>
        <p class="text-xl mt-2">{{'ชื่อลูกค้า : ' + order.user?.firstname + ' ' + order.user?.lastname
          + '   เบอร์โทรศัพท์ : ' + order.user?.phone}}</p>
        <p v-if="order.user?.contacts" class="md-6 text-xl">{{ 'ช่องทางติดต่ออื่นๆ : ' + order.user?.contacts }}</p>
        <div v-if="order.status === 'pending'" class="flex flex-row md-4 mt-3">
          <ButtonBorder @click="accept(order.id)"> Accept </ButtonBorder>
          <ButtonBorder v-if="!rejectField[order.id]" @click="rejectField[order.id] = true" class="ml-4"> Reject </ButtonBorder>
          <ButtonBorder v-if="rejectField[order.id]" @click="reject(order.id)" class="ml-4"> Confirm Reject </ButtonBorder>

        </div>
        <div v-if="order.status === 'in_queue'" class="flex flex-row md-4">
          <ButtonBorder @click="ready(order.id)"> Ready </ButtonBorder>
          <ButtonBorder v-if="!rejectField[order.id]" @click="rejectField[order.id] = true" class="ml-4"> Reject </ButtonBorder>
          <ButtonBorder v-if="rejectField[order.id]" @click="reject(order.id)" class="ml-4"> Confirm Reject </ButtonBorder>
        </div>
        <div v-if="rejectField[order.id]" class="flex flex-row mt-2 justify-center w-full">
        <p class="text-xl p-2">หมายเหตุ :</p>
        <input-field class="ml-1" placeholder="หมายเหตุ" v-model="notes[order.id]" type="text"></input-field>
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

import {all} from "axios";

const status = []
status.push({
  title: 'all'
})
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
let isOpen = ref(false);
const auth = useAuthStore();
const orders = ref(await $fetch(`http://localhost/api/order/non_status/ordering`))
const rejectField = ref([]);
const notes = ref([])
// orders = await $fetch(`http://localhost/api/order/18/non_status/ordering`);
orders.value.sort((a, b) => {
  return new Date(a.receiving_time) - new Date(b.receiving_time);
});
let allData = orders;

const orderTable = ref([])
const intervalId = ref();

async function categorizeStatus() {
  clearInterval(intervalId.value)
  orders.value = await $fetch(`http://localhost/api/order/non_status/ordering`);
  orders.value.sort((a, b) => {
    return new Date(a.receiving_time) - new Date(b.receiving_time);
  });
  allData = orders.value;
  if (!orders.value) {
    return
  }
  let index = -1;
  let status = ['pending', 'in_queue', 'ready', 'done', 'rejected']
  orders.value.forEach(order => {
    let menus = [];
    let allPrice = 0;
    let allQuantity = 0;
    index = status.indexOf(order.status)

    if (!orderTable.value[index]) {
      orderTable.value[index] = []
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
    orderTable.value[index].push({
      id : order.id,
      detail :order.detail,
      status : order.status,
      user : order.user,
      date_time : date.toUTCString().split('GMT').join(''),
      menus : menus
    });
  })
  intervalId.value = setInterval(() => categorizeStatus, 30000)
}
// .toUTCString().split('GMT').join('')
async function accept(id) {
  const response = await $fetch(`http://localhost/api/order/${id}/in_queue`, {
    method: 'POST'
  });
  await categorizeStatus()
}
async function reject(id) {
  let note = notes.value[id]
  const response = await $fetch(`http://localhost/api/order/${id}/rejected`, {
    method: 'POST',
    body: { note }
  });
  console.log(response)
  rejectField.value[id] = false;
  notes.value[id] = '';
  await categorizeStatus()
}

async function ready(id) {
  const response = await $fetch(`http://localhost/api/order/${id}/ready`, {
    method: 'POST'
  });
  await categorizeStatus()
}

function filter(status) {
  let statusElems = document.getElementsByName('status');
  if (status === 'all') {
    statusElems.forEach(elem => {
      elem.style.display = ''
    })
  } else {
    statusElems.forEach(elem => {
      if (!elem.textContent.slice(0,30).includes(status)) {
        elem.style.display = "none";
      } else {
        elem.style.display = "";
      }
    })
  }
}

const search = ref()
async function filterName() {
  console.log(search)
  if (!search.value) {
    await categorizeStatus()
  } else {
    filter('all');
    let result = [];
    allData.forEach(order => {
      let name = order.user.firstname + ' ' + order.user.lastname
      if (name.toLowerCase().includes(search.value.toLowerCase())) {
        result.push(order);
      }
    })

    clearInterval(intervalId.value)
    if (!result) {
      return
    }
    let index = 0;
    let status = ['pending', 'in_queue', 'ready', 'done', 'rejected']
    orderTable.value = [];
    result.forEach(order => {
      let menus = [];
      let allPrice = 0;
      let allQuantity = 0;
      index = status.indexOf(order.status)

      if (!orderTable.value[index]) {
        orderTable.value[index] = []
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
      orderTable.value[index].push({
        id : order.id,
        detail :order.detail,
        status : order.status,
        user : order.user,
        date_time : date.toUTCString().split('GMT').join(''),
        menus : menus
      });
    })
    console.log('after filter :', orderTable.value)
  }
}

await categorizeStatus()
console.log(orderTable.value)
</script>
