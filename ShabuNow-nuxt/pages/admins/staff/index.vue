<template>
<MainContainer>
  <HeaderContainer>
    <HeaderText>
      รายชื่อพนักงานสตาฟ
    </HeaderText>
  </HeaderContainer>
  <hr>
  <ContentContainer>
    <!-- Staff List -->
<!--    <div v-if="pending"><p>Loading...</p></div>-->
<!--    <div v-else>-->
<!--      <div class="mx-8 my-2 border p-4 rounded-xl hover:bg-green-200"-->
<!--           v-for="staff in staffs" :key="staff.id">-->
<!--        <nuxt-link class="hover:text-blue-500 hover:font-bold"-->
<!--                   :to="`/`">-->
<!--          {{ staff.firstname }} {{ staff.surname }}-->
<!--        </nuxt-link>-->
<!--      </div>-->
<!--    </div>-->
    <fwb-modal v-if="staffsModal">
      <template #header class="border border-black">
        <p>คุณต้องการลบ Account นี้ที้งหรือไม่</p>
      </template>
      <template #body class="border border-black">
        <div class="mx-10">
          <p>
            Username : {{ staffsModal.username }}
          </p>
          <p>
            Email : {{ staffsModal.email }}
          </p>
          <p>
            First Name : {{ staffsModal.firstname }}
          </p>
          <p>
            Last Name : {{ staffsModal.lastname }}
          </p>
        </div>
      </template>
      <hr>
      <template #footer class="border border-black">
        <div class="flex justify-end">
          <div class="flex justify-center py-12 px-4">
            <button class="bg-gray hover:bg-gray-400 ease-in-out duration-300 text-black rounded py-1.5 px-4 font-semibold border border-black"
                    @click="closeModal()">
              cancel
            </button>
            <Button class="ml-4" @click="deleteStaff(staffsModal)">
              delete
            </Button>
          </div>
        </div>

      </template>
    </fwb-modal>
    <div v-if="!staffs"><p>Loading...</p></div>
    <div v-else class="w-full modal-overlay">

<!--      Table -------------------------------------------------------------------->
      <div class="flex flex-col justify-center items-center w-full mb-10 p-4">
        <h1 class="bg-black text-center text-white text-xl rounded-t-xl py-2 px-4">
          รายชื่อพนักงานสตาฟ
        </h1>
        <table class="text-center text-sm lg:text-lg rounded-xl table-auto w-full border-2 border-slate-400 border-separate border-spacing-3">
          <thead>
          <tr>
            <th  class="border border-slate-300 rounded-xl p-2 bg-gray-100">
              User Name
            </th>
            <th  class="border border-slate-300 rounded-xl p-2 bg-gray-100">
              Email
            </th>
            <th  class="border border-slate-300 rounded-xl p-2 bg-gray-100">
              Action
            </th>
          </tr>
          </thead>
          <tbody>
          <tr v-for="staff in staffs" :key="staff.id">
            <td class="border border-slate-300 rounded-xl p-4 text-2xl">
              {{ staff.username }}
            </td>
            <td class="border border-slate-300 rounded-xl p-4 text-2xl">
              {{ staff.email }}
            </td>
            <td class="border border-slate-300 rounded-xl p-2">
                <Button @click="deleteStaff(staff)" class=" py-2 px-2 bg-white hover:bg-red-500">
                  <p class="text-red-500 hover:text-white">
                  <i class="bi bi-pencil-square mr-2"></i>
                    ลบ
                  </p>
                </Button>
            </td>
          </tr>
          </tbody>
        </table>
      </div>
    </div>
<!--    <Table :datas="tableData" :headers="tableHeaders" class="mt-8">-->
<!--      <title>-->
<!--        พนักงานสตาฟทั้งหมด-->
<!--      </title>-->
<!--    </Table>-->

    <a
    href="/admins/staff/create"
    class="">
      <Button class="w-full py-2 px-6">
        <i class="bi bi-person-plus-fill mr-2"></i>
        เพิ่มพนักงานสตาฟ
      </Button>
    </a>

  </ContentContainer>
</MainContainer>
</template>

<script setup lang="js">
import useMyFetch from "~/composables/useMyFetch";
import {FwbModal} from "flowbite-vue";

const config = useRuntimeConfig()
let data = await $fetch('http://localhost/api/getStaffs');
const staffs = ref(data);
const staffsModal = ref();

const deleteStaff = async (staff) => {
  try {
    const response = await $fetch(`http://localhost/api/deleteStaff/${staff.id}`, {
      method : 'POST'
    })
    staffs.value = await $fetch('http://localhost/api/getStaffs');
  } catch (error) {
    console.log(error.value)
  }
}

</script>


