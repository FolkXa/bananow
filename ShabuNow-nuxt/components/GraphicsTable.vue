<template>
    <a v-for="(tableNumber, index) in tableNumbers" :key="index" class="relative p-4 m-4 ease-out duration-300 border-2 hover:border-red-600 rounded-lg cursor-pointer">
        <div class="w-[100px] h-[120px] bg-amber-600 rounded-lg flex justify-center items-center z-10">
            <div class="flex flex-col justify-center items-center">
                <span class="text-base text-white">โต๊ะที่</span>
                <h1 class="text-4xl text-white font-normal">
                  {{ tableNumber }}
                </h1>
            </div>
        </div>
    
        <div class="w-[28px] h-[28px] bg-amber-800 rounded-full absolute top-6 left-1 -z-10"></div>
        <div class="w-[28px] h-[28px] bg-amber-800 rounded-full absolute top-6 right-1 -z-10"></div>
        <div class="w-[28px] h-[28px] bg-amber-800 rounded-full absolute bottom-6 left-1 -z-10"></div>
        <div class="w-[28px] h-[28px] bg-amber-800 rounded-full absolute bottom-6 right-1 -z-10"></div>
    </a>
</template>

<script>
export default {
    props: ['tableNumbers'],
}
</script>