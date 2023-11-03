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
    <div v-if="pending"><p>Loading...</p></div>
    <div v-else>

<!--      Table -------------------------------------------------------------------->
      <div class="flex flex-col justify-center items-center w-full mb-10 p-4">
        <h1 class="bg-black text-center text-white text-xl rounded-t-xl py-2 px-4">
          รายชื่อพนักงานสตาฟ
        </h1>
        <table class="text-center text-sm lg:text-lg rounded-xl table-auto w-full border-2 border-slate-400 border-separate border-spacing-3">
          <thead>
          <tr>
            <th  class="border border-slate-300 rounded-xl p-2 bg-gray-100">
              Name
            </th>
            <th  class="border border-slate-300 rounded-xl p-2 bg-gray-100">
              Role
            </th>
            <th  class="border border-slate-300 rounded-xl p-2 bg-gray-100">
              Action
            </th>
          </tr>
          </thead>
          <tbody>
          <tr v-for="staff in staffs" :key="staff.id">
            <td class="border border-slate-300 rounded-xl p-4 text-2xl">
              {{ staff.firstname }} {{ staff.surname }}
            </td>
            <td class="border border-slate-300 rounded-xl p-4 text-2xl">
              {{ staff.role }}
            </td>
            <td class="border border-slate-300 rounded-xl p-2">
              <nuxt-link :to="`/admins/staff/${staff.id}`" class="">
                <Button class=" py-2 px-2 bg-white hover:bg-red-500">
                  <p class="text-red-500 hover:text-white">
                  <i class="bi bi-pencil-square mr-2"></i>
                    ตรวจสอบ/แก้ไข
                  </p>
                </Button>
              </nuxt-link>
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

<script setup lang="ts">

import useMyFetch from "~/composables/useMyFetch";

const config = useRuntimeConfig()
const { data: staffs, pending } = await useMyFetch("staff",{})
console.log(staffs.value)
</script>


