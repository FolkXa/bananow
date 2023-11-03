<template>
    <div class="relative border-2 border-red-600 rounded-xl shadow-md w-full mt-8 max-w-5xl">

        <button class="absolute bg-red-600 hover:bg-red-500 flex justify-center items-center rounded-full -top-4 -right-4 w-[40px] h-[40px]">
            <!-- <i class="bi bi-x text-4xl text-white"></i> -->
            <i class="bi bi-trash3-fill text-xl text-white"></i>
        </button>

        <div class="flex flex-col md:flex-row justify-between items-center mx-8 my-4">
            <img class="rounded-lg object-cover h-[300px] w-[750px] md:h-[200px] md:w-[200px]" :src="imageUrl || ''" alt="">
            <div class="w-60 mt-4 text-center">
                <h1 class="text-2xl">
                    <slot name="cart_menu">
                        Default Menu
                    </slot>
                </h1>
            </div>
            <!-- increment and decrement button-->
            <CounterButton class="mt-4 h-auto">
                <template v-slot:counter_title>
                    ใส่จำนวน
                </template>
            </CounterButton>
    
            <div class="mt-4 text-center w-40">
                <h1 class="text-2xl">
                    ราคา: ฿
                    <slot name="cart_price">
                        Default Price
                    </slot>
                </h1>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        imageUrl: String,
    }
}
</script>