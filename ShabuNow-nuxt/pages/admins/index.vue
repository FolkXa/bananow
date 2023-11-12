<template>
  <MainContainer>
    <HeaderContainer>
      <HeaderText> โปรดเลือกเมนูสุดคุ้ม </HeaderText>

      <!-- add by pooh for debugging -->
      <!-- <h1>email : {{ auth.getUser?.email }}</h1>
      <h1>password :{{ auth.getUser?.surname }}</h1>
      <h1>username: {{ auth.getUser }}</h1> -->
      <h1>all :{{ auth.getUser.username }}</h1>

      <!-- <template v-if="token.getStatus">
        <button @click.prevent="auth.logout()">Logout</button>
        <h1>{{ token.getStatus }}</h1>
      </template> -->

      <!-- category dropdown button -->
<!--      <ButtonDropdown title="หมวดหมู่อาหาร" :items="categories" class="mb-4" />-->
      <!-- end -->
    </HeaderContainer>
    <hr />
    <ContentContainer>
      <!-- menu management section -->
      <HeaderContainer class="w-full">
        <div
        class="text-2xl font-medium m-4 py-1.5 px-4 rounded-xl bg-gray-600 text-white"
        >
          <h1 class="flex justify-center items-center"><i class="bi bi-cup-hot-fill mr-2"></i>การจัดการอาหาร</h1>
        </div>

      </HeaderContainer>

      <!-- menu management -->
      <div class="flex justify-center items-center border-b py-6 w-full">
        <div class="flex flex-col w-full max-w-xl">
          <a href="/admins/editMenu">
            <ButtonBorder class="w-full">
              <i class="bi bi-bookmark-plus-fill mr-2"></i>
              จัดการเมนู
            </ButtonBorder>
          </a>
          <a href="/admins/createMenu" class="mt-4">
          <ButtonBorder class="w-full">
            <i class="fa-solid fa-bowl-food mr-2"></i>
            เพิ่มเมนู
            </ButtonBorder>
          </a>
        </div>
      </div>

      <hr>
      <!-- staff management section -->
      <HeaderContainer class="w-full">
        <div
        class="text-2xl font-medium m-4 py-1.5 px-4 rounded-xl bg-gray-600 text-white"
        >
          <h1 class="flex justify-center items-center"><i class="bi bi-people-fill mr-2"></i>การจัดการพนักงาน</h1>
        </div>
      </HeaderContainer>

      <!-- staff management -->
      <div class="flex justify-center items-center border-b py-6 w-full">
        <div class="flex flex-col w-full max-w-xl">
          <a href="/admins/staff/create">
            <ButtonBorder class="w-full">
              <i class="bi bi-person-plus-fill mr-2"></i>
              เพิ่มพนักงานสตาฟ
            </ButtonBorder>
          </a>
          <a href="/admins/staff" class="mt-4">
            <ButtonBorder class="w-full">
              <i class="fa-solid fa-list mr-2"></i>
              ดูรายชื่อพนักงานสตาฟ
            </ButtonBorder>
          </a>
        </div>
      </div>

    </ContentContainer>
  </MainContainer>
</template>

<script>
export default {
  // just some data
  data() {
    definePageMeta({
      middleware: ["auth"],
    });
    const auth = useAuthStore();
    const token = useTokenStore();
    definePageMeta({
      middleware: ["auth"],
      // layout: "custom",
    });
    return {
      foods: [
        {
          imageUrl:
            "https://img.freepik.com/free-photo/delicious-vietnamese-food-including-pho-ga-noodles-spring-rolls-white-wall_181624-34158.jpg?w=1480&t=st=1694914784~exp=1694915384~hmac=28ce6696273496ac0f862aacccb0f3fcf9bb4b466ea118dc34c9bc3aaac883b0",
          title: "Salad",
          price: "฿75",
        },
        {
          imageUrl:
            "https://img.freepik.com/free-photo/fried-pork-with-garlic-pepper-served-with-rice-fried-egg_1150-27374.jpg?w=1480&t=st=1694917028~exp=1694917628~hmac=817a4137204b388d2caebdb1832e665b25d9137da1a062d8ac87461377696e95",
          title: "Fried Pork Rice",
          price: "฿85",
        },
        {
          imageUrl:
            "https://img.freepik.com/free-photo/fried-chicken-parts-fusilli-white-plate_114579-72409.jpg?w=1480&t=st=1694919254~exp=1694919854~hmac=42c4ec7371dcd64d7efcd793238b259b333273cb049d67c36d4a4b26a29294a7",
          title: "Pasta and Chicken",
          price: "฿125",
        },
        {
          imageUrl:
            "https://img.freepik.com/free-photo/stir-fried-instant-noodles-with-seafood-variety-vegetable_1150-27317.jpg?w=740&t=st=1694933851~exp=1694934451~hmac=a4574fd32652c87e0203d95bf152a23675fcca588220cc5edd22b30321a3eef4",
          title: "Spaghetti",
          price: "฿145",
        },
      ],
      token,
      auth,
      // dropdown button
      categories: [
        {
          title: "Food",
          link: "#",
        },
        {
          title: "Drink",
          link: "#",
        },
        {
          title: "Ice Cream",
          link: "#",
        },
        {
          title: "Hot",
          link: "#",
        },
      ],
    };
  },
};

</script>
