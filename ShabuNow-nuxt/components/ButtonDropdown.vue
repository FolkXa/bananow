
<template>
    <div class="relative z-10">
        <ButtonBorder @click="isOpen = !isOpen" class="relative">
            <div class="block flex justify-center items-center">
                <p class="mr-2">{{ title }}</p>
                <i class="bi bi-caret-down-fill"></i>
            </div>
        </ButtonBorder>
        <!-- submenu -->
        <transition name="fade" appear>
            <ul v-if="isOpen" class="bg-white mt-1 rounded-lg p-2 border border-black absolute left-0 w-full">
                <li v-for="(item, i) in items" :key="i" class="py-2 px-4 border-b rounded-lg hover:bg-gray-200">
                    <a :href="item.link" class="block py-2 hover:text-red-600">{{ item.title=== undefined? item.name : item.title }}</a>
                </li>
            </ul>
        </transition>
    </div>
</template>

<script>
export default {
    // name: 'dropdown',
    props: ['title', 'items'],
    data() {
        return {
            isOpen: false
        }
    }
}
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: all .2s ease-out;
}

.fade-enter,
.fade-leave-to {
    opacity: 0;
}
</style>