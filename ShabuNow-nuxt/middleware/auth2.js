export default defineNuxtRouteMiddleware((to, from) => {
  const tokenStore = useTokenStore();
  const auth = useAuthStore();
  if (!tokenStore.getStatus) {
    return navigateTo("/login");
  }
  if (auth.getUser.role == "admin") {
    return navigateTo("/admins");
  }
});
