<template>
    <div class="w-2/3 p-4 text-xl font-light border rounded-xl mt-8">
        <h1 class="text-2xl font-semibold text-red-600">
            <slot name="detail_title">
                Default Title
            </slot>
        </h1>
        <slot/>
    </div>
</template>