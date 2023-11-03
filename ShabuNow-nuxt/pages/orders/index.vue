<template>
    <MainContainer>
        <HeaderContainer>
            <HeaderText>
                คำสั่งซื้อของลูกค้า
            </HeaderText>
        </HeaderContainer>
        <hr>
        <ContentContainer>

            <h1 class="mt-4 text-xl text-red-600 font-medium">จำนวนโต๊ะทั้งหมด : {{ counter.count }}</h1>
            <!-- Add table and Delete table-->
            <div class="mt-4 flex justify-center items-center w-full">
                <Button @click="showConfirmationDialog('add')" class="py-3 ml-2">
                    <i class="bi bi-plus-circle text-xl mr-2"></i>
                    เพิ่มโต๊ะ
                </Button>
                <Button @click="showConfirmationDialog('delete')" class="py-3 ml-2">
                    <i class="bi bi-dash-circle text-xl mr-2"></i>
                    ลดโต๊ะ
                </Button>
            </div>

            <GridContainer class="mt-8">

                <!-- หมายเลขโต๊ะ -->
               <GraphicsTable :tableNumbers="tableNumbers"/>

            </GridContainer>

            <ConfirmationDialog
            v-if="showDialog"
            :header_message="dialogHeaderMessage"
            :message="dialogMessage"
            :confirmAction="handleDialogConfirm"
            :cancelAction="hideConfirmationDialog"/>

        </ContentContainer>
    </MainContainer>
</template>

<script>
import { reactive } from 'vue';

export default {
    data() {
        return {
            tableNumbers: [],
            counter: reactive({count:0}),

            // Show dialog
            showDialog: false,
            dialogHeaderMessage: '',
            dialogMessage: '',
            dialogAction: '',
        }
    },
    created() {
        this.tableNumbers = this.tableNumbersGen(this.counter.count)
    },
    methods: {
        showConfirmationDialog(action) {
            this.showDialog = true;
            this.dialogAction = action;
            if (this.dialogAction === 'add') {
                this.dialogHeaderMessage = 'Add the table?'
                this.dialogMessage = 'คุณแน่ใจหรือไม่ว่าต้องการเพิ่มจำนวนโต๊ะ?'
            } else if (this.dialogAction === 'delete') {
                this.dialogHeaderMessage = 'Remove the table?'
                this.dialogMessage = 'คุณแน่ใจหรือไม่ว่าต้องการลดจำนวนโต๊ะ?'
            }
        },
        hideConfirmationDialog() {
            this.showDialog = false;
        },
        handleDialogConfirm() {
            if (this.dialogAction === 'add') {
                this.incrementTable();
                this.showDialog = false;
                console.log('Add');
            } else if (this.dialogAction === 'delete') {
                this.decrementTable();
                this.showDialog = false;
                console.log('Delete');
            }
        },

        tableNumbersGen(count) {
            const tableNumbers = []
            for(let i = 1; i<= count; i++) {
                tableNumbers.push(i);
            }
            return tableNumbers
        },
        incrementTable() {
            this.hideConfirmationDialog()
            this.counter.count++
            this.tableNumbers.push(this.tableNumbers.length + 1)
        },
        decrementTable() {
            if (this.tableNumbers.length > 0) {
                this.counter.count--
                this.tableNumbers.pop()
            }
        },
    }


}
</script>