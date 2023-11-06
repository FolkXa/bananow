<template>
  <MainContainer>
    <HeaderContainer>
      <HeaderText> บัญชีของคุณ </HeaderText>
    </HeaderContainer>
    <hr />
    <ContentContainer>
      <!-- ข้อมูลส่วนตัว -->
      <div
        class="w-2/3 p-4 text-xl font-light border rounded-xl mt-8"
      >
        <h1 class="text-2xl font-semibold text-red-600">ข้อมูลส่วนตัว</h1>

        <p class="mt-4">
          <span class="font-medium"> Username : </span>
          {{ auth.getUser.username }}
        </p>
        <p class="mt-4">
          <span class="font-medium"> ชื่อลูกค้า : </span>
          {{ auth.getUser.firstname }} {{ auth.getUser.lastname }}
        </p>
        <p class="mt-4">
          <span class="font-medium"> เบอร์โทรศัพท์ : </span>
          {{ auth.getUser.phone }}
        </p>
        <div v-if="auth.getUser.role === 'customer'">
        <p class="mt-4">
          <span class="font-medium"> ช่องทางติดต่อ อื่นๆ : </span>
        </p>
        <InputField v-model="formContacts" @input="checkContacts"
                    class="mt-4" placeholder="ช่องทางการติดต่อ อื่นๆ" type="text" name="contacts"/>
        <Button @click="updateContacts" class="mt-4 p-3 text-lg">ยืนยันการเปลี่ยนช่องทางการติดต่อ</Button>
        <span v-if="message.contacts" class="text-red-500">{{
            message.contacts
          }}</span>
        <div class="mt-2">
                <span v-if="message.contacts_success" class="text-green-500">{{
                    message.contacts_success
                  }}</span>
        </div>
        </div>
      </div>

      <!-- เปลี่ยนรหัสผ่าน -->
      <form @submit.prevent="handleChangePassword"
        class="w-2/3 p-4 text-xl font-light border rounded-xl mt-8"
      >
      <h1 class="text-2xl font-semibold text-red-600">เปลี่ยนรหัสผ่าน</h1>
        <p class="mt-4">
          <span class="font-medium"> อีเมลล์ : </span>
          {{ auth.getUser.email }}
        </p>
                <InputField v-model="formData.old_password"
                class="mt-4" placeholder="รหัสผ่านเดิม" type="password" name="current_password">
                    <template v-slot:icon>
                        <i class="bi bi-house-lock text-xl mr-2"></i>
                    </template>
                </InputField>
                <div class="mt-2">
                  <span v-if="message.error" class="text-red-500">{{
                    message.error
                  }}</span>
                </div>
                <InputField v-model="formData.new_password"
                class="mt-4" placeholder="รหัสผ่านใหม่" type="password" name="password">
                    <template v-slot:icon>
                        <i class="bi bi-lock-fill text-xl mr-2"></i> 
                    </template>
                </InputField>
                <!-- confirm password input field -->
                <InputField v-model="formData.new_password_confirmation"
                class="mt-4" placeholder="รหัสผ่านใหม่อีกครั้ง" type="password" name="confirm-password"/>

                <Button class="mt-4 p-3 text-lg">
                    ยืนยันการเปลี่ยนรหัสผ่าน
                </Button>

                <div class="mt-2">
                <span v-if="message.success" class="text-green-500">{{
                  message.success
                }}</span>
                </div>
          </form>
        </ContentContainer>
        <hr>
        <!-- History table -->
        <!-- <Table :datas="tableData" :headers="tableHeaders" class="mt-8">
            <template v-slot:title>
                ประวัติการใช้บริการ
            </template>

        </Table> -->
    </MainContainer>
</template>

<script setup lang="ts">
const route = useRoute()
const auth = useAuthStore()
const token = useTokenStore();
const formData = reactive({
  old_password: "",
  new_password: "",
  new_password_confirmation: "",
})

const data = await $fetch(`http://localhost/api/getContacts/${auth.getUser.id}`)
const formContacts = ref(data);
console.log(token)

function checkContacts() {
  if (formContacts.value.length > 30) {
    formContacts.value = formContacts.value.substring(0, 30)
  }
}

async function updateContacts() {
  let contacts = formContacts.value
  try {
    const response = await $fetch(`http://localhost/api/updateContacts/${auth.getUser.id}`, {
      method: 'POST',
      body: { contacts }
    })
    message.contacts = ''
    message.contacts_success = 'เปลี่ยนช่องทางการติดต่อสำเร็จ'
  } catch (error) {
    message.contacts = 'เปลี่ยนช่องทางการติดต่อไม่สำเร็จ'
    message.contacts_success = ''
  }

}


const message = reactive({
  success: "",
  error: "",
  contacts: "",
  contacts_success: ""
})

async function handleChangePassword() {
  console.log(auth.getUser)
  console.log(formData)
  try {
    if (formData.old_password !== "") {
      const customer = await $fetch(`http://localhost/api/customer/${auth.getUser.id}/updatePassword`, {
        method: "POST",
        body: formData,
      })
      message.success = "เปลี่ยนรหัสผ่านสำเร็จ"
      message.error = ""
    } else {
      message.error = "โปรดใส่รหัสผ่าน"
      message.success = ""
    }
  } catch (error) {
    console.log(error)
    if (error.response && error.response.data && error.response.data.error) {
      message.error = error.response.data.error
      message.success = ""
    } else {
      message.error = "เกิดข้อผิดพลาด"
      message.success = ""
    }
  }
}
</script>

<!-- <script>
export default {
  data() {
      const auth = useAuthStore()
      const form = reactive({
        old_password: "",
        new_password: "",
        // password_confirmation: "",
      })
      const errors = ref([])
      const handleChangePasswd = async () => {
        try {
          await auth.update(form)
        } catch (error) {

        }
      }
        return {
            auth,
            form,
            errors,
            handleChangePasswd,
            // customers or users detail
            
            // add some datas to table
            tableHeaders: [
                'No.',
                'Price',
                'Date',
                'Used Points',
            ],
            // add table headers
            tableData: [
                ['1', '3750', '11/12/2023', '150'],
                ['2', '2250', '10/12/2023', '53'],
                ['3', '750', '9/12/2023', '250'],
                ['4', '1750', '1/12/2023', '10'],
                ['5', '4200', '28/11/2023', '55'],
            ],
        }
    },
}
</script> -->
