<template>

</template>
<script setup lang="js">
import {useAuthStore} from "~/stores/auth";

const auth = useAuthStore();
if (auth.getUser.role === 'admin') {
  navigateTo("/admins");
} else if (auth.getUser.role === 'staff') {
  navigateTo('/staff/order')
} else {
  navigateTo('/home')
}
</script>