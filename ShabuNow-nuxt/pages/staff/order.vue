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

setInterval(updateSchedule, 60000);

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
orders.value.forEach((a, b) => {
  return new Date(a.receiving_time) - new Date(b.receiving_time);
})
const rejectField = ref([]);
const notes = ref([])
const transactionOpen = ref([]);
// orders = await $fetch(`http://localhost/api/order/18/non_status/ordering`);
let allData = orders;

const orderTable = ref([])
const intervalId = ref();

async function categorizeStatus() {
  clearInterval(intervalId.value)
  orders.value = await $fetch(`http://localhost/api/order/non_status/ordering`);
  orders.value.forEach((a, b) => {
    return new Date(a.receiving_time) - new Date(b.receiving_time);
  })
  allData = orders.value;
  if (orders.value.length === 0) {
    return
  }
  let index = -1;
  let status = []
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
    let date = new Date(order.receiving_time)
    date.setHours(date.getHours() + 7)
    orderTable.value[index].push({
      id : order.id,
      detail :order.detail,
      status : order.status,
      user : order.user,
      date_time : date.toUTCString().split('GMT').join(''),
      menus : menus,
      transactions : order.transactions
    });
  })
  let tmp = orderTable.value;
  tmp.sort((a, b) => {
    return status.indexOf(a) - status.indexOf(b);
  })
  tmp.forEach(item => {
    item.sort((a, b) => {
      return new Date(a.date_time) - new Date(b.date_time);
    })
  })
  orderTable.value = tmp;
  intervalId.value = setInterval(() => categorizeStatus, 30000)
}
// .toUTCString().split('GMT').join('')
async function accept(id) {
  const response = await $fetch(`http://localhost/api/order/${id}/in_queue/${auth.getUser.id}`, {
    method: 'POST'
  });
  location.reload()
}
async function reject(id) {
  let note = notes.value[id]
  const response = await $fetch(`http://localhost/api/order/${id}/rejected/${auth.getUser.id}`, {
    method: 'POST',
    body: { note }
  });
  console.log(response)
  rejectField.value[id] = false;
  notes.value[id] = '';
  location.reload()
}

async function ready(id) {
  const response = await $fetch(`http://localhost/api/order/${id}/ready/${auth.getUser.id}`, {
    method: 'POST'
  });
  location.reload()
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
          sumPrice: menu.price * menu.pivot.quantity
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
