<template>
    <div class="confirmation-dialog">
      <div class="dialog-content">
        <h1 class="text-2xl font-medium mb-2">{{ header_message }}</h1>
        <p>{{ message }}</p>
        <hr class="mt-4 mb-2">
        <div class="dialog-buttons">
            <ButtonBorder class="button" @click="cancelAction">ยกเลิก</ButtonBorder>
            <Button class="button" @click="confirmAction">ตกลง</Button>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    props: {
        header_message: String,
        message: String,
        confirmAction: Function,
        cancelAction: Function,
    }
  };
  </script>
  
  <style scoped>
  .confirmation-dialog {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
  }
  
  .dialog-content {
    background-color: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
    text-align: center;
  }
  
  .dialog-buttons .button {
    margin: 8px;
    padding: 8px 16px;
    cursor: pointer;
  }
  </style>
  