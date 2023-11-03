
import { Input } from 'postcss';
<template>
  <MainContainer>
    <HeaderContainer>
      <HeaderText>สร้างเจ้าหน้าที่</HeaderText>
    </HeaderContainer>
    <hr />
    <ContentContainer>
      <div class="w-2/3 p-4 text-xl font-light border rounded-xl mt-8">

        <div class="overflow-hidden border h-[150px] flex flex-col lg:flex-row justify-around items-center rounded-xl bg-cover-category object-cover bg-center bg-no-repeat">
          <h1 class="text-xl lg:text-3xl font-semibold text-white z-10">
            เพิ่มพนักงานสตาฟ
          </h1>
          <div class="z-10 text-white text-6xl lg:text-8xl">
            <i class="bi bi-person-plus-fill"></i>
          </div>
          <div class="bg-cover-overlay"></div>
        </div>

        <form action="">
          <InputField
            class="mt-4"
            type="text"
            name="firstname"
            placeholder="Firstname"
          />
          <InputField
            class="mt-4"
            type="text"
            name="lastname"
            placeholder="Lastname"
          />
          <InputField
            class="mt-4"
            type="email"
            name="email"
            placeholder="Email"
          />
          <InputField
            class="mt-4"
            type="password"
            name="password"
            placeholder="Password"
          />
          <InputField
            class="mt-4"
            type="password"
            name="confirm-password"
            placeholder="Confirm password"
          />
          <div class="mx-auto my-8">
            <label
              for="example1"
              class="mt-4 mb-1 block text-lg font-medium text-gray-700"
              >อัพโหลดรูปภาพ</label
            >
            <input
              id="example1"
              type="file"
              class="mt-2 block w-full text-sm file:mr-4 file:rounded-md file:border-0 file:bg-slate-700 file:py-2 file:px-4 file:text-sm file:font-semibold file:text-white hover:file:bg-slate-900 focus:outline-none disabled:pointer-events-none disabled:opacity-60"
            />
          </div>
          <!-- <button class="bg-red-500 text-white rounded-xl ">Log in</button> -->
          <hr class="mt-4">
          <Button class="mt-4 w-full">
            <slot name="button"> สร้างบัญชีพนักงานสตาฟ </slot>
          </Button>
        </form>
      </div>
    </ContentContainer>
  </MainContainer>
</template>

<style>
.bg-cover-category {
  position: relative;
  background-image: url('../../assets/img/cover1.jpg');
  background-position: center;
}
.bg-cover-overlay {
  background-color: rgba(0, 0, 0, .35);
  width: 100%;
  height: 100%;
  position: absolute;
}
</style>