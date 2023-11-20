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
        <Table v-if="order.status !== 'in_queue'" :datas="order.menus" :headers="tableHeaders" class="m-0 w-full">
          <template v-slot:title>
            {{ 'id : '+ order.id +  ' เวลามารับของ : ' + order.date_time }}
          </template>
        </Table>
        <div v-else class="flex flex-col justify-center items-center w-full mb-10 p-4">
          <h1 class="bg-black text-center text-white text-xl rounded-t-xl py-2 px-4">
            {{ 'id : '+ order.id +  ' เวลามารับของ : ' + order.date_time }}
          </h1>
          <table class="text-center text-sm lg:text-lg rounded-xl table-auto w-full border-2 border-slate-400 border-separate border-spacing-3">
            <thead>
            <tr>
              <th  class="border border-slate-300 rounded-xl p-2 bg-gray-100">
                รายการ
              </th>
              <th  class="border border-slate-300 rounded-xl p-2 bg-gray-100">
                จำนวน
              </th>
              <th  class="border border-slate-300 rounded-xl p-2 bg-gray-100">
                ราคา
              </th>
              <th  class="border border-slate-300 rounded-xl p-2 bg-gray-100">
                สถานะ
              </th>
              <th  class="border border-slate-300 rounded-xl p-2 bg-gray-100">
                Action
              </th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(menu, index) in order.menus" :key="index">
              <td class="border border-slate-300 rounded-xl p-4 text-2xl">
                {{ menu.name }}
              </td>
              <td class="border border-slate-300 rounded-xl p-4 text-2xl">
                {{ menu.quantity }}
              </td>
              <td class="border border-slate-300 rounded-xl p-4 text-2xl">
                {{ menu.sumPrice }}
              </td>
              <td v-if="menu.status === 'making'" class="border border-slate-300 rounded-xl p-4 text-2xl">
                making
              </td>
              <td v-if="menu.status === 'ready'" :class="['border border-slate-300 rounded-xl p-4 text-2xl text-green-400']">
                Ready
              </td>
              <td v-if="menu.status" class="border border-slate-300 rounded-xl p-2">
                <Button @click="menuReady(order, index, menu)" class=" py-2 px-2 bg-white hover:bg-green-500">
                  <p class="text-green-500 hover:text-white">
                    <i class="bi bi-pencil-square mr-2"></i>
                    Ready
                  </p>
                </Button>
              </td>
            </tr>
            </tbody>
          </table>
        </div>
        <Nav>
          <NavItem :isActive="!select[order.id]" @click="selectOrderDetail(order.id)">Order Detail</NavItem>
          <NavItem :isActive="select[order.id]" @click="selectTransaction(order.id)">Transactions</NavItem>
        </Nav>
        <hr class="border border-black">
        <div v-if="!select[order.id]" class="flex flex-col justify-center bg-slate-200 rounded my-3 p-3">
          <p v-if="order.detail" class="text-xl">รายละเอียดเพิ่มเติม : {{order.detail}}</p>
          <p class="text-xl">{{'ชื่อลูกค้า : ' + order.user?.firstname + ' ' + order.user?.lastname}}</p>
          <p class="text-xl">{{'เบอร์โทรศัพท์ : ' + order.user?.phone}}</p>
          <p v-if="order.user?.contacts" class="md-6 text-xl">{{ 'ช่องทางติดต่ออื่นๆ : ' + order.user?.contacts }}</p>
        </div>
        <div v-else class="w-3/4 text-xl">
          <div v-if="order.transactions.length" class="flex flex-row w-full text-xl my-4 pl-8">
            <div v-for="transaction in order.transactions" class="w-1/3 bg-slate-200 rounded p-4 mx-2">
              <p>
                {{transaction.before_status}} --> {{transaction.after_status}}
              </p>
              <p class="mt-1">
                {{ transaction.change_date }}
              </p>
              <p class="mt-1">
                Changer : {{ transaction.user? transaction.user.username : 'System' }}
              </p>
            </div>
          </div>
          <div v-else class="justify-center items-center">
            No transactions
          </div>
        </div>
        <div v-if="order.status === 'pending'" class="flex flex-row md-4 mt-3">
          <ButtonBorder @click="accept(order.id)"> Accept </ButtonBorder>
          <ButtonBorder v-if="!rejectField[order.id]" @click="rejectField[order.id] = true" class="ml-4"> Reject </ButtonBorder>
          <ButtonBorder v-if="rejectField[order.id]" @click="reject(order.id)" class="ml-4"> Confirm Reject </ButtonBorder>
        </div>
        <div v-if="order.status === 'in_queue'" class="flex flex-row md-4 mt-3">
          <ButtonBorder v-if="!rejectField[order.id]" @click="rejectField[order.id] = true" class="ml-4"> Reject </ButtonBorder>
          <ButtonBorder v-if="rejectField[order.id]" @click="reject(order.id)" class="ml-4"> Confirm Reject </ButtonBorder>
        </div>
        <div v-if="order.status === 'ready'" class="flex flex-row md-4 mt-3">
          <ButtonBorder @click="done(order.id)"> Done </ButtonBorder>
          <ButtonBorder v-if="!rejectField[order.id]" @click="rejectField[order.id] = true" class="ml-4"> Reject </ButtonBorder>
          <ButtonBorder v-if="rejectField[order.id]" @click="reject(order.id)" class="ml-4"> Confirm Reject </ButtonBorder>
        </div>
        <div v-if="rejectField[order.id]" class="flex flex-row mt-2 justify-center w-full">
        <p class="text-xl p-2">หมายเหตุ :</p>
        <input-field class="ml-1" placeholder="หมายเหตุ" v-model="notes[order.id]" type="text"></input-field>
        </div>
        <span v-if="successMessage[order.id]" class="text-green-400">
          Update Successfully
        </span>
        <span v-if="errorMessage[order.id]" class="text-red-500">
          Error : {{ errorMessage[order.id] }}
        </span>
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
// update schedule
async function updateSchedule() {
  try {
    let r = await $fetch('http://localhost/api/order/updateSchedule', {
      method : 'POST'
    })
    console.log(r)
  } catch (error) {
    console.log(error)
  }
}

setInterval(updateSchedule, 120000);

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
const select = ref([]);
const auth = useAuthStore();
if (auth.getUser.role !== 'staff') {
  navigateTo('/');
}
const orders = ref(await $fetch(`http://localhost/api/order/non_status/ordering`))
const rejectField = ref([]);
const notes = ref([])
const transactionOpen = ref([]);
// orders = await $fetch(`http://localhost/api/order/18/non_status/ordering`);
let allData = orders;

const orderTable = ref([]);
const intervalId = ref();

async function categorizeStatus() {
  clearInterval(intervalId.value)
  orders.value = await $fetch(`http://localhost/api/order/non_status/ordering`);
  let order_details = await $fetch('http://localhost/api/order/getOrderDetails')
  // console.log('order details : ', order_details)
  allData = orders.value;
  if (orders.value.length === 0) {
    return
  }
  let index = -1;
  let status = []
  let tmpTable = []
  orderTable.value = [];
  orders.value.forEach(order => {
    let menus = [];
    let allPrice = 0;
    let allQuantity = 0;
    if (!status.includes(order.status)) {
      status.push(order.status);
      index++
    } else {
      index = status.indexOf(order.status)
    }
    if (!tmpTable[index]) {
      tmpTable[index] = []
    }

    order.menus.forEach(menu => {
      allPrice += menu.price * menu.pivot.quantity;
      allQuantity += menu.pivot.quantity
      if (order.status === 'in_queue') {
        menus.push({
          name: menu.name,
          quantity: menu.pivot.quantity,
          sumPrice: menu.price * menu.pivot.quantity,
          status : order_details.find(item => item.order_id === order.id && item.menu_id === menu.id).status
        });
      } else {
        menus.push({
          name: menu.name,
          quantity: menu.pivot.quantity,
          sumPrice: menu.price * menu.pivot.quantity
        })
      }
    });
    menus.push({
      name: "รวม",
      quantity: allQuantity,
      sumPrice: allPrice
    })
    let date = new Date(order.receiving_time)
    date.setHours(date.getHours() + 7)
    tmpTable[index].push({
      id : order.id,
      detail :order.detail,
      status : order.status,
      user : order.user,
      date_time : date.toUTCString().split('GMT').join(''),
      menus : menus,
      transactions : order.transactions
    });
  })
  orderTable.value = tmpTable;
  console.log('ordertable : ', orderTable.value)
  intervalId.value = setInterval(() => categorizeStatus, 30000)
}
// .toUTCString().split('GMT').join('')

const successMessage = ref([]);
const errorMessage = ref([])

async function menuReady(order, menu_index, menu) {
  try {
    const response = $fetch(`http://localhost/api/order/${order.id}/${menu_index}/updateOrderDetailStatus/ready`, {
      method : 'POST'
    })
    menu.status = 'ready';
    if (!order.menus.find(item => item.status === 'making')) {
      await ready(order.id)
    }
  } catch (error) {
    console.log(error.data)
  }
}
async function accept(id) {
  try {
    const response = await $fetch(`http://localhost/api/order/${id}/in_queue/${auth.getUser.id}`, {
      method: 'POST'
    });
    successMessage.value[id] = true
    setTimeout(() => {
      location.reload();
    }, 1000);
  } catch (error) {
    errorMessage.value[id] = error.data;
  }
}
async function reject(id) {
  try {
    let note = notes.value[id]
    const response = await $fetch(`http://localhost/api/order/${id}/rejected/${auth.getUser.id}`, {
      method: 'POST',
      body: { note }
    });
    successMessage.value[id] = true
    setTimeout(() => {
      location.reload();
    }, 1000);
  } catch (error) {
    errorMessage.value[id] = error.data;
  }
}

async function ready(id) {
  try {
    const response = await $fetch(`http://localhost/api/order/${id}/ready/${auth.getUser.id}`, {
      method: 'POST'
    });
    successMessage.value[id] = true
    setTimeout(() => {
      location.reload();
    }, 1000);
  } catch (error) {
    errorMessage.value[id] = error.data;
  }
}

async function done(id) {
  try {
    const response = await $fetch(`http://localhost/api/order/${id}/done/${auth.getUser.id}`, {
      method: 'POST'
    });
    successMessage.value[id] = true
    setTimeout(() => {
      location.reload();
    }, 1000);
  } catch (error) {
    errorMessage.value[id] = error.data;
  }
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
    let index = -1;
    let status = []
    let orderSearchTable = [];
    let order_details = await $fetch('http://localhost/api/order/getOrderDetails')
    result.forEach(order => {
      let menus = [];
      let allPrice = 0;
      let allQuantity = 0;
      if (!status.includes(order.status)) {
        status.push(order.status);
        index++
      } else {
        index = status.indexOf(order.status)
      }
      if (!orderSearchTable[index]) {
        orderSearchTable[index] = []
      }

      order.menus.forEach(menu => {
        allPrice += menu.price * menu.pivot.quantity;
        allQuantity += menu.pivot.quantity
        menus.push({
          name: menu.name,
          quantity: menu.pivot.quantity,
          sumPrice: menu.price * menu.pivot.quantity,
          status : order_details.find(item => item.order_id === order.id && item.menu_id === menu.id).status
        });
      });
      menus.push({
        name: "รวม",
        quantity: allQuantity,
        sumPrice: allPrice
      })
      let date = new Date(order.order_date)
      date.setHours(date.getHours() + 7)
      orderSearchTable[index].push({
        id : order.id,
        detail :order.detail,
        status : order.status,
        user : order.user,
        date_time : date.toUTCString().split('GMT').join(''),
        menus : menus,
        transactions : order.transactions
      });
    })
    orderTable.value = orderSearchTable;
    console.log('after filter :', orderTable.value)
    intervalId.value = setInterval(() => filterName(), 30000)
  }
}

function selectOrderDetail(id) {
  select.value[id] = false
}

function selectTransaction(id) {
  select.value[id] = true
}

await categorizeStatus()
console.log(orderTable.value)
</script>
