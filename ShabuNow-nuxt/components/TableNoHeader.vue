<template>
    <div class="flex flex-col justify-center items-center w-full mb-10">
        <table class="text-center text-sm lg:text-lg rounded-xl table-auto w-full border-2 border-slate-400 border-separate border-spacing-3">
            <thead>
                <tr>
                    <th v-for="(header, index) in headers" :key="index" class="border border-slate-300 rounded-xl p-2 bg-gray-100">
                        {{ header }}
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(row, rowIndex) in datas" :key="rowIndex">
                    <td v-for="(data, colIndex) in row" :key="colIndex" class="border border-slate-300 rounded-xl p-2">
                        {{ data }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
export default {
    props: ['datas','headers']
}
</script>