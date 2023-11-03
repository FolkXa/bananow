<template>
    <MainContainer>
        <HeaderContainer>
            <HeaderText>
                ตะกร้าสินค้า
            </HeaderText>
        </HeaderContainer>
        <hr>
        <ContentContainer>
            <!-- Ordered List -->
            <CartListCard v-for="order in orders" :imageUrl="order.imageUrl">


            </CartListCard>

            <!-- Total Price -->
            <TotalPrice>

                <div class="flex justify-center items-center">
                    <h1 class="text-2xl text-gray-700 mr-2">รวมการสั่งซื้อ:</h1>
                    <span id="original_price" class="text-red-600 font-semibold text-3xl ease-linear duration-150">฿ 500</span>
                </div>



                <hr>
                <!-- Add menu button -->
                <Button class="mt-8 py-4 w-full md:w-auto">
                <i class="bi bi-plus-lg mr-1"></i>
                    เพิ่มเมนูอาหาร
                </Button>

            </TotalPrice>

        </ContentContainer>
    </MainContainer>
</template>

<script setup lang="ts">
const route = useRoute();
//   const { data: orders, pending } = await useMyFetch<any>(`order/${route.params.id}`, {});
const { data: orders, pending } = await $fetch<any>(`http://localhost/api/order/${route.params.id}`)
console.log(orders);
</script>