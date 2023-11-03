<template>
  <MainContainer>
    <HeaderContainer>
      <HeaderText>รายละเอียดเมนู</HeaderText>
    </HeaderContainer>
    <hr />
    <ContentContainer>
      <DetailCard class="mb-12">
        <template v-slot:detail_title>แก้ไขรายละเอียดเมนูอาหาร</template>
        <InputField
          placeholder="ชื่อเมนู"
          type="text"
          name="confirm-password"
          value="5"
          class="mt-4"
        />
        <InputField
          placeholder="ราคา (THB)"
          type="number"
          name="confirm-password"
          class="mt-4"
        />
        <select
        class="mt-4 text-base text-slate-400 focus:ring-red-600 focus:border-red-600 focus:ring-1 focus:outline-none p-2.5 block bg-white border border-slate-300 rounded-md w-full py-3 pl-9 pr-3"
        >
          <option selected>เลือกหมวดหมู่</option>
          <option value="food">อาหาร</option>
          <option value="drink">เครื่องดื่ม</option>
          <option value="sweet">ของหวาน</option>
        </select>

        <textarea
        row="2"
          class="mt-4 text-base block p-2.5 w-full bg-white border border-slate-300 rounded-lg focus:ring-red-600 focus:border-red-600 focus:ring-1 focus:outline-none"
          placeholder="คำอธิบายเพิ่มเติม"
        ></textarea>
        
        <div class="mx-auto mt-2">
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
        <Button class="mt-4 w-full">
          <slot name="button">ยืนยัน</slot>
        </Button>
      </DetailCard>

    </ContentContainer>
  </MainContainer>
</template>
