<template>
    <!-- toggle checkbox -->
    <div>
        <label class="flex items-center cursor-pointer">
            <!-- toggle -->
            <div class="relative">
                <!-- input -->
                <input type="checkbox" class="sr-only" :id="id" :name="name" :@change="onchange">
                <!-- line -->
                <div class="block bg-gray-400 dot-bg-color w-14 h-8 rounded-full"></div>
                <!-- dot -->
                <div class="dot absolute left-1 top-1 bg-white w-6 h-6 rounded-full transition"></div>
            </div>
            <!-- label -->
            <div class="ml-3 font-normal text-xl text-gray-700">
                <slot name="toggle_title">Default toggle title</slot>
            </div>
        </label>
    </div>
    <!-- end toggle checkbox -->
</template>

<script setup>
const props = defineProps([
    'id',
    'name',
    'isChecked',
    'onchange'
])

// const onChange = (event) =>{
//     localStorage.setItem('checkboxState', event.target.checked.toString())

//     onChange(event.target.checked);
// }

// const storedState = localStorage.getItem('checkboxState');
// if (storedState !== null) {
//     isChecked = storedState === 'true'; // Convert the string to a boolean
// }
</script>

<style>
input:checked ~ .dot {
  transform: translateX(100%);
}
input:checked ~ .dot-bg-color {
    background-color: rgb(220 38 38);
}
</style>