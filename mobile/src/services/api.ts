import axios from 'axios';
import AsyncStorage from '@react-native-async-storage/async-storage';
import Constants from 'expo-constants';

const baseURL =
  (Constants.expoConfig?.extra?.apiBaseUrl as string | undefined) ??
  'http://10.0.2.2:8000';

export const api = axios.create({
  baseURL,
  headers: { Accept: 'application/json' },
  timeout: 15000,
});

api.interceptors.request.use(async (config) => {
  const token = await AsyncStorage.getItem('@timeplus/token');
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

export async function login(email: string, password: string) {
  const { data } = await api.post('/api/auth/login', { email, password });
  if (data?.token) {
    await AsyncStorage.setItem('@timeplus/token', data.token);
  }
  return data;
}

export async function fetchAppointments() {
  const { data } = await api.get('/api/appointments/upcoming');
  return data?.data ?? [];
}

export async function logout() {
  await AsyncStorage.removeItem('@timeplus/token');
}
