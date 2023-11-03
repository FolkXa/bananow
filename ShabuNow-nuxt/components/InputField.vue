<template>
    <label class="relative block">
        <span class="absolute inset-y-0 left-0 flex items-center pl-2">
            <slot name="icon">

            </slot>
        </span>
        <input class="placeholder:text-slate-400 placeholder:text-base block border border-slate-300 rounded-md w-full py-3 pl-9 pr-3 shadow-sm focus:outline-none focus:border-red-600 focus:ring-red-600 focus:ring-1 sm:text-base" 
        :placeholder="placeholder" 
        :type="type" 
        :name="name"
        :id="id"
        :value="modelValue"
        @input="emitInput"/>
    </label>
</template>

<script>
export default {
    props: ['placeholder','type','name','id','modelValue'],
    methods: {
        emitInput(event) {
            this.$emit('update:modelValue', event.target.value)
        }
    }
}
</script>