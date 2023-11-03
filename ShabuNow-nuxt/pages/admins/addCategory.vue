<template>
  <MainContainer>
    <HeaderContainer>
      <HeaderText>จัดการหมวดหมู่</HeaderText>
    </HeaderContainer>
    <hr />
    <ContentContainer>

      <!-- category header -->
      <div class="w-2/3 p-4 text-xl font-light border rounded-xl mt-8 ">
        <div class="overflow-hidden border h-[150px] flex flex-col lg:flex-row justify-around items-center rounded-xl bg-cover-category object-cover bg-center bg-no-repeat">
          <h1 class="text-xl lg:text-3xl font-semibold text-white z-10">
            เพิ่มหมวดหมู่อาหาร
          </h1>
          <div class="z-10 text-white text-6xl lg:text-8xl">
            <i class="bi bi-book-half"></i>
          </div>
          <div class="bg-cover-overlay"></div>
        </div>

        <div class="flex flex-col justify-center items-start">
          <form @submit.prevent="addCategory" class="w-full m-0">
            <input
              v-model="categoryName"
              class="mt-4 w-full"
              placeholder="ชื่อหมวดหมู่"
              type="text"
              id="name"/>
            <Button type="submit" class="mt-4 w-full">
              <i class="bi bi-bookmark-plus-fill mr-2"></i>
              เพิ่มหมวดหมู่
            </Button>
          </form>
          </div>
          <div class="border-t w-full mt-8 py-4">
            <h1 class="text-2xl">
              สร้างแล้ว: <span class="font-medium">{{ count }}</span> หมวดหมู่
            </h1>
            <TableNoHeaderForAddCategory :datas="categorys" :headers="header" class="mt-8">

            </TableNoHeaderForAddCategory>
          </div>
        </div>

    </ContentContainer>
  </MainContainer>
</template>

<script setup lang="js">

const categoryName = ref('');
async function addCategory() {
  const name = categoryName.value;
  console.log(categoryName)
  const response = await $fetch(
      "http://localhost/api/category/store",
      {
        method: "POST",
        body: { name }
      }
  )
  if (response.message === "Category stored successfully") {
    fetchData();
  }
}

const categorys = ref([]);

const count = ref(0);

const header = {
  name : 'ชื่อหมวดหมู่'
}

// ตัวอย่างการใช้งาน async function
async function fetchData() {
  try {
    let categories = await $fetch('http://localhost/api/category');
    categorys.value = categories;
    count.value = categories.length;
  } catch (error) {
    console.error(error);
  }
}
fetchData();
</script>>

<style>
.bg-cover-category {
  position: relative;
  background-image: url('../../assets/img/cover1.jpg');
}
.bg-cover-overlay {
  background-color: rgba(0, 0, 0, .35);
  width: 100%;
  height: 100%;
  position: absolute;
}
</style>
