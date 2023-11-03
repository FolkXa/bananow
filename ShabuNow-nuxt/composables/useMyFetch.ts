// import {useAuthStore} from "~/stores/useAuthStore";
type Headers = {[key: string] : string}
export default async function <T>(path: string, options: {}) {
  const config = useRuntimeConfig()
  // const auth = useAuthStore()
    const token = useTokenStore()
  const headers: Headers = {
    "Accept" : "application/json"
  }
  if (token.loggedIn){
    headers['Authorization'] = 'Bearer ${auth.token}'
  }

  return await useLazyFetch<T>(path, {
    ...options,
    baseURL: config.public.apiBaseURL,
    headers: {
      "Accept": "application/json"
    }
  })

}